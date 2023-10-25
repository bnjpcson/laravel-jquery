<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:user-view');
    }

    public function index()
    {
        $data['page_title'] = "Users";
        $data['user'] = Auth::user();

        return view('admin.components.users', $data);
    }

    public function getAllUsers()
    {
        $users = User::with('roles')->get();

        $response['data'] = $users;

        return response()->json($response);
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                "name" => "required",
                "email" => "required|email",
                "password" => "required",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {

                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]);

                    try {
                        $role = Role::findByName('user');
                        $user->assignRole($role);
                    } catch (\Exception $e) {
                    }


                    if ($user) {
                        return response()->json(['status' => 200, 'msg' => 'Inserted Succesfully'], 200);
                    } else {
                        return response()->json(['status' => 500, 'msg' => 'Invalid query.'], 500);
                    }
                } catch (\Exception $e) {
                    abort(500, 'Something Went Wrong');
                }
            }
        } catch (\Exception $e) {
            abort(500, 'Something Went Wrong');
        }
    }

    public function selectUser(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->get();

        if (count($user) > 0) {
            $data['user'] = $user;
            return response()->json($data, 200);
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }



    public function deleteUser(Request $request)
    {
        $id = $request->id;

        if ($id == 1) {
            abort(401, 'Unauthorized request!');
        }

        $user = User::where('id', $id)->get();

        if (count($user) > 0) {
            $user = User::where('id', $id)->delete();
            if ($user) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function editUser(Request $request)
    {
        try {
            $id = $request->id;

            $roles = Role::all()->pluck('name');
            $user = User::with('roles')->where('id', $id)->get()[0];
            $res['user'] = $user;

            $output = [];

            foreach ($roles as $key => $role) {
                $hasrole = false;
                if ($user->hasRole($role)) {
                    $hasrole = true;
                }
                $output[] = ['role' => $role, 'hasrole' => $hasrole];
            }

            $res["data"] = $output;

            return response()->json($res, 200);
        } catch (\Exception $e) {

            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function saveUser(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), [
                "id" => "required",
                "name" => "required",
                "email" => "required|email",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {

                    $id = $request->id;

                    if ($id == 1) {
                        abort(401, 'Unauthorized request!');
                    }

                    $user = User::where('id', $id)->get()[0];

                    $data = [
                        'name' => $request->name,
                        'email' => $request->email,
                    ];

                    $user->update($data);

                    $user->syncRoles($request->roles);

                    if ($user) {
                        return response()->json(['status' => 200, 'msg' => 'Inserted Succesfully']);
                    } else {
                        return response()->json(['status' => 500, 'msg' => 'Invalid query.'], 500);
                    }
                } catch (\Exception $e) {
                    abort(500, 'Something Went Wrong');
                }
            }
        } catch (\Exception $e) {
            abort(500, 'Something Went Wrong');
        }
    }
}
