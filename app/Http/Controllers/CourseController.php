<?php

namespace App\Http\Controllers;

use App\course;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function createCourse(Request $request) {
        $request->validate([
            'name' => ['required','string','max:255'],
            'code' => ['required','string', 'max:255', 'unique:courses']
        ]);

        $course = Course::create([
            'name' => $request->get('name'),
            'code' => $request->get('code')
        ]);

        return response([
            "message" => "Course as been created successfully"
        ], Response::HTTP_CREATED);
    }

    public function getAllCourse() {
        return CourseResource::collection(Course::all());
    }

    public function getCourseById(Request $request) {
        $id = $request->route('id');
        return new CourseResource(Course::where('id',$id)->get());
    }
}
