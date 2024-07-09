<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardUserController extends Controller
{
  public function index(Request $r){
    return view('dashboard_user');
  }

  public function getQuiz(Request $r){
    $this->id_user = auth()->user()->id_user;

    $q = "SELECT t.code as quiz
                  , h.id_user
                  , h.id_category
                  , COUNT(d.id_quiz) as jmlh_soal
                  , t.duration as lama_waktu
                  , s.description as ket_status
                  , h.code_status
          FROM trn_quiz_hdr as h
          INNER JOIN trn_quiz_dtl as d ON d.id_user = h.id_user AND d.id_category = h.id_category
          INNER JOIN mst_category_tes as t on t.id_category = h.id_category 
          INNER JOIN mst_status as s ON s.code_status = h.code_status
          WHERE h.id_user = '".$this->id_user."'
          GROUP BY t.code, t.duration, h.id_user, h.id_category, s.description, h.code_status
          ORDER BY h.id_category";

    $data =DB::select($q);
    return $data;
  }

  public function getContentQuiz(Request $r){
    $arrData = [];
    $q = "SELECT code as fl_code, description as fl_desc, duration as fl_waktu, instruction as fl_instruction
          FROM mst_category_tes
          WHERE id_category = '".$r->IdCategory."'";
    $data = collect(DB::select($q))->first();
    $arrData['master_category'] = $data;

    $arrData['master_soal'] = $this->GetJumlahSoal($r->IdCategory);

    return $arrData;
  }

  public function getContohSoal(Request $r){
    $arrData = [];
    $q = "SELECT h.id_quiz
                , h.no_quiz
                , h.id_type
                , REPLACE(h.question,'|',',') as question
                , d.id_quiz_dtl
                , REPLACE(d.description,'|',',') as description
                , CASE WHEN h.correct_answer = '' THEN '99999' ELSE h.correct_answer END as correct_answer
          FROM mst_quiz_hdr as h
          LEFT JOIN mst_quiz_dtl as d ON d.id_category = h.id_category AND d.id_quiz = h.id_quiz
          WHERE h.id_category = '".$r->IdCategory."' AND quiz_type='E' AND h.no_quiz = '".$r->noQuiz."'";
    $data =DB::select($q);

    return $data;
  }

  public function getSoal(Request $r){
    $arrData = [];
    $q = "SELECT h.id_quiz
                , h.no_quiz
                , h.id_type
                , REPLACE(h.question,'|',',') as question
                , d.id_quiz_dtl
                , REPLACE(d.description,'|',',') as description
                , h.correct_answer
                , tqd.answer
                , d.img_desc
          FROM mst_quiz_hdr as h
          INNER JOIN trn_quiz_dtl as tqd ON tqd.id_category = h.id_category AND tqd.id_quiz = h.id_quiz AND tqd.id_user = '".$r->idUser."'
          LEFT JOIN mst_quiz_dtl as d ON d.id_category = h.id_category AND d.id_quiz = h.id_quiz
          WHERE h.id_category = '".$r->IdCategory."' AND quiz_type='L' AND h.no_quiz = '".$r->noQuiz."'";
    $data =DB::select($q);

    $this->updateStats($r->idUser,$r->IdCategory);
    return $data;
  }

  function updateStats($idUser, $idCategory){
    date_default_timezone_set("Asia/Jakarta");
    DB::table('trn_quiz_hdr')
      ->where('id_user', $idUser)
      ->where('id_category', $idCategory)
      ->where('code_status','001')
    ->update([
      'start_date'  => Date("Y-m-d H:i:s"),
      'code_status' => '003',
      'updated_at'  => Date("Y-m-d H:i:s"),
      'updated_by'  => $idUser
    ]);

    DB::table('trn_quiz_hdr')
      ->where('id_user', $idUser)
      ->where('id_category', $idCategory)
    ->update([
      'updated_at'  => Date("Y-m-d H:i:s"),
      'updated_by'  => $idUser
    ]);
  }

  function GetJumlahSoal($idCategory){
    $q = "SELECT SUM(hmm.Example) as JMLH_E
              , SUM(hmm.Live) as JMLH_L
          FROM(
            SELECT CASE WHEN quiz_type = 'E' THEN 1 ELSE 0 END as 'Example',
                CASE WHEN quiz_type = 'L' THEN 1 ELSE 0 END as 'Live'
            FROM mst_quiz_hdr
            WHERE id_category = '".$idCategory."'
          )hmm";
    $data = collect(DB::select($q))->first();

    return $data;
  }

  public function getTableNumber(Request $r){
    $this->id_user = auth()->user()->id_user;
    $q = "SELECT d.id_quiz
                  , CASE WHEN d.answer IS NULL AND d.updated_at IS NULL THEN '0' ELSE '1' END as fl_jawab
                  , d.answer
                  , d.updated_at
          FROM trn_quiz_hdr as h
          LEFT JOIN trn_quiz_dtl as d ON d.id_category = h.id_category AND h.id_user = d.id_user
          WHERE h.id_user = '".$this->id_user."' AND h.id_category ='".$r->IdCategory."'";

    $data =DB::select($q);
    return $data;
  }

  public function SubmitAnswer(Request $r){
    date_default_timezone_set("Asia/Jakarta");
    try{
      DB::BeginTransaction();

      DB::table('trn_quiz_hdr')
        ->where('id_user', $r->idUser)
        ->where('id_category', $r->idCategory)
      ->update([
        'updated_at'  => Date("Y-m-d H:i:s"),
        'updated_by'  => $r->idUser
      ]);

      DB::table('trn_quiz_dtl')
        ->where('id_user', $r->idUser)
        ->where('id_category', $r->idCategory)
        ->where('id_quiz', $r->idQuiz)
      ->update([
        'updated_at'  => Date("Y-m-d H:i:s"),
        'answer'      => $r->answer
      ]);
      DB::Commit();

      return "1";
    }catch(\Exception $e){
      DB::rollback();     
      return "Something wrong! <br/> Please contact IT Admin. <br/> ERROR: <br/> [ '". substr($e,0,1000) ."' ]";
    }
  }

  public function cekLastSave(Request $r){
    $q = "SELECT MIN(id_quiz) as MIN_TERJAWAB, MAX(id_quiz) as JMLH_SOAL, MAX(h.code_status) as code_status
          FROM trn_quiz_hdr as h
          INNER JOIn trn_quiz_dtl as d ON d.id_category = h.id_category AND h.id_user = d.id_user
          WHERE h.id_category = '".$r->idCategory."' AND h.id_user = '".$r->idUser."' AND d.answer IS NULL AND d.updated_at IS NULL";
    $data = collect(DB::select($q))->first();
    return $data;
  }

  public function updateDone(Request $r){
    date_default_timezone_set("Asia/Jakarta");
    DB::table('trn_quiz_hdr')
      ->where('id_user', $r->idUser)
      ->where('id_category', $r->idCategory)
    ->update([
      'code_status' => '004',
      'finish_date' => Date("Y-m-d H:i:s"),
      'updated_at'  => Date("Y-m-d H:i:s"),
      'updated_by'  => $r->idUser
    ]);
    return "done";
  }

  public function cekWaktu(Request $r){		
    date_default_timezone_set("Asia/Jakarta");			
    $q = "SELECT h.start_date, h.updated_at, t.duration
          FROM trn_quiz_hdr as h
          INNER JOIN mst_category_tes as t ON t.id_category = h.id_category 
          WHERE h.id_user = '".$r->idUser."' AND h.id_category = '".$r->IdCategory."'";
    $data = collect(DB::select($q))->first();

    $jam_start  = strtotime(date('Y-m-d H:i:s'));
    $jam_update = strtotime($data->start_date);
                            
    $selisih  = $jam_start - $jam_update;
    $sisaDetik = $data->duration * 60;

    if($data->start_date){
      if($selisih > 0){
        $jam   = floor($selisih / (60 * 60));
        $menit = $selisih - ( $jam * (60 * 60) );
        $detik = $selisih % 60;

        $MenitTerpakai = ($jam * 60) + floor( $menit / 60 );

        if($data->duration > $MenitTerpakai){
          $sisaDetik = (($data->duration - $MenitTerpakai) * 60) - $detik;
        }else{
          $sisaDetik = 0;
        }
      }
    }

    // dd(date('Y-m-d H:i:s'),$data->start_date,$sisaDetik);
   
    return $sisaDetik;
  }
}
