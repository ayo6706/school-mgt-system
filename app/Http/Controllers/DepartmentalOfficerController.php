<?php

namespace App\Http\Controllers;

use App\Course;
use App\Department;
use App\DepartmentalLevelResult;
use App\Exports\StudentResultExport;
use App\Exports\StudentResultWithGpaExport;
use App\Imports\DepartmentalLevelResultImport;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseResultResource;
use App\RegisterCourse;
use App\DepartmentTakingRegisteredCourse;
use App\Session;
use App\Student;
use App\StudentResult;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\Response;
//use PDF;
use ZipArchive;
use File;

class DepartmentalOfficerController extends Controller
{
    public function index(){
        return view('modules/departmentalOfficer');
    }

    public function getStudentResultPerCourse (Request $request) {
        /*
         * To get student result from a department by the departmental officer
         * First you have to find out if the course was taken by the department of
         * the departmental officer.
         * If true, then output the result to the department.
         * *Steps
         * *Get the department of the departmental officer.
         * *Find out if his department was registered to take the course for the semester.
         * *Then output the results.
         * */

        // The departmental officer is the current session
        // So you can get his department via Auth::user()
        $departmentalOfficerDepartmentId = \Auth::user()->department_id;
        $courseCode = $request->get('courseCode');
        $sessionYearInitial = $request->get('sessionYearInitial');
        $sessionYearFinal = $request->get('sessionYearFinal');
        $semesterName = $request->get('semesterName');
        $clause = [
            'courseCode'=> $courseCode,
            'sessionYearInitial'=> $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName'=> $semesterName
        ];
        // Then check registered Courses for the session, year and semester and pluck the Id.
        $registeredCourseId = RegisterCourse::where($clause)->value('id');
        $secondClause = [
            'registered_course_id'=>$registeredCourseId,
            'department_id'=>$departmentalOfficerDepartmentId
        ];
        // Then use the registered courseId and the departmentalId in DepartmentTakingRegisteredCourse to see if the department exist.
        $ifTakingCourse = DepartmentTakingRegisteredCourse::where($secondClause)->first();
        $thirdClause  = [
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $departmentalOfficerDepartmentId
        ];
        if($ifTakingCourse) {
            $studentResult = CourseResultResource::collection(Student::where($thirdClause)->get(), $registeredCourseId);
            if($studentResult) {
                return response([
                    'studentResult' => $studentResult
                ], Response::HTTP_OK);
            } else {
                return response([
                    "message" => "Result not found",
                ],Response::HTTP_NOT_FOUND);
            }
        } else {
            return response([
                "message" => "Your department do not take this course",
            ],Response::HTTP_NOT_FOUND);
        }

    }

    public function downloadStudentResultPerCourse (Request $request) {
        /*
         * To get student result from a department by the departmental officer
         * First you have to find out if the course was taken by the department of
         * the departmental officer.
         * If true, then output the result to the department.
         * *Steps
         * *Get the department of the departmental officer.
         * *Find out if his department was registered to take the course for the semester.
         * *Then output the results.
         * */

        // The departmental officer is the current session
        // So you can get his department via Auth::user()
        $departmentalOfficerDepartmentId = \Auth::user()->department_id;
        $course_id = $request->get('course_id');
        $level = $request->get('level');

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

//        $clause = [
//            'course_id'=> $course_id,
//            'sessionYear'=> $sessionYear,
//            'semesterName'=> $semesterName
//        ];
        // Then check registered Courses for the session, year and semester and pluck the Id.
//        $registeredCourseId = RegisterCourse::where($clause)->value('id');

        $secondClause = [
            'registered_course_id'=>$course_id,
            'department_id'=>$departmentalOfficerDepartmentId
        ];
        // Then use the registered courseId and the departmentalId in DepartmentTakingRegisteredCourse to see if the department exist.
        $ifTakingCourse = DepartmentTakingRegisteredCourse::where($secondClause)->first();
        $thirdClause  = [
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $departmentalOfficerDepartmentId,
            'level' => $level
        ];
        if($ifTakingCourse) {
            $studentResult = CourseResultResource::collection(Student::where($thirdClause)->get(), $course_id);
            if($studentResult) {
//                return response([
//                    'studentResult' => $studentResult
//                ], Response::HTTP_OK);
                return Excel::download(new StudentResultExport($studentResult), 'department-course-result.xlsx');
            } else {
                return response([
                    "message" => "Result not found",
                ],Response::HTTP_NOT_FOUND);
            }
        } else {
            return response([
                "message" => "Your department do not take this course",
            ],Response::HTTP_NOT_FOUND);
        }

    }

