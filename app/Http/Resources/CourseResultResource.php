<?php

namespace App\Http\Resources;

use App\StudentResult;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResultResource extends JsonResource
{

    private $registeredCourseId;

    /**
     * Create a new resource instance.
     *
     * @param  mixed $resource
     * @param $registeredCourseId
     */
    public function __construct($resource, $registeredCourseId)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;

        $this->registeredCourseId = $registeredCourseId;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "matricNo" => $this->matricNo,
            "sessionYear" => $this->sessionYear,
            "semesterName" => $this->semesterName,
            "college_id" => $this->college_id,
            "collegeName" => $this->collegeName,
            "department_id" => $this->department_id,
            "departmentName" => $this->departmentName,
            "level" => $this->level,
            "result" => StudentResult::where("student_id",$this->id)
                                        ->where("registered_course_id",15)
                                        ->first()
        ];
    }
}
