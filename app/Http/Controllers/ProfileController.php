<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProfileController extends Controller
{
  public function index(Request $r){
    $nama = auth()->user()->name;

    return view('profile',compact('nama'));
  }
}
