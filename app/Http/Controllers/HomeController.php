<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->hasRole('courseLecturer')) {
            return redirect('/course-lecturer');
        }

        if ($request->user()->hasRole('departmentalOfficer')){
            return redirect('/departmental-officer');
        }

        if ($request->user()->hasRole('collegeOfficer')){
            return redirect('/college-officer');
        }
//        return view('home');
    }
}
