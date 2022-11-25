<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ["sessionYearInitial","sessionYearFinal","semesterName","isActivated"];
}
