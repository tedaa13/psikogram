<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Mail;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Str;
use App\Mail\SendMail;

class UserController extends Controller
{
  public function index(Request $r){
    $this->role_user = auth()->user()->id_role;
    
    if($this->role_user == '0'){
      $q = "SELECT m.id_merchant, m.code, m.description
            FROM mst_merchant as m 
            ORDER BY m.description ASC";
    }else{
      $q = "SELECT m.id_merchant, m.code, m.description
            FROM mst_merchant as m 
            WHERE m.id_merchant = '999'
            ORDER BY m.description ASC";
    }
    $data_merchant = DB::select($q);

    if($this->role_user == '0'){
      $q = "SELECT r.id_role, r.description
            FROM mst_role as r 
            ORDER BY r.id_role ASC";
    }else{
      $q = "SELECT r.id_role, r.description
            FROM mst_role as r 
            WHERE r.id_role = '2'
            ORDER BY r.id_role ASC";
    }
    $data_role = DB::select($q);


    return view('user',compact('data_merchant','data_role'));
  }

  public function addData(Request $r){
    date_default_timezone_set("Asia/Jakarta");
    $this->user_id = auth()->user()->id_user;
    $this->role = auth()->user()->id_role;

    $merchant = $r->merchant;
    if($this->role == '1'){
      $getMerchant= DB::table('mst_user_login')
                      ->where('id_role','=',$this->role)
                      ->first();
      $merchant = $getMerchant->id_merchant;
    }

    if($r->role == "" || $merchant == "" || $r->name == "" || $r->email == "" || $r->tglTes == ""){
      return "All columns are required";
    }

    $getID = DB::table('mst_user_login')
                ->orderby('id_user','desc')
                ->first();
    $idUser = $getID->id_user + 1;

    try{
      DB::BeginTransaction();

      $password = Str::password(6);
      $hashedPassword = Hash::make($password);

      DB::table('mst_user_login')->insert([
        'id_user'     => $idUser,
        'id_merchant' => $merchant,
        'username'    => $r->name,
        'email'       => $r->email,
        'password'    => $hashedPassword,
        'password2'   => $password,
        'id_role'     => $r->role,
        'code_status' => '001',
        'start_date'  => $r->tglTes . " 00:00:00",
        'end_date'    => $r->tglTes . " 23:59:59",
        'created_at'  => Date('Y-m-d H:i:s'),
        'created_by'  => $this->user_id
      ]);

      if($r->cbPilTes_0){
        DB::table('trn_quiz_hdr')->insert([
          'id_category' => 0,
          'id_user'     => $idUser,
          'start_date'  => null,
          'finish_date' => null,
          'code_status' => '001',
          'created_at'  => Date('Y-m-d H:i:s'),
          'created_by'  => $this->user_id,
          'updated_at'  => null,
          'updated_by'  => null
        ]);

        $q = "INSERT INTO trn_quiz_dtl
              SELECT '0', '".$idUser."', id_quiz, NULL, NULL 
              FROM mst_quiz_hdr
              WHERE id_category = '0' AND quiz_type = 'L'";
        DB::insert($q);
      }

      if($r->cbPilTes_1){
        DB::table('trn_quiz_hdr')->insert([
          'id_category' => 1,
          'id_user'     => $idUser,
          'start_date'  => null,
          'finish_date' => null,
          'code_status' => '001',
          'created_at'  => Date('Y-m-d H:i:s'),
          'created_by'  => $this->user_id,
          'updated_at'  => null,
          'updated_by'  => null
        ]);

        $q = "INSERT INTO trn_quiz_dtl
              SELECT '1', '".$idUser."', id_quiz, NULL, NULL 
              FROM mst_quiz_hdr
              WHERE id_category = '1' AND quiz_type = 'L';";
        DB::insert($q);
      }

      if($r->cbPilTes_2){
        DB::table('trn_quiz_hdr')->insert([
          'id_category' => 2,
          'id_user'     => $idUser,
          'start_date'  => null,
          'finish_date' => null,
          'code_status' => '001',
          'created_at'  => Date('Y-m-d H:i:s'),
          'created_by'  => $this->user_id,
          'updated_at'  => null,
          'updated_by'  => null
        ]);

        $q = "INSERT INTO trn_quiz_dtl
              SELECT '2', '".$idUser."', id_quiz, NULL, NULL 
              FROM mst_quiz_hdr
              WHERE id_category = '2' AND quiz_type = 'L';";
        DB::insert($q);
      }

      if($r->cbPilTes_3){
        DB::table('trn_quiz_hdr')->insert([
          'id_category' => 3,
          'id_user'     => $idUser,
          'start_date'  => null,
          'finish_date' => null,
          'code_status' => '001',
          'created_at'  => Date('Y-m-d H:i:s'),
          'created_by'  => $this->user_id,
          'updated_at'  => null,
          'updated_by'  => null
        ]);

        $q = "INSERT INTO trn_quiz_dtl
              SELECT '3', '".$idUser."', id_quiz, NULL, NULL 
              FROM mst_quiz_hdr
              WHERE id_category = '3' AND quiz_type = 'L';";
        DB::insert($q);
      }

      // $mailData = [
      //   'title' => 'Mail from ItSolutionStuff.com',
      //   'body' => 'This is for testing email using smtp.'
      // ];
      
      // Mail::to('andretedaa@gmail.com')->send(new SendMail($mailData));
        
      // dd("Email is sent successfully.");

      DB::Commit();

      return "";
    }catch(\Exception $e){
      DB::rollback();     
      return "Something wrong! <br/> Please contact IT Admin. <br/> ERROR: <br/> [ '". substr($e,0,1000) ."' ]";
    }
  }

  public function getData(Request $r){
    $this->id_merchant = auth()->user()->id_merchant;
    $this->id_role = auth()->user()->id_role;

    $q = "SELECT u.id_user as id
                  , u.username as name
                  , u.email
                  , r.description as des_role
                  , CASE WHEN m.description IS NULL THEN '-' ELSE m.description END as des_merchant
                  , CASE WHEN CONVERT(u.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(u.created_at,VARCHAR(45)) END as created_at
          FROM mst_user_login as u
          LEFT JOIN mst_merchant as m ON m.id_merchant = u.id_merchant
          INNER JOIN mst_role as r ON r.id_role = u.id_role
          WHERE (u.id_merchant = '".$this->id_merchant."' AND u.id_role <> '0') OR ('0' = '".$this->id_role."')";

    $data = DB::select($q);
    return $data;
  }
  
  public function getDataDetail(Request $r){
    $q = "SELECT u.id_user as id
                  , u.username as name
                  , u.email
                  , r.description as des_role
                  , CASE WHEN m.description IS NULL THEN '-' ELSE m.description END as des_merchant
                  , CASE WHEN CONVERT(u.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(u.created_at,VARCHAR(45)) END as created_at
                  , u.code_status as active
          FROM mst_user_login as u
          LEFT JOIN mst_merchant as m ON m.id_merchant = u.id_merchant
          INNER JOIN mst_role as r ON r.id_role = u.id_role
          INNER JOIN mst_status as s ON s.code_status = u.code_status
          WHERE u.id_user = '".$r->id_user ."'";

    $data = collect(DB::select($q))->first();
    return $data;
  }

  public function masterTes(Request $r){
    $q = "SELECT id_category
                  , code
                  , duration
                  , description
                  , created_at
                  , created_by
          FROM psikogram_ibm.mst_category_tes;";
    $data = DB::select($q);
    return $data;
  }
}
