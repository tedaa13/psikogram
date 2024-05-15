<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PsikogramController extends Controller
{
  private $id_user;

  public function index(Request $r){

    $this->id_user = auth()->user()->id;

    $q = "SELECT h.id_user, u.name
          FROM trn_quiz_hdr as h 
          INNER JOIN users as u ON u.id = h.id_user
          GROUP BY h.id_user, u.name";

    $data_user = DB::select($q);

    return view('psikogram',compact('data_user'));
  }

  public function getProfileUser(Request $r){
    $q = "SELECT mu.id_user
                  , mu.tmpt_lahir
                  , DATE_FORMAT(mu.tgl_lahir, '%d-%m-%Y') as tgl_lahir
                  , mu.umur
                  , DATE_FORMAT(mu.tgl_tes, '%d-%m-%Y') as tgl_tes
                  , mu.pendidikan
                  , mu.jabatan
                  , mu.masa_kerja
                  , mu.tujuan_tes
                  , u.name
          FROM mst_user as mu
          INNER JOIN users as u ON u.id = mu.id_user
          WHERE mu.id_user = '".$r->id_user."'";
    $data = DB::select($q);

    return $data;
  }

  public function getDataUser(Request $r){
    $q = "SELECT tqh.*, mct.code
          FROM trn_quiz_hdr as tqh
          INNER JOIN mst_category_test as mct ON mct.id_category = tqh.id_category 
          WHERE tqh.id_user = '".$r->id_user."' AND tqh.status = '001'";
    $data = DB::select($q);

    return $data;
  }

  public function getDataTest(Request $r){
    if($r->id_category == 0){
      $q = "SELECT tqd.id_user
                    , tqd.id_category
                    , tqd.id_quiz
                    , mq.question
                    , mq.correct_answer
                    , CASE WHEN mqd.id_quiz IS NULL THEN mq.correct_answer ELSE mqd.description END as ket_correct_answer
                    , CASE WHEN tqd.answer IS NULL THEN '-' ELSE tqd.answer END as answer
                    , CASE WHEN tqd.answer IS NULL THEN '-' WHEN mqd.id_quiz IS NULL THEN tqd.answer ELSE mqd.description END as ket_answer
            FROM trn_quiz_dtl as tqd         
            INNER JOIN mst_quiz as mq ON mq.id_category = tqd.id_category AND mq.id_quiz = tqd.id_quiz
            LEFT JOIN mst_quiz_dtl as mqd ON mqd.id_category = mq.id_category AND mqd.id_quiz = mq.id_quiz
            WHERE tqd.id_user = '".$r->id_user."' AND tqd.id_category = '".$r->id_category."' AND (mq.correct_answer = mqd.id_quiz_dtl OR mqd.id_quiz_dtl IS NULL)
            ORDER BY tqd.id_user
                  , tqd.id_category
                  , tqd.id_quiz";
    }else if($r->id_category == 1){
      $q = "SELECT tqd.id_user
                  , tqd.id_category
                  , tqd.id_quiz
                  , mqd.id_quiz_dtl
                  , tqd.answer
                  , CASE WHEN mqd.id_quiz IS NULL THEN mq.correct_answer ELSE mqd.description END as ket_correct_answer
                  , CASE WHEN tqd.answer IS NULL THEN '-' ELSE tqd.answer END as answer
                  , CASE WHEN tqd.answer IS NULL THEN '-' WHEN mqd.id_quiz IS NULL THEN tqd.answer ELSE mqd.description END as ket_answer
            FROM trn_quiz_dtl as tqd         
            INNER JOIN mst_quiz as mq ON mq.id_category = tqd.id_category AND mq.id_quiz = tqd.id_quiz
            LEFT JOIN mst_quiz_dtl as mqd ON mqd.id_category = mq.id_category AND mqd.id_quiz = mq.id_quiz
            WHERE tqd.id_user = '".$r->id_user."' AND tqd.id_category = '".$r->id_category."' AND ((tqd.answer IS NULL) OR (tqd.answer IS NOT NULL))
            ORDER BY tqd.id_user
                  , tqd.id_category
                  , tqd.id_quiz";
    }else{
      $q = "SELECT d.id_quiz
                , qd.description
                    , qd.most
                    , qd.least
                    , d.answer
                    , CASE WHEN qd.id_quiz_dtl = LEFT(d.answer,1) THEN '⚫' ELSE '' END as MOST_ANSWER
                    , CASE WHEN qd.id_quiz_dtl = RIGHT(d.answer,1) THEN '⚫' ELSE '' END as LEAST_ANSWER
            FROM trn_quiz_hdr as h
            INNER JOIN trn_quiz_dtl as d ON h.id_category = d.id_category AND h.id_user = d.id_user
            INNER JOIN mst_quiz_dtl as qd ON qd.id_category = h.id_category AND qd.id_quiz = d.id_quiz
            WHERE h.id_user = '".$r->id_user."' AND h.id_category = '".$r->id_category."' 
            ORDER BY d.id_quiz ASC;";
    }

    $data = DB::select($q);

    return $data;
  }

  public function getDataResult(Request $r){
    $q = "SELECT JML_TERJAWAB
                  , JML_TDK_TERJAWAB
                  , JML_JAWAB_BNR
                  , JML_JAWAB_SLH
                  , mw.IQ as HSL_IQ
                  , (SELECT description FROM mtx_wpt1 WHERE min_IQ >= mw.IQ ORDER BY min_IQ ASC LIMIT 1) as KET_IQ
          FROM(
            SELECT SUM(TERJAWAB) as JML_TERJAWAB
                    , SUM(TDK_TERJAWAB) as JML_TDK_TERJAWAB
                    , SUM(JAWAB_BNR) as JML_JAWAB_BNR
                    , SUM(JAWAB_SLH) as JML_JAWAB_SLH
            FROM(
              SELECT CASE WHEN tqd.answer IS NULL THEN 0 ELSE 1 END as TERJAWAB
                  , CASE WHEN tqd.answer IS NULL THEN 1 ELSE 0 END as TDK_TERJAWAB
                  , CASE WHEN tqd.answer = mq.correct_answer THEN 1 ELSE 0 END as JAWAB_BNR
                  , CASE 
                    WHEN tqd.answer IS NULL THEN 0 
                    WHEN tqd.answer = mq.correct_answer THEN 0
                    ELSE 1 END as JAWAB_SLH
              FROM trn_quiz_dtl as tqd         
              INNER JOIN mst_quiz as mq ON mq.id_category = tqd.id_category AND mq.id_quiz = tqd.id_quiz
              LEFT JOIN mst_quiz_dtl as mqd ON mqd.id_category = mq.id_category AND mqd.id_quiz = mq.id_quiz
              WHERE tqd.id_user = '".$r->id_user."' AND tqd.id_category = '".$r->id_category."' AND (mq.correct_answer = mqd.id_quiz_dtl OR mqd.id_quiz_dtl IS NULL)
            )hmm
          )poin INNER JOIN mtx_wpt as mw ON mw.RS = poin.JML_JAWAB_BNR";
    $data = DB::select($q);

    return $data;
  }

  public function getDataResultPAPI(Request $r){
    $q = "SELECT CASE WHEN d.answer = '1' THEN p.poin_A ELSE p.poin_B END as Hasil_1
                  , COUNT(*) as POIN
                  , p1.desc_parameter
                  , p1.grouping
          FROM trn_quiz_hdr as h
          INNER JOIN trn_quiz_dtl as d ON d.id_user = h.id_user AND d.id_category	= h.id_category
          INNER JOIN mtx_papi as p ON p.id_quiz = d.id_quiz
          INNER JOIN mtx_papi1 as p1 ON p1.code_parameter = CASE WHEN d.answer = '1' THEN p.poin_A ELSE p.poin_B END
          WHERE h.id_user = '".$r->id_user."' AND h.id_category = '".$r->id_category."'
          GROUP BY CASE WHEN d.answer = '1' THEN p.poin_A ELSE p.poin_B END
                  , p1.desc_parameter
                  , p1.grouping
          ORDER BY grouping ASC";
    $data = DB::select($q);
    return $data;
  }

  public function getDataResultDISC(Request $r){
    $q = 'call GetSkorDISC('.$r->id_user.')';
    $data = DB::select($q);
    
    return $data;
  }

  public function getDataReport(Request $r){
    $q = 'call GetDataReportUser('.$r->id_user.')';
    $data = DB::select($q);
    
    return $data;
  }

  public function getDataChart(Request $r){
    $q = 'call GetSkorDISC('.$r->id_user.')';
    $data = DB::select($q);

    $arrdata = [];
    $smallData_M = [];
    $smallData_L = [];
    $smallData_C = [];
    $x=0;
    foreach ($data as $item) {
      $smallData_M[$x] = $item->skor_M;
      $smallData_L[$x] = $item->skor_L;
      $smallData_C[$x] = $item->skor_C;

      $x=$x+1;
    }

    $arrdata[0] = [
      "name" => "Graph 1",
      "data" => $smallData_M,
    ];

    $arrdata[1] = [
      "name" => "Graph 2",
      "data" => $smallData_L,
    ];

    $arrdata[2] = [
      "name" => "Graph 3",
      "data" => $smallData_C,
    ];

    return json_encode($arrdata);
  }
}
