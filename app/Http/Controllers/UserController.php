<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
  public function index(Request $r){
    $this->role_user = auth()->user()->role;
    
    if($this->role_user == 'SA'){
      $q = "SELECT m.code, m.description
            FROM mst_merchant as m 
            ORDER BY m.description ASC";
    }else{
      $q = "SELECT m.code, m.description
            FROM mst_merchant as m 
            WHERE m.code = '999'
            ORDER BY m.description ASC";
    }
    $data_merchant = DB::select($q);

    if($this->role_user == 'SA'){
      $q = "SELECT r.id_role, r.name_role
            FROM mst_role as r 
            ORDER BY r.name_role ASC";
    }else{
      $q = "SELECT r.id_role, r.name_role
            FROM mst_role as r 
            WHERE r.id_role = 'GST'
            ORDER BY r.name_role ASC";
    }
    $data_role = DB::select($q);


    return view('user',compact('data_merchant','data_role'));
  }

  public function addData(Request $r){
    $this->user_id = auth()->user()->id;
    $this->role = auth()->user()->role;

    $merchant = $r->merchant;
    if($this->role == 'ADM'){
      $getMerchant= DB::table('users')
                      ->where('role','=',$this->role)
                      ->first();
      $merchant = $getMerchant->merchant;
    }

    if($r->role == "" || $merchant == "" || $r->name == "" || $r->email == ""){
      return "All columns are required";
    }

    $getID = DB::table('users')
                ->orderby('id','desc')
                ->first();

    try{
      DB::BeginTransaction();

      $password = Str::password(6);
      $hashedPassword = Hash::make($password);

      DB::table('users')->insert([
        'id'          => $getID->id + 1,
        'merchant'    => $merchant,
        'name'        => $r->name,
        'email'       => $r->email,
        'password'    => $hashedPassword,
        'password2'   => $password,
        'role'        => $r->role,
        'active'      => '001',
        'created_at'  => Date('Y-m-d H:i:s'),
        'created_by'  => $this->user_id
      ]);

      DB::Commit();

      return "";
    }catch(\Exception $e){
      DB::rollback();     
      return "Something wrong! <br/> Please contact IT Admin. <br/> ERROR: <br/> [ '". substr($e,0,1000) ."' ]";
    }
  }

  public function getData(Request $r){
    $q = "SELECT u.id
                  , u.name
                  , u.email
                  , r.name_role as des_role
                  , CASE WHEN m.description IS NULL THEN '-' ELSE m.description END as des_merchant
                  , CASE WHEN CONVERT(u.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(u.created_at,VARCHAR(45)) END as created_at
          FROM users as u
          LEFT JOIN mst_merchant as m ON m.code = u.merchant
          INNER JOIN mst_role as r ON r.id_role = u.role";

    $data = DB::select($q);
    return $data;
  }
  
  public function getDataDetail(Request $r){
    $q = "SELECT u.id
                  , u.name
                  , u.email
                  , r.name_role as des_role
                  , CASE WHEN m.description IS NULL THEN '-' ELSE m.description END as des_merchant
                  , CASE WHEN CONVERT(u.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(u.created_at,VARCHAR(45)) END as created_at
                  , u.active
          FROM users as u
          LEFT JOIN mst_merchant as m ON m.code = u.merchant
          INNER JOIN mst_role as r ON r.id_role = u.role
          WHERE u.id = '".$r->id_user ."'";

    $data = collect(DB::select($q))->first();
    return $data;
  }
}
