<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\report;

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
    public function index()
    {
        return view('home');
    }

    public function getCVSSarray(){
        $reports = report::all();
        $CVSSvaluesArray = array();
        foreach($reports as $report){
        $worstCVSS = app('\App\Http\Controllers\reportsController')->getWorstCVSS($report->id);
        array_push($CVSSvaluesArray, $worstCVSS);
        }
        return  $CVSSvaluesArray;

    }
}
