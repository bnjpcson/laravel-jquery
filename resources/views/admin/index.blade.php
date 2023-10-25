@extends('admin.layouts')

@section('title', 'Home')

@section('scripts')

@endsection
@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-6">
            <h1 class="sample">Student Table</h1>
        </div>
        <div class="col-6 text-end">
            <button type='button' class='btn btn-success' id="toggleAdd">Add Student</button>
        </div>
    </div>
    <div class="row p-5 bg-light border rounded">
        <div class="col">
            <button type='button' class='btn btn-danger' id="toggleDeleteAll">Delete</button>
            <table class="table" id="studentTable">
                <thead>
                    <tr>
                        <th>
                        </th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="addForm" class="create-student" action="{{ route('home.store') }}" method="post">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col">
                                    @csrf
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Name</label>
                                            <input id="add_std_name_input" type="text" class="form-control" name="std_name">
                                            <span class="text-danger" id="add_std_name_error"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Address</label>
                                            <input id="add_std_address_input" type="text" class="form-control" name="std_address" placeholder="">
                                            <span class="text-danger" id="add_std_address_error"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3 form-group">
                                        <div class="col">
                                            <label class="form-label">Contact Number</label>
                                            <input id="add_std_contactno_input" type="tel" class="form-control" name="std_contactno" placeholder="">
                                            <span class="text-danger" id="add_std_contactno_error"></span>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="editForm" action="" method="post">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col">
                                    @csrf
                                    <input type="hidden" id="edit_id_input" name="id">
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Name</label>
                                            <input id="edit_std_name_input" type="text" class="form-control" name="std_name">
                                            <span class="text-danger" id="edit_std_name_error"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Address</label>
                                            <input id="edit_std_address_input" type="text" class="form-control" name="std_address" placeholder="">
                                            <span class="text-danger" id="edit_std_address_error"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3 form-group">
                                        <div class="col">
                                            <label class="form-label">Contact Number</label>
                                            <input id="edit_std_contactno_input" type="tel" class="form-control" name="std_contactno" placeholder="">
                                            <span class="text-danger" id="edit_std_contactno_error"></span>
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
                            <button class="btn btn-primary btn-block" id="btnSave">Save</button>
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

    <!-- Delete All Modal -->
    <div class="modal fade" id="deleteAllModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure? Do you want to delete the selected record.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="btnDeleteAll">Delete</button>
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
    $('#toggleDeleteAll').prop('disabled', true);

    let tableName = $('#studentTable').DataTable();

    function selectRecord(params) {

        if (params) numberOfSelected++;
        else numberOfSelected--;

        if (numberOfSelected > 0) {
            $('#toggleDeleteAll').prop('disabled', false);
        } else {
            $('#toggleDeleteAll').prop('disabled', true);
        }

    }

    function tableReload() {

        tableName.clear();
        $('#toggleDeleteAll').prop('disabled', true);


        $.ajax({
            type: "GET",
            contentType: "application/json; charset=utf-8",
            url: "{{ route('home.getStudents') }}",
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
                            "<td><input class='checkBoxClass form-check-input' name='checked[]' type='checkbox' value='" + record.id + "' onclick='selectRecord(this.checked);'></td>" +
                            "<td>" + record.id + "</td>" +
                            "<td>" + record.std_name + "</td>" +
                            "<td>" + record.std_address + "</td>" +
                            "<td>" + record.std_contactno + "</td>" +
                            "<td>" + record.date + "</td>" +
                            "<td>" + "<button class='toggleEdit btn btn-warning me-2' data-id='" + record.id + "'><i class='fa fa-edit'></i></button>" + "<button type='button' class='toggleDelete btn btn-danger' data-id='" + record.id + "'><i class='fa fa-trash'></i></button>" + "</td>" +
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

        $(document).on('click', '.toggleEdit', function() {
            var id = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '/select/' + id,
                success: function(data) {
                    $("#editForm").attr('action', '/edit/' + id);
                    $('#edit_id_input').val(data.student[0].id);
                    $('#edit_std_name_input').val(data.student[0].std_name);
                    $('#edit_std_address_input').val(data.student[0].std_address);
                    $('#edit_std_contactno_input').val(data.student[0].std_contactno);

                    $('#editModal').modal('show');
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

        $(document).on('click', '.toggleDelete', function() {
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: '/select/' + id,
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

        $(document).on('click', '#toggleDeleteAll', function() {
            $('#deleteAllModal').modal('show');
        });


        $('#btnAdd').on('click', function(ev) {
            ev.preventDefault();
            $('#btnAdd').prop('disabled', true);
            $('#btnAdd').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $('#add_std_name_error').html("");
            $('#add_std_address_error').html("");
            $('#add_std_contactno_error').html("");

            let addForm = $("#addForm")[0];
            let addFormData = new FormData(addForm);

            $.ajax({
                type: "post",
                url: "{{ route('home.addstore') }}",
                data: addFormData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {
                    $('#btnAdd').prop('disabled', false);
                    $('#btnAdd').html("Add");
                    if (res.status === 400) {
                        if (res.error.std_name != null) {
                            $('#add_std_name_error').html(res.error.std_name);
                        }
                        if (res.error.std_address != null) {
                            $('#add_std_address_error').html(res.error.std_address);
                        }
                        if (res.error.std_contactno != null) {
                            $('#add_std_contactno_error').html(res.error.std_contactno);
                        }
                    }

                    if (res.status === 200) {
                        $('#add_std_name_error').html("");
                        $('#add_std_address_error').html("");
                        $('#add_std_contactno_error').html("");

                        $('#add_std_name_input').val('');
                        $('#add_std_address_input').val('');
                        $('#add_std_contactno_input').val('');
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

        $('#btnSave').on('click', function(ev) {
            ev.preventDefault();
            $('#btnSave').prop('disabled', true);
            $('#btnSave').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $('#edit_std_name_error').html("");
            $('#edit_std_address_error').html("");
            $('#edit_std_contactno_error').html("");

            let editForm = $("#editForm")[0];
            let editFormData = new FormData(editForm);

            let id = editFormData.get('id');

            $.ajax({
                type: "post",
                url: "/edit/" + id,
                data: editFormData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {
                    $('#btnSave').prop('disabled', false);
                    $('#btnSave').html("Save");
                    if (res.status === 400) {
                        if (res.error.std_name != null) {
                            $('#edit_std_name_error').html(res.error.std_name);
                        }
                        if (res.error.std_address != null) {
                            $('#edit_std_address_error').html(res.error.std_address);
                        }
                        if (res.error.std_contactno != null) {
                            $('#edit_std_contactno_error').html(res.error.std_contactno);
                        }
                    }

                    if (res.status === 200) {
                        new swal({
                            title: 'Success',
                            text: 'Record Updated Successfully!',
                            icon: 'success',
                        });

                        $('#editModal').modal('hide');
                        tableReload();
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });
        });

        $('#btnDelete').on('click', function(ev) {

            $.ajax({
                type: "DELETE",
                contentType: "application/json; charset=utf-8",
                url: "/delete/" + $('#btnDelete').val(),
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

        $('#btnDeleteAll').on('click', function(ev) {

            let num = [];
            $('.checkBoxClass').each(function(index) {
                let id = $(this).val();
                if ($(this).is(':checked')) {
                    num.push(id);
                }
            });

            $.ajax({
                type: "DELETE",
                contentType: "application/json; charset=utf-8",
                url: "/deleteAll/" + num,
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

            $('#deleteAllModal').modal('hide');

        });

    });
</script>
@endsection