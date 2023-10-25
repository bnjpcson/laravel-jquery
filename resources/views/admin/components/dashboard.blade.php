@extends('admin.layouts')

@section('title', $page_title)

@section('scripts')

@endsection
@section('content')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="container my-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none text-dark" href="{{route('class.index')}}">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    No. of class</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $class }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-edit fa-2x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none text-dark" href="{{route('subjects.index')}}">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Subjects</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subjects }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pen fa-2x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none text-dark" href="{{route('students.index')}}">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    No. of Students</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">5%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 5%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection



@section('footer')

@endsection