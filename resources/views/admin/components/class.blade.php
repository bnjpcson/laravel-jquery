@extends('admin.layouts')

@section('title', $page_title)

@section('scripts')

@endsection
@section('content')


<div class="container my-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Class List</h1>
        @can('class-add')
        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="toggleAddClass"><i class="fas fa-plus fa-sm text-white-50"></i> Add new class</button>
        @endcan
    </div>


    <div id="content" class="row">

    </div>




</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="addForm">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col">
                                @csrf

                                <div class="row mb-2 form-group">
                                    <div class="col">
                                        <label class="form-label">Section</label>
                                        <input id="add_section_input" type="text" class="form-control" name="section">
                                        <span class="text-danger" id="add_section_error"></span>
                                    </div>
                                </div>

                                <div class="row mb-2 form-group">
                                    <div class="col">
                                        <label class="form-label">Teacher</label>
                                        <input id="add_teacher_input" type="text" class="form-control" name="teacher">
                                        <span class="text-danger" id="add_teacher_error"></span>
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
                        <button class="btn btn-success btn-block" id="btnAddClass">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection



@section('footer')

<script>
    $('#content').hide();

    function contentReload() {
        $('#content').empty();
        $.ajax({
            type: "GET",
            contentType: "application/json; charset=utf-8",
            url: "{{ route('class.getclassroomdata') }}",
            data: "",
            dataType: "JSON",
            success: function(data) {


                if (data.data.length == 0 || typeof data.data === "undefined" || typeof data
                    .data === null) {

                    let toAppend = $("<div class=\"col-10 mx-auto text-center\">" +
                        "<img class=\"w-50 img-fluid\" src=\"{{ asset('images/svg/nodata.svg') }}\" alt=\"...\">" +
                        "</div>");
                    $('#content').prepend(toAppend);
                    $('#content').show();

                } else {
                    $.map(data.data, function(record) {

                        const cols = $(
                            "<div class=\"col-xl-3 col-md-6 mb-4\">" +
                            "<a href=\"/class/" + record.id + "\" class=\"text-dark text-decoration-none\">" +
                            "<div class=\"card border-left-primary shadow h-100 py-2\">" +
                            "<div class=\"card-body\">" +
                            "<div class=\"row no-gutters align-items-center\">" +
                            " <div class=\"col mr-2\">" +
                            "<div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1 text-center\">" +

                            "</div>" +
                            "<div class=\"h5 mb-0 font-weight-bold text-gray-800\"></div>" +
                            "</div>" +
                            " </div>" +
                            "<div class=\"row no-gutters align-items-center\">" +
                            "<div class=\"col mr-2 text-center\">" +
                            "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">" + record.section + "</div>" +
                            "<div class=\"h6 mb-0 font-weight-bold text-gray-800 mt-3\">" + record.teacher + "</div>" +
                            "</div>" +
                            "</div>" +
                            "</div>" +
                            "</div>" +
                            "</a>" +

                            " </div>"
                        );

                        $('#content').prepend(cols);
                        $('#content').show();

                    });
                }
            },
        });
    }

    $(document).ready(function() {

        contentReload();

        $('#toggleAddClass').on('click', function(ev) {
            ev.preventDefault();
            $('#addClassModal').modal('show');
        });


        $('#add_section_input').on('change paste keyup', function(ev) {
            if ($('#add_section_input').val() != '') {
                $('#add_section_error').html("");
                $("#add_section_input").removeClass("is-invalid");

            } else {
                $('#add_section_error').html("Section field is required.");
                $("#add_section_input").addClass("is-invalid");
            }
        });

        $('#add_teacher_input').on('change paste keyup', function(ev) {
            if ($('#add_teacher_input').val() != '') {
                $('#add_teacher_error').html("");
                $("#add_teacher_input").removeClass("is-invalid");
            } else {
                $('#add_teacher_error').html("Teacher field is required.");
                $("#add_teacher_input").addClass("is-invalid");
            }

        });

        $('#btnAddClass').on('click', function(ev) {
            ev.preventDefault();

            $('#btnAddClass').prop('disabled', true);
            $('#btnAddClass').html("<i class='fa fa-spinner fa-spin'></i> Loading");

            $('#add_teacher_error').html("");
            $("#add_teacher_input").removeClass("is-invalid");

            $('#add_section_error').html("");
            $("#add_section_input").removeClass("is-invalid");


            let addForm = $("#addForm")[0];
            let addFormData = new FormData(addForm);

            $.ajax({
                type: "post",
                url: "{{ route('class.store') }}",
                data: addFormData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {

                    if (res.status === 400) {
                        if (res.error.section != null) {
                            $("#add_section_input").addClass("is-invalid");
                            $('#add_section_error').html(res.error.section);
                        }
                        if (res.error.teacher != null) {
                            $("#add_teacher_input").addClass("is-invalid");
                            $('#add_teacher_error').html(res.error.teacher);
                        }
                    }

                    if (res.status === 200) {
                        $('#add_teacher_error').html("");
                        $("#add_teacher_input").removeClass("is-invalid");

                        $('#add_section_error').html("");
                        $("#add_section_input").removeClass("is-invalid");


                        $('#add_section_input').val('');
                        $('#add_teacher_input').val('');

                        new swal({
                            title: 'Success',
                            text: 'Inserted Successfully',
                            icon: 'success',
                        });

                        $('#addClassModal').modal('hide');

                        contentReload();

                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });

            $('#btnAddClass').prop('disabled', false);
            $('#btnAddClass').html("Add");


        });


    });
</script>


@endsection