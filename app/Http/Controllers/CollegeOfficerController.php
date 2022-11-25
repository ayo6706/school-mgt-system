<?php

namespace App\Http\Controllers;

use App\Course;
use App\Department;
use App\DepartmentalLevelResult;
use App\DepartmentTakingRegisteredCourse;
use App\Exports\StudentResultWithCgpaExport;
use App\Exports\StudentResultWithGpaExport;
use App\RegisterCourse;
use App\Session;
use App\Student;
use App\StudentResult;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Imports\StudentInfoTemplateImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CollegeOfficerController extends Controller
{
    public function index(){
        return view('modules/collegeOfficer');
    }

    public function uploadTemplate(Request $request){
//        Auth::user()->email;
        $request->validate([
            'file' => ['required'],
            'department_id' => ['required','integer'],
            'level' => ['required','string']
        ]);

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $department_id = $request->get('department_id');
        $level = $request->get('level');
        $departmentName = Department::where('id',$department_id)->value('name');

        $deleteIfExist = $sessionInfo = Student::where([
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $department_id,
            'departmentName' => $departmentName,
            'level' => $level
        ])->delete();
        Excel::import(new StudentInfoTemplateImport($semesterName,$sessionYearInitial,$sessionYearFinal,$department_id,$departmentName,$level), $request->file('file'));

        return response([
            "message" => "Template Uploaded Successfully"
        ], Response::HTTP_CREATED);

    }

    public function registerUser(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'roles' => ['required'],
            'department_id' => ['required','integer']
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->get("name"),
                'email' => $request->get("email"),
                'password' => bcrypt($request->get("password")),
                'department_id' => $request->get("department_id")
            ]);
        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        $roles = $request->get("roles");

        try {
            foreach ($roles as $role) {
                $user->roles()->attach(\App\Role::where('name',$role)->first());
            }
        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();

         /*Todo: After creating the user, send an email to the email address to
         change password. */

        if($user) {
            return response([
                "message" => "Staff has been Registered Successfully",
                "name" => $user->name,
                "email" => $user->email
            ], Response::HTTP_OK);
        } else {
            return response([
                "message" => "Error occured"
            ], Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    public function createCourse(Request $request) {
        $request->validate([
            'courseName' => ['required', 'string','max:255'],
            'courseCode' => ['required', 'string', 'max:60', 'unique:courses'],
//            'unit' => ['required', 'integer']
        ]);

        $course = Course::create([
            'courseName' => $request->get('courseName'),
            'courseCode' => $request->get('courseCode'),
//            'unit' => $request ->get('unit')
        ]);

        if($course) {
            return response([
                "message" => "Course has been Created Successfully",
                "courseName" => $course->courseName,
                "courseCode" => $course->code
            ], Response::HTTP_CREATED);
        } else {
            return response([
                "message" => "Course not Created",
            ],Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    public function getAllCourses() {
        return Course::all();
    }

    public function getCourseById(Request $request) {
        return Course::where('id',$request->get('id'))->first();
    }

    public function updateCourse(Request $request) {
        $updateCourse = Course::find($request->get('id'));

        $updateCourse->courseName = $request->get('courseName');
        $updateCourse->courseCode = $request->get('courseCode');

        if($updateCourse->save()) {
            return response([
                "message" => "Updated successfully",
            ],Response::HTTP_OK);
        } else {
            return response([
                "message" => "Error occurred",
            ],Response::HTTP_OK);
        }
    }

    public function createAndActivateSessionAndSemester(Request $request) {
        $request->validate([
            'sessionYearInitial' => ['required', 'integer'],
            'sessionYearFinal' => ['required','integer'],
            'semesterName' => ['required', 'max:60']
        ]);

        DB::beginTransaction();

        try {
            $deactivateAllSession = Session::where('isActivated', true)
                ->update([
                    'isActivated' => false
                ]);
        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        try {
            $session = Session::create([
                'sessionYearInitial' => $request->get('sessionYearInitial'),
                'sessionYearFinal' => $request->get('sessionYearFinal'),
                'semesterName' => $request->get('semesterName'),
                'isActivated' => true
            ]);
        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();


        if($session) {
            return response([
                "message" => "Session as been activated",
            ], Response::HTTP_CREATED);
        } else {
            return response([
                "message" => "Session not Created",
            ],Response::HTTP_NOT_IMPLEMENTED);
        }

    }

    public function getActivatedSessionAndSemester(Request $request) {
        return Session::where('isActivated', true)->first();
    }

    public function createDepartment(Request $request) {
        $request->validate([
            'name' => ['required', 'string','max:255'],
        ]);

        $department = Department::create([
            'name' => $request->get('name'),
        ]);

        if($department) {
            return response([
                "message" => "Department has been Created Successfully",
                "name" => $department->name,
            ], Response::HTTP_CREATED);
        } else {
            return response([
                "message" => "Department not Created",
            ],Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    public function getAllDepartments() {
        return Department::all();
    }

    public function getDepartmentById(Request $request) {
        return Department::where('id',$request->get('id'))->first();
    }

    public function updateDepartment(Request $request) {
        $department = Department::find($request->get('id'));

        $department->name = $request->get('name');

        if($department->save()) {
            return response([
                "message" => "Updated successfully",
            ],Response::HTTP_OK);
        } else {
            return response([
                "message" => "Error occurred",
            ],Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    public function getResultWithCgpa(Request $request) {
        $request->validate([
            'department_id' => ['required','integer'],
            'level' => ['required','string'],
            'courseInfos' => ['required']
        ]);

        $department_id = $request->get('department_id');
        $level = $request->get('level');
        $courseInfos = $request->get('courseInfos');

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $registered_course_ids = array_column($courseInfos,'course_id');


        $noOfStudents = 0;
        $noOfFailures = 0;
        $noOfPasses = 0;
        $percentagePass = 0;
        $percentageFail = 0;


        $students = Student::where([
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $department_id,
            'level' => $level
        ])->get();

        $registeredCourseCodes = [];
        foreach ($courseInfos as $courseInfo) {
            $registeredCourseCodes[] = [
                "courseCode" => RegisterCourse::where('id',$courseInfo['course_id'])->value('courseCode'),
                "courseUnit" => $courseInfo['course_unit']
            ];
        }

        $studentResults = [];
        foreach ($students as $student) {
            $result = [];
            $summationOfGp = 0.0;
            $tu = 0;
            foreach ($courseInfos as $courseInfo) {

                $studentResult = StudentResult::where('student_id',$student->id)
                    ->where("registered_course_id",$courseInfo['course_id'])->first();
                if($studentResult !== null) {
                    $result[] = $studentResult;

                    if($studentResult->grade !== '-') {
                        $gp = $this->getPoint($studentResult->grade) * $courseInfo['course_unit'];
                        $summationOfGp = $summationOfGp + $gp;
                        $tu = $tu + $courseInfo['course_unit'];
                    }
                }
            }

            $grades = array_column($result,'grade');

            if($tu === 0) {
//                $tu = 0; // Since computer can't divide by 0
                $tu = "-";
                $gpa = "-";
                $summationOfGp = "-";
                $remark = "-";
            } else {
                $gpa = $summationOfGp / $tu;
                $gpa = number_format((float)$gpa, 2, '.', '');
                $remark = $gpa < 1.50 ? 'FAIL' : 'PASS';
            }

            $studentCgpa = $this->generateCgpa($level,$sessionYearInitial,$sessionYearFinal,$department_id,$student->matricNo);
            $studentResults[] = [
                'student' => $student,
                'grades' => $grades,
                'tp' => $summationOfGp,
                'tu' => $tu,
                'gpa' => $gpa,
                'ctu' => $studentCgpa['ctu'],
                'ctp' => $studentCgpa['ctp'],
                'cgpa' => $studentCgpa['cgpa'],
                'remark' => $remark
            ];
        }

        // Calculating Result summary.
        $arrayOfRemarks = array_column($studentResults,'remark');

        foreach ($arrayOfRemarks as $grade) {
            if ($grade === 'FAIL') {
                $noOfStudents++;
                $noOfFailures++;
            } else if($grade === 'PASS') {
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



        $resultInfo = [
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'departmentName' => Department::where("id",$department_id)->value('name'),
            'level' => $level
        ];

//        $registeredCourseCode = RegisterCourse::find($registered_course_ids)->pluck('courseCode');

        if($studentResults) {
            return Excel::download(new StudentResultWithCgpaExport($studentResults,$resultInfo,$registeredCourseCodes,$analysis), 'department-level-result.xlsx');
        } else {
            return response([
                "message" => "Result not found",
            ], Response::HTTP_NOT_FOUND);
        }
    }


    private function generateCgpa($level,$sessionYearInitial,$sessionYearFinal,$department_id,$matricNo) {
        $ctp = 0;
        $ctu = 0;
        $cgpa = 0;
        $tempSessionYearInitial = $sessionYearInitial;
        $tempSessionYearFinal = $sessionYearFinal;

        while($level >= 100) {
            $semesterNameArray = array("rain", "harmattan", "second", "first");

            foreach ($semesterNameArray as $semesterName) {
//                echo "$semesterName <br>";

                $departmentLevelResult = DepartmentalLevelResult::where('department_id',$department_id)
                                            ->where('level',$level)
                                            ->where('semesterName',$semesterName)
                                            ->where('sessionYearInitial',$tempSessionYearInitial)
                                            ->where('sessionYearFinal',$tempSessionYearFinal)
                                            ->where('matricNo',$matricNo)
                                            ->first();

                if($departmentLevelResult) {
                    $tp = $departmentLevelResult->tp;
                    $tu = $departmentLevelResult->tu;
                    $ctp = $ctp + $tp;
                    $ctu = $ctu + $tu;
                    $cgpa = ($ctp + $tp)/($ctu + $tu);
                    $cgpa = number_format((float)$cgpa,2,'.','');
                }
            }

            $level = $level - 100;
            $tempSessionYearInitial--;
            $tempSessionYearFinal--;
        }
        $resultObject = [
            "ctp" => $ctp,
            "ctu" => $ctu,
            "cgpa" => $cgpa
        ];
        return $resultObject;
    }

    private function getPoint($grade) {
        $point = 0;
        if($grade === 'A') {
            $point = 5.0;
        } else if($grade === 'B') {
            $point = 4.0;
        } else if($grade === 'C') {
            $point = 3.0;
        } else if($grade === 'D') {
            $point = 2.0;
        } else if($grade === 'E') {
            $point = 1.0;
        } else if($grade === 'F') {
            $point = 0;
        } else if($grade === '-') {
            $point = 0;
        }
        return $point;
    }

    public function getCoursesTakenByADepartment(Request $request) {
        $departmentalOfficerDepartmentId = $request->get('department_id');

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $registeredCourseIds = RegisterCourse::where('sessionYearInitial',$sessionYearInitial)
            ->where('sessionYearFinal',$sessionYearFinal)
            ->where('semesterName',$semesterName)->pluck('id');

        $coursesTakenByDepartment = [];

        foreach ($registeredCourseIds as $registeredCourseId) {
            $result = DepartmentTakingRegisteredCourse::where('department_id', $departmentalOfficerDepartmentId)
                ->where('registered_course_id', $registeredCourseId)
                ->value('registered_course_id');
            if($result !== null) {
                $coursesTakenByDepartment[] = $result;
            }

        }

        $registeredCourses = RegisterCourse::find($coursesTakenByDepartment);

        return response([
            "courses" => $registeredCourses
        ], Response::HTTP_OK);
    }

}
