<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FormController extends Controller
{
  public function index(Request $r){
    return view('form');
  }
}