    public function downloadDepartmentLevelResult(Request $request) {
        $request->validate([
            'level' => ['required','string'],
            'courseInfos' => ['required']
        ]);

        $departmentalOfficerDepartmentId = \Auth::user()->department_id;
        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $level = $request->get('level');
        $courseInfos = $request->get('courseInfos');
        $registered_course_ids = array_column($courseInfos,'course_id');
//        $registered_course_units = array_column($courseInfos, 'course_unit');


        $noOfStudents = 0;
        $noOfFailures = 0;
        $noOfPasses = 0;
        $percentagePass = 0;
        $percentageFail = 0;


        $students = Student::where([
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $departmentalOfficerDepartmentId,
            'level' => $level
        ])->get();

//        $tu = array_sum(array_column($courseInfos, 'course_unit'));
        $studentResults = [];
        $registeredCourseCodes = [];
        foreach ($courseInfos as $courseInfo) {
            $registeredCourseCodes[] = [
                "courseCode" => RegisterCourse::where('id',$courseInfo['course_id'])->value('courseCode'),
                "courseUnit" => $courseInfo['course_unit']
            ];
        }

        foreach ($students as $student) {
//            echo("Student Name: ".$student->name." #");
            $result = [];
            $summationOfGp = 0;
            $tu = 0;
            $gp = 0;
            $gpa = 0;
            foreach ($courseInfos as $courseInfo) {

                $studentResult = StudentResult::where('student_id',$student->id)
                    ->where("registered_course_id",$courseInfo['course_id'])->first();
                if($studentResult !== null) {
                    $result[] = $studentResult;

                    if($studentResult->grade !== '-') {
                        $gp = $this->getPoint($studentResult->grade) * $courseInfo['course_unit'];
                        $summationOfGp = $summationOfGp + $gp;
                        $tu = $tu + $courseInfo['course_unit'];
//                        echo("This first part worked");

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
                $gpa = $summationOfGp/$tu;
                $gpa = number_format((float)$gpa,2,'.','');
                $remark = $gpa < 1.50 ? 'FAIL' : 'PASS';
            }

            $studentResults[] = [
                'student' => $student,
                'grades' => $grades,
                'tp' => $summationOfGp,
                'tu' => $tu,
                'gpa' => $gpa,
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
            'departmentName' => Department::where("id",$departmentalOfficerDepartmentId)->value('name'),
            'level' => $level
        ];

//        $registeredCourseCode = RegisterCourse::find($registered_course_ids)->pluck('courseCode');

        if($studentResults) {
//            return response([
//                "studentResult" => $studentResults,
//                "resultInfo" => $resultInfo,
//                "tableHeader" => $registeredCourseCode
//            ]);
            return Excel::download(new StudentResultWithGpaExport($studentResults,$resultInfo,$registeredCourseCodes,$analysis), 'department-level-result.xlsx');
        } else {
            return response([
                "message" => "Result not found",
            ], Response::HTTP_NOT_FOUND);
        }
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

    public function getCoursesTakenByADepartment() {
        $departmentalOfficerDepartmentId = \Auth::user()->department_id;

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

    public function uploadDepartmentalLevelResult(Request $request) {
        $request->validate([
            'file' => ['required'],
            'level' => ['required','string']
        ]);

        $departmentalOfficerDepartmentId = \Auth::user()->department_id;

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $level = $request->get('level');

        DepartmentalLevelResult::where([
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $departmentalOfficerDepartmentId,
            'level' => $level
        ])->delete();

        Excel::import(new DepartmentalLevelResultImport($semesterName,$sessionYearInitial,$sessionYearFinal,$departmentalOfficerDepartmentId,$level), $request->file('file'));

        return response([
            'message' => "Result Uploaded Successfully"
        ], Response::HTTP_CREATED);
    }

    public function downloadStudentResultPdf(Request $request) {
        $request->validate([
            'level' => ['required','string'],
            'courseInfos' => ['required']
        ]);
        $departmentalOfficerDepartmentId = \Auth::user()->department_id;

        $level = $request->get('level');
        $courseInfos = $request->get('courseInfos');

        $sessionInfo = Session::where('isActivated',true)->first();
        $sessionYearInitial = $sessionInfo->sessionYearInitial;
        $sessionYearFinal = $sessionInfo->sessionYearFinal;
        $semesterName = $sessionInfo->semesterName;

        $registered_course_ids = array_column($courseInfos, 'course_id');

        $students = Student::where([
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'department_id' => $departmentalOfficerDepartmentId,
            'level' => $level
        ])->get();

        $departmentName = Department::where('id',$departmentalOfficerDepartmentId)->value('name');
        $resultInfo = [
            'sessionYearInitial' => $sessionYearInitial,
            'sessionYearFinal' => $sessionYearFinal,
            'semesterName' => $semesterName,
            'departmentName' => $departmentName,
            'level' => $level
        ];


        $fileName = 'result'.'_'.$departmentName.'_'.$sessionYearInitial.'_'.$sessionYearFinal.'_'.$semesterName;
        $path = storage_path($fileName);
        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }
//        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));



//        $studentResults = [];
        foreach ($students as $student) {
            $result = [];
            $studentResult = null;
            $results = null;
            $summationOfGp = 0;
            $tu = 0;
            foreach ($courseInfos as $courseInfo) {
                $studentResult = StudentResult::where('student_id',$student->id)
                    ->where("registered_course_id",$courseInfo['course_id'])->first();
                if($studentResult !== null) {
//                    $result[] = $studentResult;
                    // Get course name in result and add it to result array.
                    $results[] = [
                        'result' => $studentResult,
                        'courseInfo' => RegisterCourse::where('id',$courseInfo['course_id'])->first(),
                        'unit' => $courseInfo['course_unit']
                    ];

                    if($studentResult->grade !== '-') {
                        $gp = $this->getPoint($studentResult->grade) * $courseInfo['course_unit'];
                        $summationOfGp = $summationOfGp + $gp;
                        $tu = $tu + $courseInfo['course_unit'];
                    }
                }
            }
            $grades = array_column($results,'grade');
            $totalScore = array_column($results, 'total');
            if($tu === 0) {
//                $tu = 0; // Since computer can't divide by 0
                $tu = "-";
                $gpa = "-";
                $summationOfGp = "-";
                $remark = "-";
            } else {
                $gpa = $summationOfGp/$tu;
                $gpa = number_format((float)$gpa,2,'.','');
                $remark = $gpa < 1.50 ? 'FAIL' : 'PASS';
            }
            $studentCgpa = $this->generateCgpa($level,$sessionYearInitial,$sessionYearFinal,$departmentalOfficerDepartmentId,$student->matricNo);
            $studentResult = [
                'student' => $student,
                'results' => $results,
                'tp' => $summationOfGp,
                'tu' => $tu,
                'gpa' => $gpa,
                'ctu' => $studentCgpa['ctu'],
                'ctp' => $studentCgpa['ctp'],
                'cgpa' => $studentCgpa['cgpa'],
                'remark' => $remark
            ];
            if($studentResult) {
                $data = [
                    'studentResult' => $studentResult,
                    'resultInfo' => $resultInfo
                ];
                // Convert html to pdf.
                $pdf = \PDF::loadView('modules/exports/studentResultPdf', $data);
                // Save html to created file.
                $pdf->save($path.'/'.$student->name.'.pdf');
//                $this->generatePDF($data);
            }
        }


        // Zip file storage
        $zip_file_name = 'result'.'_'.$departmentName.'_'.$sessionYearInitial.'_'.$sessionYearFinal.'_'.$semesterName.'.zip';
//        $zip_file = 'studentResult.zip';
//        $zipPath = storage_path($zip_file_name);
        $zip = new ZipArchive;
        $zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $root_path = $path;

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root_path),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($root_path) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();


        // After result as been saved to 'result folder', the files
        // are then moved to zip file.
//        foreach ($files as $name => $file)
//        {
//            // We're skipping all subfolders
//            if (!$file->isDir()) {
//                $filePath     = $file->getRealPath();
//
//                // extracting filename with substr/strlen
//                $relativePath = 'studentResult/' . substr($filePath, strlen($path) + 1);
//
//                $zip->addFile($filePath, $relativePath);
//            }
//        }
//        $zip->close();

        if($students) {
            // Set Header
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );

            $filetopath=public_path($zip_file_name);
            // Create Download Response
            if(file_exists($filetopath)){
                $this->removeDirectory($path);
                return response()->download($filetopath,$zip_file_name,$headers);
//                unlink(public_path($zip_file_name));
            } else {
                return response([
                    "message" => "working",
                    "filename" => public_path($zip_file_name)
                ], Response::HTTP_OK);
            }
        } else {
            return response([
                "message" => "Result not found",
            ], Response::HTTP_NOT_FOUND);
        }
    }

    private function removeDirectory ($directoryPath) {
        $it = new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($directoryPath);
    }

//    private function generatePDF($data)
//    {
//        $pdf = PDF::loadView('modules/exports/studentResultPdf', $data);
//        $pdf->save(storage_path().'/studentResult/'.'studentResultPdf.pdf');
////        return $pdf->download('studentResultpdf.pdf');
//    }

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
                }
            }

            $level = $level - 100;
            $tempSessionYearInitial--;
            $tempSessionYearFinal--;
        }
        $resultObject = [
            "ctp" => $ctp,
            "ctu" => $ctu,
            "cgpa" => number_format((float)$cgpa,2,'.','')
        ];
        return $resultObject;
        // To get cgpa of student in 300l
        // get all when current session and level as reference array.
        // get all in 300l-100 and session-1
        // get all in 200l-100 and session-1
        // if 200l-100 is = 100 then end loop.
        // After getting the results in an array.
        // Then use the reference array to find student where matric number is equal, and then calculate cgpa.
    }

}
