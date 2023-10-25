<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\Classroom_Student;
use App\Students;
use App\Subject_List;
use App\Subjects;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class ClassController extends Controller
{

    // use AuthenticatesUsers;
    //
    public function index(Request $request)
    {


        // $users = DB::select('select * from users where active = ?', [1]);

        $data['page_title'] = 'Classes';
        $data['user'] = Auth::user();


        return view('admin.components.class', $data);
    }

    public function getClassRoom(Request $request)
    {
        // $users = DB::select('select * from users where active = ?', [1]);
        $classroom_id = $request->classroom_id;

        $class = ClassModel::where('id', $classroom_id)->get();
        $subject_list = Subject_List::where('classroom_id', $classroom_id)
            ->join('subjects', 'subjects.id', '=', 'subject_list.subject_id')
            ->get();

        $student_list = Classroom_Student::where('classroom_id', $classroom_id)
            ->join('students', 'students.id', '=', 'classroom_student.student_id')
            ->get();



        if (count($class) == 0) {
            Alert::error('ID not found')->autoclose(3000);
            return back();
        }

        $data['class'] = $class;
        $data['subject_list'] = $subject_list;
        $data['student_list'] = $student_list;


        $data['page_title'] = 'Classes';
        $data['user'] = Auth::user();

        return view('admin.components.classroom', $data);
    }

    public function getNotEnrolledStudents(Request $request)
    {
        // $students = Students::all();

        // $students_notenrolled = [];

        // foreach ($students as $key => $student) {
        //     $class = Classroom_Student::where('student_id', "=", $student->id)->get();

        //     if (count($class) == 0) {
        //         array_push($students_notenrolled, $student);
        //     }
        // }

        // return response()->json(['data' => $students_notenrolled]);

        $class_id = $request->classroom_id;

        $students = Students::all();

        $allowed_students = [];

        foreach ($students as $key => $student) {

            $classroom_id = "";
            $classrooms = Classroom_Student::where('student_id', '=', $student->id)->get();
            $user_subjects = [];

            foreach ($classrooms as $key => $classroom) {
                $classroom_id = $classroom->classroom_id;
                $subjectLists = Subject_List::where('classroom_id', '=', $classroom_id)->get();
                foreach ($subjectLists as $key => $subjectList) {
                    $subjects = Subjects::where('id', '=', $subjectList->subject_id)->get();

                    foreach ($subjects as $key => $subject) {
                        array_push($user_subjects, $subject->id);
                    }
                }
            }

            $notAllowed = true;

            foreach ($user_subjects as $key => $user_subject) {
                if ($classroom_id != "") {
                    $listOfSubjects = Subject_List::where('classroom_id', '=', $class_id)->get();
                    foreach ($listOfSubjects as $key => $listOfSubject) {
                        if ($listOfSubject->subject_id == $user_subject) {
                            $notAllowed = false;
                        }
                    }
                }
            }

            if ($notAllowed) {
                array_push($allowed_students, $student->id);
            }
        }


        $list = [];

        foreach ($allowed_students as $key => $allowed_student) {
            $student = Students::where('id', '=', $allowed_student)->get();
            array_push($list, $student);
        }

        return response()->json(['data' => $list]);
    }

    public function store(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), [
                "section" => "required",
                "teacher" => "required",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $data = array(
                        'section'  => $request->section,
                        'teacher'  => $request->teacher,
                    );

                    $class = ClassModel::create($data);


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

    public function getclassroomdata()
    {
        $class = ClassModel::orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'msg' => 'Success', 'data' => $class]);
    }


    public function selectClassroom(Request $request)
    {
        $id = $request->id;

        $class = ClassModel::where('id', $id)->get();

        if (count($class)) {
            $data['class'] = $class;
            return response()->json($data, 200);
        } else {
            return response()->json(['msg' => 'Bad request. Student ID is not found.'], 400);
        }
    }

    public function class_update(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                "section" => "required",
                "teacher" => "required",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $id = $request->id;
                    $data = array(
                        'section'  => $request->section,
                        'teacher'  => $request->teacher,
                    );

                    $class = ClassModel::where('id', $id)->update($data);

                    if ($class) {
                        return response()->json(['status' => 200, 'msg' => 'Updated Succesfully']);
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

    public function class_delete(Request $request)
    {
        $id = $request->id;
        $class = ClassModel::where('id', $id)->get();

        if (count($class) > 0) {
            $class = ClassModel::where('id', $id)->delete();
            if ($class) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function class_enroll(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), [
                "student_id" => "required",
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
            } else {
                try {
                    $data = array(
                        'classroom_id'  => $request->classroom_id,
                        'student_id'  => $request->student_id,
                    );

                    $class = Classroom_Student::create($data);

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

    public function getClassEnrolled(Request $request)
    {
        $classroom_id = $request->classroom_id;

        $class = Classroom_Student::where('classroom_id', $classroom_id)->join('students', 'students.id', '=', 'classroom_student.student_id')->select('classroom_student.id AS classroom_student_id', 'student_id', 'students.std_name')->get();


        if (count($class)) {
            $data['class'] = $class;
            $data['status'] = 200;

            return response()->json($data, 200);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Bad request. ID is not found.'], 200);
        }
    }

    public function selectClassroom_Student(Request $request)
    {
        $id = $request->id;

        $classroom_student = Classroom_Student::where('id', $id)->get();

        if (count($classroom_student)) {
            $data['classroom_student'] = $classroom_student;
            return response()->json($data, 200);
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function enroll_delete(Request $request)
    {
        $id = $request->id;
        $classroom = Classroom_Student::where('id', $id)->get();

        if (count($classroom) > 0) {
            $classroom = Classroom_Student::where('id', $id)->delete();
            if ($classroom) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function getAllowedSubjects(Request $request)
    {
        $subjects = Subjects::all();
        $classroom_id = $request->classroom_id;

        $allowedSubjects = [];

        foreach ($subjects as $key => $subject) {
            $subjectList = Subject_List::where('subject_id', "=", $subject->id)
                ->where('classroom_id', "=", $classroom_id)
                ->get();

            if (count($subjectList) == 0) {
                array_push($allowedSubjects, $subject);
            }
        }

        return response()->json(['data' => $allowedSubjects]);
    }


    public function subject_add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "subject_id" => "required",
            "description" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
        } else {
            $data = array(
                'subject_id'  => $request->subject_id,
                'description'  => $request->description,
                'classroom_id'  => $request->classroom_id,
            );

            $subjectList = Subject_List::create($data);


            if ($subjectList) {
                return response()->json(['status' => 200, 'msg' => 'Inserted Succesfully'], 200);
            } else {
                return response()->json(['status' => 500, 'msg' => 'Invalid query.'], 500);
            }
            // try {
            //     $data = array(
            //         'subject_id'  => $request->subject_id,
            //         'description'  => $request->description,
            //         'classroom_id'  => $request->classroom_id,
            //     );

            //     $subjectList = Subject_List::create($data);


            //     if ($subjectList) {
            //         return response()->json(['status' => 200, 'msg' => 'Inserted Succesfully'], 200);
            //     } else {
            //         return response()->json(['status' => 500, 'msg' => 'Invalid query.'], 500);
            //     }
            // } catch (\Exception $e) {
            //     abort(500, 'Something Went Wrong');
            // }
        }
        // try {
        //     $validate = Validator::make($request->all(), [
        //         "subject_id" => "required",
        //         "description" => "required",
        //     ]);

        //     if ($validate->fails()) {
        //         return response()->json(['status' => 400, 'error' => $validate->getMessageBag()]);
        //     } else {
        //         $data = array(
        //             'subject_id'  => $request->subject_id,
        //             'description'  => $request->description,
        //             'classroom_id'  => $request->classroom_id,
        //         );

        //         $subjectList = Subject_List::create($data);


        //         if ($subjectList) {
        //             return response()->json(['status' => 200, 'msg' => 'Inserted Succesfully'], 200);
        //         } else {
        //             return response()->json(['status' => 500, 'msg' => 'Invalid query.'], 500);
        //         }
        //         // try {
        //         //     $data = array(
        //         //         'subject_id'  => $request->subject_id,
        //         //         'description'  => $request->description,
        //         //         'classroom_id'  => $request->classroom_id,
        //         //     );

        //         //     $subjectList = Subject_List::create($data);


        //         //     if ($subjectList) {
        //         //         return response()->json(['status' => 200, 'msg' => 'Inserted Succesfully'], 200);
        //         //     } else {
        //         //         return response()->json(['status' => 500, 'msg' => 'Invalid query.'], 500);
        //         //     }
        //         // } catch (\Exception $e) {
        //         //     abort(500, 'Something Went Wrong');
        //         // }
        //     }
        // } catch (\Exception $e) {
        //     abort(500, 'Something Went Wrong');
        // }
    }

    public function getSelectedSubject(Request $request)
    {
        $classroom_id = $request->classroom_id;

        $subjectList = Subject_List::where('classroom_id', $classroom_id)
            ->join('subjects', 'subjects.id', '=', 'subject_list.subject_id')
            ->select('subject_list.id AS subject_list_id', 'subject_name', 'description', 'subject_id')
            ->get();


        if (count($subjectList)) {
            $data['subjectList'] = $subjectList;
            $data['status'] = 200;

            return response()->json($data, 200);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Bad request. ID is not found.'], 200);
        }
    }

    public function selectSubjectList(Request $request)
    {
        $id = $request->id;

        $subjectList = Subject_List::where('id', $id)->get();

        if (count($subjectList)) {
            $data['subjectList'] = $subjectList;
            return response()->json($data, 200);
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }

    public function subject_delete(Request $request)
    {
        $id = $request->id;
        $subjectList = Subject_List::where('id', $id)->get();

        if (count($subjectList) > 0) {
            $subjectList = Subject_List::where('id', $id)->delete();
            if ($subjectList) {
                return response()->json(['msg' => 'Success'], 200);
            } else {
                return response()->json(['msg' => 'Invalid Query'], 500);
            }
        } else {
            return response()->json(['msg' => 'Bad request. ID is not found.'], 400);
        }
    }
}
