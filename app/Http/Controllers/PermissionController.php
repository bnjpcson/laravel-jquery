<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['page_title'] = "Permissions";
        $data['user'] = Auth::user();

        return view('admin.components.permissions', $data);
    }

    public function getAllPermissions()
    {
        $permissions = Permission::all();

        $response['data'] = $permissions;

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

                    $class = Permission::create($data);

                    $superadmin = Role::where('name', 'superadmin')->first();

                    $superadmin->givePermissionTo(Permission::all());

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

    public function selectPermission(Request $request)
    {

        $id = $request->id;
        $permission = Permission::where('id', $id)->get();

        if (count($permission) > 0) {
            $data['id'] = $id;
            $data['permission'] = $permission;
            return response()->json($data, 200);
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function deletePermission(Request $request)
    {
        $id = $request->id;
        $permission = Permission::where('id', $id)->get();

        if (count($permission) > 0) {
            $permission = Permission::where('id', $id)->delete();
            if ($permission) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }
}
