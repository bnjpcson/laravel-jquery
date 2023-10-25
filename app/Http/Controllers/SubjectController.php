<?php

namespace App\Http\Controllers;

use App\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class SubjectController extends Controller
{
    //
    public function index()
    {
        $subjects = Subjects::get();

        $data['subjects'] = $subjects;
        $data['page_title'] = 'Subjects';
        $data['user'] = Auth::user();


        return view('admin.components.subjects', $data);
    }

    public function getSubjects()
    {
        $subjects = Subjects::all();
        foreach ($subjects as $key => $value) {
            $value['date'] = date_format($value->created_at, "Y/m/d h:i:sa");
        }

        return response()->json(['status' => 200, 'msg' => 'Success', 'data' => $subjects]);
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                "subject_name" => "required",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $data = array(
                        'subject_name'  => $request->subject_name,
                    );

                    $subjects = Subjects::create($data);

                    if ($subjects) {
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
        $subjects = Subjects::where('id', $id)->get();

        if (count($subjects)) {
            $data['id'] = $id;
            $data['subjects'] = $subjects;
            return response()->json($data, 200);
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function update(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                "subject_name" => "required",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $id = $request->id;
                    $data = array(
                        'subject_name'  => $request->subject_name,
                    );

                    $subject = Subjects::where('id', $id)->update($data);

                    if ($subject) {
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
        $subject = Subjects::where('id', $id)->get();

        if (count($subject) > 0) {
            $subject = Subjects::where('id', $id)->delete();
            if ($subject) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            $id = $request->id;
            $ids = explode(",", $id);
            $delete = DB::table('subjects')->whereIn('id', $ids)->delete();

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
