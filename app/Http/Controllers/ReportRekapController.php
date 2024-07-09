<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ReportRekapController extends Controller
{
  public function index(Request $r){
    $q = "SELECT m.id_merchant, m.code, m.description
          FROM mst_merchant as m
          ORDER BY m.description";

    $data_merchant = DB::select($q);

    return view('report_rekap',compact('data_merchant'));
  }

  public function getData(Request $r){
    $q = "";
          
    $data = DB::select($q);
    return $data;
  }
}
