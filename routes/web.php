<?php

use App\Http\Controllers\ClassController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


Route::group(["prefix" => "students", "middleware" => ["auth", "can:students-view"]], function () {
    Route::get('/', [StudentsController::class, 'index'])->name('students.index');
    Route::post('/add', [StudentsController::class, 'store'])->name('students.store');
    Route::get('/getStudents', [StudentsController::class, 'getStudents'])->name('students.getStudents');
    Route::get('/select/{id}', [StudentsController::class, 'select'])->name('students.select');
    Route::post('/edit/{id}', [StudentsController::class, 'update'])->name('students.update');
    Route::delete('/delete/{id}', [StudentsController::class, 'delete'])->name('students.delete');
    Route::delete('/deleteAll/{id}', [StudentsController::class, 'deleteAll'])->name('students.deleteAll');
});

Route::group(["prefix" => "subjects", "middleware" => ["auth", "can:subjects-view"]], function () {
    Route::get('/', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/add', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/getStudents', [SubjectController::class, 'getSubjects'])->name('subjects.getStudents');
    Route::get('/select/{id}', [SubjectController::class, 'select'])->name('subjects.select');
    Route::post('/edit/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/delete/{id}', [SubjectController::class, 'delete'])->name('subjects.delete');
    Route::delete('/deleteAll/{id}', [SubjectController::class, 'deleteAll'])->name('subjects.deleteAll');
});

Route::group(["prefix" => "class", "middleware" => ["auth", "can:class-view"]], function () {
    Route::get('/', [ClassController::class, 'index'])->name('class.index');
    Route::post('/', [ClassController::class, 'store'])->name('class.store');
    Route::post('/class-enroll', [ClassController::class, 'class_enroll'])->name('class.class_enroll');
    Route::post('/class-edit/{id}', [ClassController::class, 'class_update'])->name('class.class-update');
    Route::delete('/class-delete/{id}', [ClassController::class, 'class_delete'])->name('class.class_delete');
    Route::post('/subject-add', [ClassController::class, 'subject_add'])->name('class.subject_add');
    Route::delete('/enroll-delete/{id}', [ClassController::class, 'enroll_delete'])->name('class.enroll_delete');
    Route::delete('/subject-delete/{id}', [ClassController::class, 'subject_delete'])->name('class.subject_delete');
    Route::get('/{classroom_id}', [ClassController::class, 'getClassRoom'])->name('class.getClassRoom');
});


Route::group(["middleware" => "auth"], function () {
    Route::get('/selectClassroom/{id}', [ClassController::class, 'selectClassroom'])->name('class.selectClassroom');
    Route::get('/selectClassroom_Student/{id}', [ClassController::class, 'selectClassroom_Student'])->name('class.selectClassroom_Student');
    Route::get('/getclassroomdata', [ClassController::class, 'getclassroomdata'])->name('class.getclassroomdata');
    Route::get('/getNotEnrolledStudents/{classroom_id}', [ClassController::class, 'getNotEnrolledStudents'])->name('class.getNotEnrolledStudents');
    Route::get('/getAllowedSubjects/{classroom_id}', [ClassController::class, 'getAllowedSubjects'])->name('class.getAllowedSubjects');
    Route::get('/getClassEnrolled/{classroom_id}', [ClassController::class, 'getClassEnrolled'])->name('class.getClassEnrolled');
    Route::get('/getSelectedSubject/{classroom_id}', [ClassController::class, 'getSelectedSubject'])->name('class.getSelectedSubject');
    Route::get('/selectSubjectList/{id}', [ClassController::class, 'selectSubjectList'])->name('class.selectSubjectList');
});

Route::group(["prefix" => "users", "middleware" => ["auth", "can:users-view"]], function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/get-all-users', [UserController::class, 'getAllUsers'])->name('users.get-all-users');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/select-user/{id}', [UserController::class, 'selectUser'])->name('users.select-user');
    Route::get('/edit-user/{id}', [UserController::class, 'editUser'])->name('users.edit-user');
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser'])->name('users.delete-user');
    Route::post('/save-user/{id}', [UserController::class, 'saveUser'])->name('users.save-user');
});


Route::group(["prefix" => "roles", "middleware" => ["auth", 'can:roles-view']], function () {
    Route::get('/', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/get-all-roles', [RoleController::class, 'getAllRoles'])->name('roles.get-all-roles');
    Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/select-roles/{id}', [RoleController::class, 'selectRole'])->name('roles.select-role');
    Route::get('/edit-role/{id}', [RoleController::class, 'editRole'])->name('roles.edit-role');
    Route::post('/save-role/{id}', [RoleController::class, 'saveRole'])->name('roles.save-role');
    Route::delete('/delete-role/{id}', [RoleController::class, 'deleteRole'])->name('roles.delete-role');
});

Route::group(["prefix" => "permissions", "middleware" => ["auth", "can:permissions-view"]], function () {
    Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/get-all-permissions', [PermissionController::class, 'getAllPermissions'])->name('permissions.get-all-permissions');
    Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/select-permission/{id}', [PermissionController::class, 'selectPermission'])->name('permissions.select-permission');
    Route::delete('/delete-permission/{id}', [PermissionController::class, 'deletePermission'])->name('permissions.delete-permission');
});


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('logout', function () {
    if (Auth::logout() != null) {
        Auth::logout();
    } else {
        return redirect('/login');
    }
});

Route::get('/add', function () {

    $user = Role::where('name', 'user')->get()[0];


    $response['user'] = $user;


    return response()->json($response);
});

Route::get('/select-user', function (Request $request) {

    $user = $request->user();

    $response['output'] = $user;

    return response()->json($response);
})->middleware('auth');
