@extends('admin.layouts')

@section('title', $page_title)

@section('scripts')

@endsection
@section('content')


<span id="classID" class="d-none" data-id="{{ $class[0]->id }}"></span>

<div class="container my-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <div class="row">
                <div class="col-2">
                    <a class="text-dark" href="{{ route('class.index') }}"><i class='fa fa-arrow-left fa-xs'></a></i>&nbsp;&nbsp;
                </div>
                @if (Auth::user()->can('class-edit'))
                <div class="col-auto">
                    <div class="dropdown pb-4">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="class_section" class=" mx-1"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                            <li>
                                <span data-id="{{ $class[0]->id }}" id="toggleEdit" class="dropdown-item" style="cursor: pointer;">Edit</span>
                            </li>
                            <li>
                                <span id="toggleDelete" class="dropdown-item" style="cursor: pointer;">Delete</span>
                            </li>

                        </ul>
                    </div>
                </div>
                @else
                <div class="col-auto">
                    <span id="class_section" class=" mx-1"></span>
                </div>
                @endif

            </div>
        </h1>
    </div>

    <div class="row mx-auto">
        <div class="col-8 text-center mx-auto">
            <i class="p-4 bg-secondary rounded-circle fas fa-user fa-3x text-dark-50"></i>
            <h1 id="class_teacher"></h1>
        </div>
    </div>


    <div class="mt-5 d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800"></h1>
        @can('class-add')
        <button id="toggleAddSubject" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add New Subject</button>
        @endcan
    </div>


    <div class="row">
        <div class="div card px-5 py-3">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Subject ID</th>
                            <th>Subjects</th>
                            <th>Description</th>
                            @can('class-delete')
                            <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody id="tbody-subject">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-5 d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800">Enrolled Students</h1>
        @can('class-add')
        <button id="toggleAddStudent" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Enroll New Student</button>
        @endcan
    </div>

    <div class="row">
        <div class="div card px-5 py-3">
            <div class="col">
                <table class="table" id="enrollTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            @can('class-delete')
                            <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody id="tbody-student">
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="subjectForm">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col">
                                @csrf
                                <input type="hidden" id="subject_classroom_id_input" name="classroom_id">
                                <div class="row mb-2 form-group">
                                    <div class="col">
                                        <label class="form-label">Subject</label>
                                        <select class="form-select" id="subject_input" name="subject_id">
                                        </select>
                                        <span class="text-danger" id="subject_error"></span>
                                    </div>
                                </div>
                                <div class="row mb-2 form-group">
                                    <div class="col">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" id="subject_desc_input" cols="30" rows="5"></textarea>
                                        <span class="text-danger" id="subject_desc_error"></span>
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
                        <button class="btn btn-success btn-block" id="btnAddSubject">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enroll Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enroll Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="enrollForm">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col">
                                @csrf
                                <input type="hidden" id="classroom_id_input" name="classroom_id">
                                <div class="row mb-2 form-group">
                                    <div class="col">
                                        <label class="form-label">Student Name</label>
                                        <select class="form-select" id="enroll_student_input" name="student_id">
                                        </select>
                                        <span class="text-danger" id="enroll_student_error"></span>
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
                        <button class="btn btn-success btn-block" id="btnEnrollStudent">Enroll</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Enroll Modal -->
<div class="modal fade" id="deleteModalEnroll" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure? Do you want to remove this student from the class?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="btnDeleteEnroll">Remove</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Enroll Modal -->
<div class="modal fade" id="deleteModalSubject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure? Do you want to remove this subject from the class?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="btnDeleteSubject">Remove</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Class Modal -->
<div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="editClassForm">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col">
                                @csrf
                                <input type="hidden" id="edit_id_input" name="id">
                                <div class="row mb-2 form-group">
                                    <div class="col">
                                        <label class="form-label">Section</label>
                                        <input id="edit_section_input" type="text" class="form-control" name="section">
                                        <span class="text-danger" id="edit_section_error"></span>
                                    </div>
                                </div>
                                <div class="row mb-2 form-group">
                                    <div class="col">
                                        <label class="form-label">Teacher</label>
                                        <input id="edit_teacher_input" type="text" class="form-control" name="teacher">
                                        <span class="text-danger" id="edit_teacher_error"></span>
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
                        <button class="btn btn-primary btn-block" id="btnSaveClass">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Class Modal -->
