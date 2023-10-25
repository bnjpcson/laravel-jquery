<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['page_title'] = "Roles";
        $data['user'] = Auth::user();

        return view('admin.components.roles', $data);
    }

    public function getAllRoles()
    {
        $roles = Role::with('permissions')->get();

        $response['data'] = $roles;

        return response()->json($response);
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                "name" => "required",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $data = array(
                        'name'  => $request->name,
                    );

                    $class = Role::create($data);

                    if ($class) {
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

    public function selectRole(Request $request)
    {

        $id = $request->id;
        $role = Role::with('permissions')->where('id', $id)->get();

        if (count($role) > 0) {
            $data['role'] = $role;
            return response()->json($data, 200);
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function editRole(Request $request)
    {
        try {
            $id = $request->id;

            $permissions = Permission::all()->pluck('name');
            $role = Role::with('permissions')->where('id', $id)->get()[0];
            $res['role'] = $role;

            $data = [];
            $output = [];

            foreach ($permissions as $key => $permission) {
                $permitted = false;
                if ($role->hasPermissionTo($permission)) {
                    $permitted = true;
                }
                $output[] = ['permission' => $permission, 'ispermitted' => $permitted];
            }

            $res["data"] = $output;




            return response()->json($res, 200);
        } catch (\Exception $e) {

            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function deleteRole(Request $request)
    {
        $id = $request->id;

        if ($id == 1) {
            abort(401, 'Unauthorized request!');
        }

        $role = Role::where('id', $id)->get();

        if (count($role) > 0) {
            $role = Role::where('id', $id)->delete();
            if ($role) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function saveRole(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), [
                "id" => "required",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $id = $request->id;

                    if ($id == 1) {
                        abort(401, 'Unauthorized request!');
                    }

                    $role = Role::where('id', $id)->get()[0];

                    $role->syncPermissions($request->permission);

                    if ($role) {
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
