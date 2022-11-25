<?php

namespace App\Http\Controllers;

use App\Course;
use App\Department;
use App\DepartmentTakingRegisteredCourse;
use App\Exports\StudentResultExport;
use App\Exports\StudentResultNotOnTemplateResultExport;
use App\Http\Resources\CourseResultResource;
use App\Imports\StudentResultImport;
use App\RegisterCourse;
use App\Session;
use App\Student;
use App\StudentResult;
use App\StudentResultNotOnTemplate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\StudentInfoTemplateExport;

class CourseLecturerController extends Controller
{
    public function index(){
        return view('modules/courseLecturer');
    }

    public function registerCourse(Request $request) {
        $request->validate([
//            'sessionYear' => ['required','string','max:255'],
//            'semesterName' => ['required','string','max:255'],
            'courseId' => ['required','integer','max:255'],
            'selectedDepartments' => ['required']
        ]);

//        $sessionYear = $request->get('sessionYear');
//        $semesterName = $request->get('sessionName');
        DB::beginTransaction();

        try {
            $sessionInfo = Session::where('isActivated',true)->first();
        } catch (\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        $courseId = $request->get('courseId');

        try {
            $courseInfo = Course::where('id',$courseId)->first();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $courseName = $courseInfo->courseName;
        $courseCode = $courseInfo->courseCode;

        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;


        $departmentsTakingCourse = $request->get('selectedDepartments');
        $courseRegisterMatches = [
            'sessionYearInitial'=> $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'courseName' => $courseName,
            'courseCode' => $courseCode
        ];
        $getRegisteredCourse = RegisterCourse::where($courseRegisterMatches)->first();
        if(!$getRegisteredCourse) {

            try {
                $registerCourse = RegisterCourse::Create([
                    'lecturer_id' => \Auth::user()->id,
                    'sessionYearInitial' => $sessionYearInitial,
                    'sessionYearFinal' => $sessionYearFinal,
                    'semesterName' => $semesterName,
                    'courseName' => $courseName,
                    'courseCode' => $courseCode
                ]);
            } catch(\Exception $e)
            {
                DB::rollback();
                throw $e;
            }

//            CreateDepartmentTakingRegisteredCoursesTable::insert($departmentsTakingCourse);

            foreach ($departmentsTakingCourse as $departmentTakingCourse) {
                try {
                    DepartmentTakingRegisteredCourse::create([
//                        'register_course_id' => $departmentTakingCourse['registered_course_id'],
//                        'department_id' => $departmentTakingCourse['department_id']

                        'registered_course_id' => $registerCourse->id,
                        'department_id' => $departmentTakingCourse
                    ]);
                } catch(\Exception $e)
                {
                    DB::rollback();
                    throw $e;
                }
            }
            DB::commit();

//            if($registerCourse) {
//                return response([
//                    "message" => "Course registered successfully.",
//                ],Response::HTTP_OK);
//            } else {
//                return response([
//                    "message" => "Course could not be registered",
//                ],Response::HTTP_NOT_IMPLEMENTED);
//            }
            if(DB::transactionLevel() === 0 ) {
                return response([
                    "message" => "Course registered successfully.",
                ],Response::HTTP_OK);
            } else {
                return response([
                    "message" => "Course could not be registered",
                ],Response::HTTP_NOT_IMPLEMENTED);
            }
        } else {

            if($getRegisteredCourse->lecturer_id === \Auth::user()->id) {

                foreach ($departmentsTakingCourse as $departmentTakingCourse) {
                    try {
                            $ifAlreadyExist = DepartmentTakingRegisteredCourse::where([
                                'registered_course_id' => $getRegisteredCourse->id,
                                'department_id' => $departmentTakingCourse
                            ])->first();
                            if($ifAlreadyExist === null) {
                                DepartmentTakingRegisteredCourse::create([
                                    'registered_course_id' => $getRegisteredCourse->id,
                                    'department_id' => $departmentTakingCourse
                                ]);
                            }
                    } catch(\Exception $e)
                        {
                            DB::rollback();
                            throw $e;
                        }
                }
                DB::commit();
                if(DB::transactionLevel() === 0 ) {
                    return response([
                        "message" => "Course registered successfully.",
                    ],Response::HTTP_OK);
                } else {
                    return response([
                        "message" => "Course could not be registered",
                    ],Response::HTTP_NOT_IMPLEMENTED);
                }
            } else {
                return response([
                    "message" => "This course as already been registered by another lecturer.",
                    "getRegisteredCourse" => $getRegisteredCourse->id
                ],Response::HTTP_NOT_IMPLEMENTED);
            }

        }

    }

    public function removeRegisteredCourse(Request $request) {
        $request->validate([
            'registeredCourseId' => ['required','integer','max:255']
        ]);

        $registered_course_id = $request->get('registered_course_id');
        $checkIfCourseAsBeenActedOn = StudentResult::where('id',$registered_course_id)->get();
        if(!$checkIfCourseAsBeenActedOn) {
            DB::beginTransaction();

            try {
                RegisterCourse::where('id',$registered_course_id)->delete();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            try {
                DepartmentTakingRegisteredCourse::where('registered_course_id',$registered_course_id)->delete();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            DB::commit();

            return response([
                "message" => "Course has been deleted successfully"
            ], Response::HTTP_OK);
        }
    }

    public function getAllRegisteredCourses() {
        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        return RegisterCourse::where([
            'lecturer_id' => \Auth::user()->id,
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName
        ])->get();
    }

    public function downloadTemplate(Request $request) {
        $request->validate([
            'department' => ['required','integer'],
            'level' => ['required','string']
        ]);

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $department_id = $request->get('department');
        $level = $request->get('level');

        return Excel::download(new StudentInfoTemplateExport($sessionYearInitial,$sessionYearFinal,$semesterName,$department_id,$level), 'student-template.xlsx');
    }

    public function uploadResult(Request $request) {
        $request->validate([
            'file' => ['required'],
            'department_id' => ['required','integer'],
            'level' => ['required','string'],
            'registered_course_id' => ['required','integer']
        ]);

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $department_id = $request->get('department_id');
        $level = $request->get('level');
        $registered_course_id = $request->get('registered_course_id');

        $student_ids = Student::where([
            'sessionYearInitial'=>$sessionYearInitial,
            'sessionYearFinal'=>$sessionYearFinal,
            'semesterName'=>$semesterName,
            'department_id'=>$department_id,
            'level'=>$level
        ])->pluck('id');

        foreach ($student_ids as $student_id) {
            StudentResult::where([
                'registered_course_id' => $registered_course_id,
                'student_id' => $student_id
            ])->delete();
        }

        Excel::import(new StudentResultImport($semesterName,$sessionYearInitial,$sessionYearFinal,$department_id,$level, $registered_course_id), $request->file('file'));

        return response([
            "message" => "Result Uploaded Successfully"
        ], Response::HTTP_CREATED);
    }

    public function getDepartmentOfferingCourse(Request $request) {
        $request->validate([
            'course_id' => ['required','integer']
        ]);
        $courseId = $request->get('course_id');

        $departmentIds = DepartmentTakingRegisteredCourse::where('registered_course_id',$courseId)->pluck('department_id');
        $departmentArray = [];
        foreach ($departmentIds as $departmentId) {
            $departmentArray[] = Department::where('id',$departmentId)->first();
        }

        return response([
            "departments" => $departmentArray
        ], Response::HTTP_OK);
    }

    public function downloadDepartmentResultPerCourse (Request $request) {

        $request->validate([
            'course_id' => ['required','integer'],
            'department_id' => ['required','integer'],
            'level' => ['required','string'],
        ]);

        $course_id = $request->get('course_id');
        $level = $request->get('level');
        $department_id = $request->get('department_id');

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $thirdClause  = [
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $department_id,
            'level' => $level
        ];
        $studentResults = [];

        $noOfStudents = 0;
        $noOfFailures = 0;
        $noOfPasses = 0;
        $percentagePass = 0;
        $percentageFail = 0;

        $students = Student::where($thirdClause)->get();
            foreach ($students as $student) {
                $studentResults[] = [
                    'student' => $student,
                    'result' => StudentResult::where("student_id",$student->id)
                        ->where("registered_course_id",$course_id)
                        ->first()
                ];

            }

            //Todo: Optimize later.
            $courseInfo = RegisterCourse::where('id',$course_id)->first();

            $resultInfo = [
                'sessionYearInitial' => $sessionYearInitial,
                'sessionYearFinal' => $sessionYearFinal,
                'semesterName' => $semesterName,
                'departmentName' => Department::where('id',$department_id)->value('name'),
                'level' => $level,
                'courseName' => $courseInfo->courseName,
                'courseCode' => $courseInfo->courseCode
            ];

            // Calculating Result summary.
            $arrayOfResults = array_column($studentResults,'result');
            $arrayOfGrade = array_column($arrayOfResults,'grade');

            foreach ($arrayOfGrade as $grade) {
                if($grade === '-') {
                    // Do not count student
                } else if ($grade === 'F') {
                    $noOfStudents++;
                    $noOfFailures++;
                } else {
                    $noOfStudents++;
                    $noOfPasses++;
                }
            }

//            $noOfStudents = $noOfFailures + $noOfPasses;
            $percentageFail = ($noOfFailures/$noOfStudents)*100;
            $percentagePass = ($noOfPasses/$noOfStudents)*100;

            $percentageFail = number_format((float)$percentageFail,2,'.','');
            $percentagePass = number_format((float)$percentagePass,2,'.','');

            $analysis = [
                'noOfStudents' => $noOfStudents,
                'noOfFailures' => $noOfFailures,
                'noOfPasses' => $noOfPasses,
                'percentageFail' => $percentageFail,
                'percentagePass' => $percentagePass
            ];

            if($studentResults) {
                return Excel::download(new StudentResultExport($studentResults,$resultInfo,$analysis), 'department-course-result.xlsx');
            } else {
                return response([
                    "message" => "Result not found",
                ],Response::HTTP_NOT_FOUND);
            }

    }

    public function uploadStudentResultNotOnTemplate(Request $request) {

        $request->validate([
            'department_id' => ['required','integer'],
            'level' => ['required','string'],
            'registered_course_id' => ['required','integer'],
            'name' => ['required','string'],
            'matricNo' => ['required','string'],
            'att' => ['required','numeric'],
            'test' => ['required','numeric'],
            'exam' => ['required','numeric']
        ]);

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $department_id = $request->get('department_id');
        $level = $request->get('level');
        $registered_course_id = $request->get('registered_course_id');
        $name = $request->get('name');
        $matricNo = $request->get('matricNo');
        $att = $request->get('att');
        $test= $request->get('test');
        $exam = $request->get('exam');

        $total = $att + $test + $exam;

        $grade = StudentResultNotOnTemplate::generateGrade($total);

        $studentResult = new StudentResultNotOnTemplate;
        $studentResult->sessionYearInitial = $sessionYearInitial;
        $studentResult->sessionYearFinal = $sessionYearFinal;
        $studentResult->semesterName = $semesterName;
        $studentResult->department_id = $department_id;
        $studentResult->level = $level;
        $studentResult->registered_course_id = $registered_course_id;
        $studentResult->name = $name;
        $studentResult->matricNo = $matricNo;
        $studentResult->att = $att;
        $studentResult->test = $test;
        $studentResult->exam = $exam;
        $studentResult->total = $total;
        $studentResult->grade = $grade;

        if($studentResult->save()){
            return response([
                "message" => "Saved Successfully"
            ], Response::HTTP_OK);
        } else {
            return response([
                "message" => "Error occurred"
            ], Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    public function exportStudentResultNotOnTemplate(Request $request) {
        $request->validate([
            'registered_course_id' => ['required','integer'],
            'department_id' => ['required','integer'],
            'level' => ['required','string'],
        ]);

        $registered_course_id = $request->get('registered_course_id');
        $level = $request->get('level');
        $department_id = $request->get('department_id');

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $clause = [
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $department_id,
            'level' => $level,
            'registered_course_id' => $registered_course_id
        ];

        $studentResult = StudentResultNotOnTemplate::where($clause)->get();

        $courseInfo = RegisterCourse::where('id',$registered_course_id)->first();
        $resultInfo = [
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'departmentName' => Department::find($department_id)->value('name'),
            'level' => $level,
            'courseName' => $courseInfo->courseName,
            'courseCode' => $courseInfo->courseCode
        ];

        if($studentResult) {
            return Excel::download( new StudentResultNotOnTemplateResultExport($studentResult,$resultInfo), 'student-result-not-on-template.xlsx');
        } else {
            return response([
                "message" => "Result not found",
            ],Response::HTTP_NOT_FOUND);
        }
    }

}
