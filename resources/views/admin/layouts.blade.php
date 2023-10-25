<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaravelJquery - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>



    @yield('scripts')
</head>

<body>

    <style>
        body {
            background-color: #EAEAEE !important;
        }

        .nav-link {
            color: white;
            padding-left: 20px !important;
            padding-right: 20px !important;
        }

        .nav-item {
            width: 100%;
            margin-top: 10px;
        }

        .nav-active {
            color: black !important;
            background-color: white;
            border-radius: 10px;
        }

        .nav-link:hover {
            background-color: white;
            color: black !important;
            border-radius: 10px;

        }
    </style>

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2" style="background-color: #C00000">
                <div class="sticky-top d-flex flex-column align-items-center align-items-sm-center pt-2 text-white min-vh-100">
                    <a href="/" class="my-3 d-flex align-items-center pb-3 mb-md-0 mx-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline" style="border: 2px solid white; padding: 2px 30px;">Laravel</span>
                    </a>
                    <ul class="mt-3 nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-center" id="menu">
                        <!-- <li>
                            <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                <i class='fa fa-dashboard'></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span> </a>
                            <ul class="collapse show nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Item</span> 1 </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Item</span> 2 </a>
                                </li>
                            </ul>
                        </li> -->

                        <li class="nav-item @if ($page_title == 'Dashboard') nav-active @endif">
                            <a href="/" class="text-white nav-link align-middle px-0 @if ($page_title == 'Dashboard') nav-active @endif">
                                <i class='fa fa-dashboard'></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                            </a>
                        </li>

                        @can('class-view')
                        <li class="nav-item @if ($page_title == 'Classes') nav-active @endif">
                            <a href="{{ route('class.index')}}" class="text-white nav-link align-middle px-0 @if ($page_title == 'Classes') nav-active @endif">
                                <i class='fa fa-edit'></i> <span class="ms-1 d-none d-sm-inline">Class</span>
                            </a>
                        </li>
                        @endcan


                        @can('subjects-view')
                        <li class="nav-item @if ($page_title == 'Subjects') nav-active @endif">
                            <a href="{{ route('subjects.index')}}" class="text-white nav-link align-middle px-0 @if ($page_title == 'Subjects') nav-active @endif">
                                <i class='fa fa-pen'></i> <span class="ms-1 d-none d-sm-inline">Subjects</span>
                            </a>
                        </li>
                        @endcan


                        @can('students-view')
                        <li class="nav-item @if ($page_title == 'Students') nav-active @endif">
                            <a href="{{ route('students.index')}}" class="text-white nav-link align-middle px-0  @if ($page_title == 'Students') nav-active @endif">
                                <i class='fa fa-graduation-cap'></i> <span class="ms-1 d-none d-sm-inline">Students</span>
                            </a>
                        </li>
                        @endcan

                        @can('users-view')
                        <li class="nav-item @if ($page_title == 'Users') nav-active @endif">
                            <a href="{{ route('users.index')}}" class="text-white nav-link align-middle px-0  @if ($page_title == 'Users') nav-active @endif">
                                <i class='fa fa-user'></i> <span class="ms-1 d-none d-sm-inline">Users</span>
                            </a>
                        </li>
                        @endcan


                        @can('roles-view')
                        <li class="nav-item @if ($page_title == 'Roles') nav-active @endif">
                            <a href="{{ route('roles.index')}}" class="text-white nav-link align-middle px-0  @if ($page_title == 'Roles') nav-active @endif">
                                <i class='fa fa-user-plus'></i> <span class="ms-1 d-none d-sm-inline">Roles</span>
                            </a>
                        </li>
                        @endcan


                        @can('permissions-view')
                        <li class="nav-item @if ($page_title == 'Permissions') nav-active @endif">
                            <a href="{{ route('permissions.index')}}" class="text-white nav-link align-middle px-0  @if ($page_title == 'Permissions') nav-active @endif">
                                <i class='fa fa-shield'></i> <span class="ms-1 d-none d-sm-inline">Permissions</span>
                            </a>
                        </li>
                        @endcan


                    </ul>
                    <hr>
                    <div class="dropdown pb-4">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='fa fa-user p-2 bg-success rounded-circle me-2'></i>
                            <span class="d-none d-sm-inline mx-1">{{ $user->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                            <li class="text-center bg-secondary">
                                @if(count($user->getRoleNames()) > 0)
                                {{ $user->getRoleNames()[0] }}
                                @else
                                User
                                @endif
                            </li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row min-vh-100">
                    <div class="col-sm-11 mx-auto">
                        @yield('content')
                    </div>
                </div>
                <div class="row" style="background-color: #262626;">
                    <div class="col-12 text-center p-2">
                        <span class="text-white">Copyright © {{ date('Y') }} Addessa Corporation. All rights reserved.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])

    @yield('footer')


</body>

</html>