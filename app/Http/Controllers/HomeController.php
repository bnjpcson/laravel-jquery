<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\Students;
use App\Subjects;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $class = ClassModel::count();
        $subjects = Subjects::count();
        $students = Students::count();

        $data['class'] = $class;
        $data['subjects'] = $subjects;
        $data['students'] = $students;
        $data['page_title'] = "Dashboard";
        $data['user'] = Auth::user();




        return view('admin.components.dashboard', $data);
    }
}
