<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Role;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/setup', function() {
    Role::create([
        'name' => 'courseLecturer'
    ]);
    Role::create([
        'name' => 'departmentalOfficer'
    ]);
    Role::create([
        'name' => 'collegeOfficer'
    ]);
    return "Setup completed";
});

Auth::routes();

Route::get('/home/', 'HomeController@index')->name('home');
Route::get('/get-activated-session', 'CollegeOfficerController@getActivatedSessionAndSemester');
Route::get('/department/all', 'CollegeOfficerController@getAllDepartments');
Route::post('/change-password', 'Auth\ChangePasswordController@changePassword');

Route::group(['prefix' => 'course-lecturer/', 'middleware' => ['isCourseLecturer']], function() {
   Route::get('/', 'CourseLecturerController@index')->name('courseLecturer');
   Route::get('/course/all', 'CollegeOfficerController@getAllCourses');
   Route::post('/register-course', 'CourseLecturerController@registerCourse');
   Route::get('/registered-course', 'CourseLecturerController@getAllRegisteredCourses');
   Route::get('/department/all', 'CollegeOfficerController@getAllDepartments');
   Route::post('/download-template', 'CourseLecturerController@downloadTemplate');

   Route::post('/upload-result', 'CourseLecturerController@uploadResult');
   Route::post('/search-department-offering-course', 'CourseLecturerController@getDepartmentOfferingCourse');
   Route::post('/download-result', 'CourseLecturerController@downloadDepartmentResultPerCourse');

   Route::post('/search-department-offering-course', 'CourseLecturerController@getDepartmentOfferingCourse');

   Route::post('/upload-result-not-on-template','CourseLecturerController@uploadStudentResultNotOnTemplate');
   Route::post('/download-result-not-on-template','CourseLecturerController@exportStudentResultNotOnTemplate');
});

Route::group(['prefix' => 'departmental-officer/', 'middleware' => ['isDepartmentOfficer']], function() {
    Route::get('/', 'DepartmentalOfficerController@index')->name('departmentalOfficer');
    Route::get('/get-department-courses','DepartmentalOfficerController@getCoursesTakenByADepartment');
    Route::post('/get-department-result','DepartmentalOfficerController@downloadDepartmentLevelResult');
    Route::post('/upload-department-level-result','DepartmentalOfficerController@uploadDepartmentalLevelResult');
    Route::post('/download-departmental-student-result-pdf','DepartmentalOfficerController@downloadStudentResultPdf');
});

Route::group(['prefix' => 'college-officer/', 'middleware' => ['isCollegeOfficer']], function() {
    Route::get('/', 'CollegeOfficerController@index')->name('collegeOfficer');
//    Route::post('/register-user', 'Auth\RegisterController@create');
    Route::post('/register-user', 'CollegeOfficerController@registerUser');
    Route::post('/create-course', 'CollegeOfficerController@createCourse');
    Route::get('/course/all', 'CollegeOfficerController@getAllCourses');
    Route::patch('/course/update', 'CollegeOfficerController@updateCourse');
    Route::post('/activate-session',  'CollegeOfficerController@createAndActivateSessionAndSemester');
    Route::get('/get-activated-session', 'CollegeOfficerController@getActivatedSessionAndSemester');

    Route::post('/create-department', 'CollegeOfficerController@createDepartment');
    Route::get('/department/all', 'CollegeOfficerController@getAllDepartments');
    Route::patch('/department/update', 'CollegeOfficerController@updateDepartment');

    Route::post('/upload-template', 'CollegeOfficerController@uploadTemplate');
    Route::post('/get-department-courses', 'CollegeOfficerController@getCoursesTakenByADepartment');

    Route::post('/get-department-result', 'CollegeOfficerController@getResultWithCgpa');

});
