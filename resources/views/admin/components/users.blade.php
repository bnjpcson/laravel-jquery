@extends('admin.layouts')

@section('title', $page_title)

@section('scripts')

@endsection
@section('content')
<div class="container my-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Users Table</h1>
        <button type='button' class='d-none d-sm-inline-block btn btn-sm btn-success shadow-sm' id="toggleAdd">Add User</button>
    </div>
    <div class="row p-3 bg-light border rounded">
        <div class="col">
            <div style="overflow-x:scroll;">
                <table class="display responsive table w-100" cellspacing="0" id="rolesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="addForm" class="create-student" action="{{ route('roles.store') }}" method="post">
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
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Email</label>
                                            <input id="add_email_input" type="email" class="form-control" name="email">
                                            <span class="text-danger" id="add_email_error"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Password</label>
                                            <input id="add_password_input" type="password" class="form-control" name="password">
                                            <span class="text-danger" id="add_password_error"></span>
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
                                            <input id="edit_name_input" type="text" class="form-control" name="name" readonly>
                                            <span class="text-danger" id="edit_name_error"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Email</label>
                                            <input id="edit_email_input" type="email" class="form-control" name="email">
                                            <span class="text-danger" id="edit_email_error"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2 form-group">
                                        <div class="col">
                                            <label class="form-label">Roles:</label>

                                            <div id="checklist"></div>

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

    let tableName = $('#rolesTable').DataTable({
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
            url: "{{ route('users.get-all-users') }}",
            data: "",
            dataType: "JSON",
            success: function(data) {
                if (data.data.length === 0 || typeof data.data === "undefined" || typeof data
                    .data === null) {
                    tableName.draw();
                } else {
                    $.map(data.data, function(record) {


                        let roles = [];

                        $.map(record.roles, function(role) {
                            let element = "<span class='d-flex justify-content-center mt-2 px-2 text-center py-1 bg-secondary text-white rounded-pill'>" + role.name + "</span>";
                            roles.push(element);
                        });


                        if (roles == "") {
                            roles = "None";
                        } else {
                            roles = roles.join(" ");
                        }

                        let tableRow = "<tr>" +
                            "<td>" + record.id + "</td>" +
                            "<td>" + record.name + "</td>" +
                            "<td>" + record.email + "</td>" +

                            "<td><ul>" + roles + "</ul></td>" +
                            "<td>" +
                            "<button class='toggleEdit btn-sm btn-warning me-2' data-id='" + record.id + "'>" +
                            "<i class='fa fa-edit'></i>" +
                            "</button>" +
                            "<button type='button' class='toggleDelete btn-sm btn-danger' data-id='" + record.id + "'>" +
                            "<i class='fa fa-trash'></i>" +
                            "</button>" +
                            "</td>" +
                            "</tr>";

                        const tr = $(tableRow);




                        tableName.row.add(tr[0]).draw();
                    });
                }
            },
        });
    }

    function addCheckbox(name, hasrole) {
        var container = $('#checklist');

        var formcheck = $('<div />', {
            class: 'form-check'
        }).appendTo(container);

        $('<input />', {
            type: 'checkbox',
            id: name,
            value: name,
            name: 'roles[]',
            class: 'form-check-input',
            checked: hasrole
        }).appendTo(formcheck);

        $('<label />', {
            'for': name,
            text: name,
            class: "form-check-label"
        }).appendTo(formcheck);
    }


    $(document).ready(function() {

        tableReload();

        $(document).on('click', '#toggleAdd', function() {
            $('#addModal').modal('show');
        });

        $(document).on('click', '.toggleEdit', function() {
            var id = $(this).data('id');

            var url = "{{ route('users.edit-user', ':id') }}";
            url = url.replace(':id', id);

            $('#checklist').empty();

            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    $("#editForm").attr('action', url);
                    $('#edit_id_input').val(data.user.id);
                    $('#edit_name_input').val(data.user.name);
                    $('#edit_email_input').val(data.user.email);

                    data.data.forEach(element => {
                        addCheckbox(element.role, element.hasrole);
                    });

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

            var url = "{{ route('users.select-user', ':id') }}";
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
            $('#add_email_error').html("");
            $('#add_password_error').html("");


            let addForm = $("#addForm")[0];
            let addFormData = new FormData(addForm);

            $.ajax({
                type: "post",
                url: "{{ route('users.store') }}",
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
                        if (res.error.email != null) {
                            $('#add_email_error').html(res.error.email);
                        }
                        if (res.error.password != null) {
                            $('#add_password_error').html(res.error.password);
                        }
                    }

                    if (res.status === 200) {
                        $('#add_name_error').html("");
                        $('#add_email_error').html("");
                        $('#add_password_error').html("");


                        $('#add_name_input').val('');
                        $('#add_email_input').val('');
                        $('#add_password_input').val('');

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


            var url = "{{ route('users.save-user', ':id') }}";
            url = url.replace(':id', id);


            $.ajax({
                type: "post",
                url: url,
                data: editFormData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {
                    $('#btnSave').prop('disabled', false);
                    $('#btnSave').html("Save");

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
            }).done(function(data) {
                $('#btnSave').prop('disabled', false);
                $('#btnSave').html("Save");
            });
        });

        $('#btnDelete').on('click', function(ev) {

            var url = "{{ route('users.delete-user', ':id') }}";
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