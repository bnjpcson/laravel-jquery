<?php

namespace App\Http\Controllers;

use App\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class StudentsController extends Controller
{
    //

    public function index(Request $request)
    {
        // $users = DB::select('select * from users where active = ?', [1]);
        $students = Students::get();

        $data['students'] = $students;
        $data['page_title'] = 'Students';
        $data['user'] = Auth::user();


        return view('admin.components.students', $data);
    }

    public function getStudents()
    {
        $students = Students::all();
        foreach ($students as $key => $value) {
            $value['date'] = date_format($value->created_at, "Y/m/d H:i:s");
        }

        return response()->json(['status' => 200, 'msg' => 'Success', 'data' => $students]);
    }

    public function store(Request $request)
    {
        try {

            $validFields = [
                "std_name" => "required",
                "std_address" => "required",
                "std_contactno" => "bail|required|numeric|digits:11"
            ];

            $validate = Validator::make($request->all(), $validFields);


            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $data = array(
                        'std_name'  => $request->std_name,
                        'std_address'  => $request->std_address,
                        'std_contactno'  => $request->std_contactno,
                    );

                    $students = Students::create($data);

                    if ($students) {
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

    public function select(Request $request)
    {
        $id = $request->id;
        $student = Students::where('id', $id)->get();

        if (count($student)) {
            $data['id'] = $id;
            $data['student'] = $student;
            return response()->json($data, 200);
        } else {
            return response()->json(['msg' => 'Bad request. Student ID is not found.'], 400);
        }
    }

    public function update(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                "std_name" => "required",
                "std_address" => "required",
                "std_contactno" => "bail|required|numeric|digits:11"
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $id = $request->id;
                    $student = Students::where('id', $id)->get();
                    $data = array(
                        'std_name'  => $request->std_name,
                        'std_address'  => $request->std_address,
                        'std_contactno'  => $request->std_contactno,
                    );

                    $student = Students::where('id', $id)->update($data);

                    if ($student) {
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


    public function delete(Request $request)
    {
        $id = $request->id;
        $student = Students::where('id', $id)->get();

        if (count($student) > 0) {
            $student = Students::where('id', $id)->delete();
            if ($student) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } else {
            return response()->json(['msg' => 'Bad request. Student ID is not found.'], 400);
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            $id = $request->id;
            $ids = explode(",", $id);
            $delete = DB::table('students')->whereIn('id', $ids)->delete();

            if ($delete) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } catch (\Exception $e) {
            abort(500, 'Something Went Wrong');
        }
    }
}
