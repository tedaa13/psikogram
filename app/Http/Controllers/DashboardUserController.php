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
    $this->id_user = auth()->user()->id;

    $q = "SELECT t.code as quiz
                  , h.id_user
                  , h.id_category
                  , COUNT(d.id_quiz) as jmlh_soal
                  , t.minutes as lama_waktu
                  , s.keterangan as ket_status
          FROM trn_quiz_hdr as h
          INNER JOIN trn_quiz_dtl as d ON d.id_user = h.id_user AND d.id_category = h.id_category
          INNER JOIN mst_category_test as t on t.id_category = h.id_category 
          INNER JOIN mst_status as s ON s.kd_status = h.status
          WHERE h.id_user = '".$this->id_user."'
          GROUP BY t.code, t.minutes, h.id_user, h.id_category, s.keterangan
          ORDER BY h.id_category";

    $data =DB::select($q);
    return $data;
  }
}
