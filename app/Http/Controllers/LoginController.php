<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;

class LoginController extends Controller
{
  public function login()
  {
    if (Auth::check()) {
        return redirect('home');
    }else{
        return view('login');
    }
  }

  public function actionlogin(Request $request)
  {
    $tglsystem = Date("Y-m-d H:i:s");
    // $hashedPassword = Hash::make('123456');
    // dd($hashedPassword);
    // dd($request->password);
    $dataUser = DB::table('mst_user_login')
                    ->select('mst_user_login.end_date','mst_user_login.id_role','mst_user.id_user')
                    ->leftJoin('mst_user','mst_user.id_user','mst_user_login.id_user')
                    ->where('email','=',$request->input('email'))
                    ->first();
    $data = [
        'email' => $request->input('email'),
        'password' => $request->input('password'),
    ];

    if($tglsystem > $dataUser->end_date && $dataUser->id_role == '2'){
      Session::flash('error', 'Masa waktu login anda sudah habis.');
      return redirect('/');
    }else if (Auth::Attempt($data)) {
      if($dataUser->id_role == '2'){
        if($dataUser->id_user){
          return redirect('dashboard_user');
        }else{
          return redirect('profile');
        }
      }else{
        return redirect('home');
      }
    }else{
      Session::flash('error', 'Email atau Password Salah');
      return redirect('/');
    }
  }

  public function actionlogout()
  {
    Auth::logout();
    return redirect('/');
  }
}