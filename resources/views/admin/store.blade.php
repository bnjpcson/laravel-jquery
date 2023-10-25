@extends('admin.layouts')

@section('content')
<div class="container my-5">
    <div class="row my-5">
        <div class="col">
            <div class="container">
                <div class="row">
                    <div class="col-6 bg-light p-3 mx-auto rounded border">
                        <h1 class="text-center">Add Student</h1>
                        <div class="left">
                            @if (session()->has('message'))
                            <div class="alert alert-dismissible" style="color:green;">
                                {{ session('message') }}
                            </div>
                            @endif
                        </div>
                        <form class="create-student" action="{{ route('home.store') }}" method="post">
                            @csrf
                            <div class="container">
                                <div class="row mb-3">
                                    <div class="col">
                                        @csrf
                                        <div class="row mb-2 form-group">
                                            <div class="col">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" name="std_name">
                                            </div>
                                        </div>
                                        <div class="row mb-2 form-group">
                                            <div class="col">
                                                <label class="form-label">Address</label>
                                                <input type="text" class="form-control" name="std_address" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3 form-group">
                                            <div class="col">
                                                <label class="col-sm-3 col-form-label">Contact Number</label>
                                                <input type="tel" class="form-control" name="std_contactno" placeholder="">
                                                @error('std_contactno')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-12 text-end">
                                                <a href="{{ route('home.index') }}" class="btn btn-danger btn-block">Cancel</a>
                                                <button type="submit" class="btn btn-success btn-block">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.addEventListener('load', function() {
        $(".create-student").validate({
            rules: {
                std_name: {
                    required: true,
                },
                std_address: {
                    required: true,
                },
                std_contactno: {
                    required: true,
                    minlength: 11,
                    maxlength: 11
                },
            },
            messages: {
                std_name: {
                    required: "Name is required",
                },
                std_address: {
                    required: "Address is required",
                },
                std_contactno: {
                    required: "Contact number is required",
                    minlength: "Contact number must be 11 digits",
                    maxlength: "Contact number must be 11 digits"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection