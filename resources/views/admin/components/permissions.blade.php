@extends('admin.layouts')

@section('title', $page_title)

@section('scripts')

@endsection
@section('content')
<div class="container my-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Permissions Table</h1>
        <button type='button' class='d-none d-sm-inline-block btn btn-sm btn-success shadow-sm' id="toggleAdd">Add Permission</button>
    </div>
    <div class="row p-3 bg-light border rounded">
        <div class="col">
            <div style="overflow-x:scroll;">
                <table class="display responsive table w-100" cellspacing="0" id="permissionsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="addForm" class="create-student" action="{{ route('permissions.store') }}" method="post">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col">
                                    @csrf
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Name</label>
                                            <input id="add_name_input" type="text" class="form-control" name="name">
                                            <span class="text-danger" id="add_name_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row mb-2">
                        <div class="col-sm-12 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-success btn-block" id="btnAdd">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure? Do you want to delete this record.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="btnDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>


</div>


@endsection



@section('footer')

<script>
    let numberOfSelected = 0;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let tableName = $('#permissionsTable').DataTable({
        'columnDefs': [{
            'orderable': false, // set orderable false for selected columns
        }],
        responsive: true
    });


    function tableReload() {

        tableName.clear();

        $.ajax({
            type: "GET",
            contentType: "application/json; charset=utf-8",
            url: "{{ route('permissions.get-all-permissions') }}",
            data: "",
            dataType: "JSON",
            success: function(data) {

                if (data.data.length === 0 || typeof data.data === "undefined" || typeof data
                    .data === null) {
                    tableName.draw();
                } else {
                    $.map(data.data, function(record) {

                        const tr = $(
                            "<tr>" +
                            "<td>" + record.id + "</td>" +
                            "<td>" + record.name + "</td>" +
                            "<td>" +
                            "<button type='button' class='toggleDelete btn-sm btn-danger' data-id='" + record.id + "'>" +
                            "<i class='fa fa-trash'></i>" +
                            "</button>" +
                            "</td>" +
                            "</tr>"
                        );

                        tableName.row.add(tr[0]).draw();
                    });
                }
            },
        });
    }

    $(document).ready(function() {

        tableReload();

        $(document).on('click', '#toggleAdd', function() {
            $('#addModal').modal('show');
        });

        $(document).on('click', '.toggleDelete', function() {
            var id = $(this).data('id');

            var url = "{{ route('permissions.select-permission', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    $('#btnDelete').val(id);
                    $('#deleteModal').modal('show');
                },
                error: function(data) {
                    console.log(data);
                    new swal({
                        title: 'Error',
                        text: 'Something went wrong',
                        icon: 'error'
                    });
                }
            });

        });

        $('#btnAdd').on('click', function(ev) {
            ev.preventDefault();
            $('#btnAdd').prop('disabled', true);
            $('#btnAdd').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $('#add_name_error').html("");

            let addForm = $("#addForm")[0];
            let addFormData = new FormData(addForm);

            $.ajax({
                type: "post",
                url: "{{ route('permissions.store') }}",
                data: addFormData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {
                    $('#btnAdd').prop('disabled', false);
                    $('#btnAdd').html("Add");
                    if (res.status === 400) {
                        if (res.error.name != null) {
                            $('#add_name_error').html(res.error.name);
                        }
                    }

                    if (res.status === 200) {
                        $('#add_name_error').html("");

                        $('#add_name_input').val('');
                        new swal({
                            title: 'Success',
                            text: 'Inserted Successfully',
                            icon: 'success',
                        });

                        $('#addModal').modal('hide');
                        tableReload();

                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });


        });

        $('#btnDelete').on('click', function(ev) {

            var url = "{{ route('permissions.delete-permission', ':id') }}";
            url = url.replace(':id', $('#btnDelete').val());

            $.ajax({
                type: "DELETE",
                contentType: "application/json; charset=utf-8",
                url: url,
                data: "",
                dataType: "JSON",
                success: function(data) {
                    new swal({
                        title: 'Success!',
                        text: 'Record Deleted Successfully!',
                        icon: 'success'
                    });

                    tableReload();
                },
                error: function(response) {
                    console.log(response);
                    new swal({
                        title: 'Error',
                        text: 'Something went wrong',
                        icon: 'error'
                    });
                }
            });

            $('#deleteModal').modal('hide');

            $('#btnDelete').val();
        });


    });
</script>
@endsection