<div class="modal fade" id="deleteClassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure? Do you want to delete this class?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="btnDeleteClass">Delete</button>
            </div>
        </div>
    </div>
</div>




@endsection

@section('footer')

<script>
    let id = $('#classID').data('id');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function pageLoad() {
        $.ajax({
            type: 'GET',
            url: '/selectClassroom/' + id,
            success: function(res) {
                $("#class_section").text(res.class[0].section);
                $("#class_teacher").text(res.class[0].teacher);
            }
        });
    }

    function enrolledTableLoad() {
        $("#tbody-student").empty();
        $.ajax({
            type: 'GET',
            url: '/getClassEnrolled/' + id,
            success: function(res) {

                if (res.status === 400) {
                    let tr = $(
                        "<tr>" +
                        "<td class='text-center' colspan='3'>No data available in table</td>" +
                        "</tr>"
                    );
                    $("#tbody-student").append(tr);
                }

                if (res.status === 200) {

                    res.class.forEach(function(element, i) {


                        let studentID = "URD-0";

                        if (element.student_id.toString().length > 1) {
                            studentID += "0";
                        } else {
                            studentID += "00";
                        }

                        let tr = $(
                            "<tr>" +
                            "<td>" + studentID + element.student_id + "</td>" +
                            "<td>" + element.std_name + "</td>" +
                            "@can('class-delete')" +
                            "<td>" +
                            "<button class='toggleDeleteEnroll btn-sm btn-danger me-2' data-id='" + element.classroom_student_id + "'><i class='fa fa-trash'></i></button></td>" +
                            "@endcan" +
                            "</tr>"
                        );
                        $("#tbody-student").append(tr);
                    });
                }
            }
        });

    }

    function subjectTableLoad() {

        $("#tbody-subject").empty();

        $.ajax({
            type: 'GET',
            url: '/getSelectedSubject/' + id,
            success: function(res) {

                if (res.status === 400) {
                    let tr = $(
                        "<tr>" +
                        "<td class='text-center' colspan='4'>No data available in table</td>" +
                        "</tr>"
                    );
                    $("#tbody-subject").append(tr);
                }

                if (res.status === 200) {

                    res.subjectList.forEach(function(element, i) {


                        let studentID = "SUB-0";

                        if (element.subject_id.toString().length > 1) {
                            studentID += "0";
                        } else {
                            studentID += "00";
                        }

                        let tr = $(
                            "<tr>" +
                            "<td>" + studentID + element.subject_id + "</td>" +
                            "<td>" + element.subject_name + "</td>" +
                            "<td>" + element.description + "</td>" +
                            "@can('class-delete')" +
                            "<td>" +
                            "<button class='toggleDeleteSubject btn-sm btn-danger me-2' data-id='" + element.subject_list_id + "'><i class='fa fa-trash'></i></button></td>" +
                            "@endcan" +
                            "</tr>"

                        );
                        $("#tbody-subject").append(tr);
                    });
                }
            }
        });

    }


    $(document).ready(function() {

        pageLoad();
        enrolledTableLoad();
        subjectTableLoad();

        $('#toggleAddStudent').on('click', function(ev) {
            ev.preventDefault();


            $('#enroll_student_input').find('option').remove().end();

            $.ajax({
                type: 'GET',
                url: '/getNotEnrolledStudents/' + id,
                success: function(data) {
                    if (data.data.length != 0) {
                        data.data.forEach(element => {
                            $('#enroll_student_input').append($('<option>', {
                                value: element[0].id,
                                text: element[0].std_name
                            }));
                        });

                        $('#classroom_id_input').val(id);

                        $('#addStudentModal').modal('show');
                    } else {
                        new swal({
                            title: 'No student to enroll',
                            icon: 'info'
                        });
                    }


                },
                error: function(data) {
                    console.log(data);
                    new swal({
                        title: 'Error',
                        text: 'No student to enroll',
                        icon: 'error'
                    });
                }
            });

        });

        $(document).on('click', '#toggleEdit', function() {
            var id = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '/selectClassroom/' + id,
                success: function(data) {
                    $("#editClassForm").attr('action', '/class-edit/' + id);
                    $('#edit_id_input').val(data.class[0].id);
                    $('#edit_section_input').val(data.class[0].section);
                    $('#edit_teacher_input').val(data.class[0].teacher);

                    $('#editClassModal').modal('show');
                    enrolledTableLoad();
                },
                error: function(data) {
                    console.log(data);
                    new swal({
                        title: 'Error',
                        text: 'ID not found.',
                        icon: 'error'
                    });
                }
            });

        });

        $(document).on('click', '#toggleDelete', function() {
            $.ajax({
                type: 'GET',
                url: '/selectClassroom/' + id,
                success: function(data) {
                    $('#deleteClassModal').modal('show');
                },
                error: function(data) {
                    console.log(data);
                    new swal({
                        title: 'Error',
                        text: 'ID not found.',
                        icon: 'error'
                    });
                }
            });

        });

        $(document).on('click', '.toggleDeleteEnroll', function() {

            let id = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '/selectClassroom_Student/' + id,
                success: function(data) {
                    $('#btnDeleteEnroll').val(id);
                    $('#deleteModalEnroll').modal('show');
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

        $(document).on('click', '#toggleAddSubject', function(ev) {
            ev.preventDefault();

            $('#subject_input').find('option').remove().end();

            $.ajax({
                type: 'GET',
                url: '/getAllowedSubjects/' + id,
                success: function(data) {

                    if (data.data.length != 0) {
                        data.data.forEach(element => {
                            $('#subject_input').append($('<option>', {
                                value: element.id,
                                text: element.subject_name
                            }));
                        });

                        $('#subject_classroom_id_input').val(id);

                        $('#addSubjectModal').modal('show');
                    } else {
                        new swal({
                            title: 'No Subject to add',
                            icon: 'info'
                        });
                    }


                },
                error: function(data) {
                    new swal({
                        text: 'No subject to add',
                        icon: 'info'
                    });
                }
            });
        });

        $(document).on('click', '.toggleDeleteSubject', function() {

            let id = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '/selectSubjectList/' + id,
                success: function(data) {
                    $('#btnDeleteSubject').val(id);
                    $('#deleteModalSubject').modal('show');
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


        $('#btnAddSubject').on('click', function(ev) {
            ev.preventDefault();
            $('#btnAddSubject').prop('disabled', true);
            $('#btnAddSubject').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $('#subject_error').html("");
            $('#subject_desc_error').html("");


            let addForm = $("#subjectForm")[0];
            let addFormData = new FormData(addForm);

            $.ajax({
                type: "post",
                url: "{{ route('class.subject_add') }}",
                data: addFormData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {

                    if (res.status === 400) {
                        if (res.error.subject_id != null) {
                            $('#subject_error').html(res.error.subject_id);
                        }
                        if (res.error.description != null) {
                            $('#subject_desc_error').html(res.error.description);
                        }
                    }

                    if (res.status === 200) {
                        $('#subject_error').html("");
                        $('#subject_desc_error').html("");


                        $('#subject_input').val('');
                        $('#subject_desc_input').val('');

                        new swal({
                            title: 'Success',
                            text: 'Added Successfully',
                            icon: 'success',
                        });

                        $('#addSubjectModal').modal('hide');
                        subjectTableLoad();
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });

            $('#btnAddSubject').prop('disabled', false);
            $('#btnAddSubject').html("Add");


        });

        $('#btnEnrollStudent').on('click', function(ev) {
            ev.preventDefault();
            $('#btnEnrollStudent').prop('disabled', true);
            $('#btnEnrollStudent').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $('#enroll_student_error').html("");

            let addForm = $("#enrollForm")[0];
            let addFormData = new FormData(addForm);

            $.ajax({
                type: "post",
                url: "{{ route('class.class_enroll') }}",
                data: addFormData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {

                    if (res.status === 400) {
                        if (res.error.student_id != null) {
                            $('#enroll_student_error').html(res.error.student_id);
                        }
                    }

                    if (res.status === 200) {
                        $('#enroll_student_error').html("");

                        $('#enroll_student_input').val('');
                        new swal({
                            title: 'Success',
                            text: 'Enrolled Successfully',
                            icon: 'success',
                        });

                        $('#addStudentModal').modal('hide');
                        enrolledTableLoad();

                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });

            $('#btnEnrollStudent').prop('disabled', false);
            $('#btnEnrollStudent').html("Enroll");


        });

        $('#btnDeleteEnroll').on('click', function(ev) {

            ev.preventDefault();
            let classroom_student_id = $('#btnDeleteEnroll').val();

            $('#btnDeleteEnroll').prop('disabled', true);
            $('#btnDeleteEnroll').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $.ajax({
                type: "DELETE",
                contentType: "application/json; charset=utf-8",
                url: "/class/enroll-delete/" + classroom_student_id,
                data: "",
                dataType: "JSON",
                success: function(data) {
                    new swal({
                        title: 'Success!',
                        text: 'Record Deleted Successfully!',
                        icon: 'success',
                        confirmButtonText: "Ok",
                    });
                    enrolledTableLoad();

                },
                error: function(response) {
                    console.log(response.responseJSON.msg);
                    new swal({
                        title: 'Error',
                        text: 'Something went wrong',
                        icon: 'error'
                    });
                }
            });

            $('#deleteModalEnroll').modal('hide');
            $('#btnDeleteEnroll').prop('disabled', false);
            $('#btnDeleteEnroll').html("Remove");


        });

        $('#btnSaveClass').on('click', function(ev) {
            ev.preventDefault();
            $('#btnSaveClass').prop('disabled', true);
            $('#btnSaveClass').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $('#edit_section_error').html("");
            $('#edit_teacher_error').html("");

            let editForm = $("#editClassForm")[0];
            let editFormData = new FormData(editForm);

            let id = editFormData.get('id');

            $.ajax({
                type: "post",
                url: "/class/class-edit/" + id,
                data: editFormData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {

                    if (res.status === 400) {
                        if (res.error.section != null) {
                            $('#edit_section_error').html(res.error.section);
                        }
                        if (res.error.teacher != null) {
                            $('#edit_teacher_error').html(res.error.teacher);
                        }
                    }

                    if (res.status === 200) {
                        new swal({
                            title: 'Success',
                            text: 'Record Updated Successfully!',
                            icon: 'success',
                        });
                        $('#editClassModal').modal('hide');
                        pageLoad();
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });

            $('#btnSaveClass').prop('disabled', false);
            $('#btnSaveClass').html("Save");
        });

        $('#btnDeleteClass').on('click', function(ev) {
            ev.preventDefault();
            $('#btnDeleteClass').prop('disabled', true);
            $('#btnDeleteClass').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $.ajax({
                type: "DELETE",
                contentType: "application/json; charset=utf-8",
                url: "/class/class-delete/" + id,
                data: "",
                dataType: "JSON",
                success: function(data) {
                    new swal({
                        title: 'Success!',
                        text: 'Record Deleted Successfully!',
                        icon: 'success',
                        confirmButtonText: "Ok",
                    }).then(function() {
                        window.location.href = "{{ route('class.index') }}"
                    });
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

            $('#btnDeleteClass').prop('disabled', false);
            $('#btnDeleteClass').html("Delete");


        });

        $('#btnDeleteSubject').on('click', function(ev) {

            ev.preventDefault();
            let subjectList_id = $('#btnDeleteSubject').val();

            $('#btnDeleteSubject').prop('disabled', true);
            $('#btnDeleteSubject').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $.ajax({
                type: "DELETE",
                contentType: "application/json; charset=utf-8",
                url: "/class/subject-delete/" + subjectList_id,
                data: "",
                dataType: "JSON",
                success: function(data) {
                    new swal({
                        title: 'Success!',
                        text: 'Record Deleted Successfully!',
                        icon: 'success',
                        confirmButtonText: "Ok",
                    });
                    subjectTableLoad();

                },
                error: function(response) {
                    console.log(response.responseJSON.msg);
                    new swal({
                        title: 'Error',
                        text: 'Something went wrong',
                        icon: 'error'
                    });
                }
            });

            $('#deleteModalSubject').modal('hide');
            $('#btnDeleteSubject').prop('disabled', false);
            $('#btnDeleteSubject').html("Remove");


        });

    });
</script>
@endsection