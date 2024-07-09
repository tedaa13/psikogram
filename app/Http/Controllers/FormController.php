<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FormController extends Controller
{
  public function index(Request $r){
    return view('form');
  }

  public function getData(Request $r){
    if($r->tipe == "Tes Kategori"){
      $q = "SELECT c.id_category
                  , c.code
                  , c.duration
                  , c.description
                  , c.instruction
                  , s.description as desc_status
                  , CASE WHEN CONVERT(c.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(c.created_at,VARCHAR(45)) END as created_at
                  , CASE WHEN CONVERT(c.updated_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(c.updated_at,VARCHAR(45)) END as updated_at
                  , crt.username
                  , upd.username
                  , c.code_status
          FROM mst_category_tes as c
          INNER JOIN mst_user_login as crt ON crt.id_user = c.created_by
          INNER JOIN mst_status as s ON s.code_status = c.code_status
          LEFT JOIN mst_user_login as upd ON upd.id_user = c.updated_by";
    } 

    if($r->tipe == "Detail Quiz"){
      $q = "SELECT h.id_category
                    , mct.code
                    , h.id_quiz
                    , h.no_quiz
                    , h.id_type
                    , i.description as desc_input
                    , h.question
                    , h.correct_answer
                    , CASE WHEN h.quiz_type = 'L' THEN 'Live' ELSE 'Example' END as 'Ket_Soal'
                    , CASE WHEN CONVERT(h.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(h.created_at,VARCHAR(45)) END as created_at
                    , CASE WHEN CONVERT(h.updated_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(h.updated_at,VARCHAR(45)) END as updated_at
            FROM mst_quiz_hdr as h
            INNER JOIN mst_category_tes as mct ON mct.id_category = h.id_category
            INNER JOIN mst_tipe_input as i ON i.id_tipe = h.id_type
            WHERE h.id_category = '".$r->idCategory."'
            ORDER BY id_quiz";
    }

    if($r->tipe == "PG Detail Quiz"){
      $q = "SELECT h.id_category
                , c.code
                    , h.id_quiz
                    , d.id_quiz_dtl
                    , h.id_type
                    , d.description as pil_desc
                    , i.description as desc_input
                    , CASE WHEN h.quiz_type = 'L' THEN 'Live' ELSE 'Example' END as 'Ket_Soal'
                , CASE WHEN CONVERT(h.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(h.created_at,VARCHAR(45)) END as created_at
                , CASE WHEN CONVERT(h.updated_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(h.updated_at,VARCHAR(45)) END as updated_at
            FROM mst_quiz_hdr as h
            INNER JOIN mst_category_tes as c ON c.id_category = h.id_category
            INNER JOIN mst_tipe_input as i ON i.id_tipe = h.id_type
            INNER JOIN mst_quiz_dtl as d ON d.id_category = h.id_category AND h.id_quiz = d.id_quiz
            WHERE h.id_category = '".$r->idCategory."' AND h.id_quiz = '".$r->idQuiz."'
            ORDER BY d.id_quiz_dtl ASC;";
    }

    $data = DB::select($q);
    return $data;
  }

  public function getDataDetail(Request $r){


    if($r->tipe == "Tes Kategori"){
      $q = "SELECT c.id_category
                  , c.code
                  , c.duration 
                  , c.description as desc_category
                  , c.instruction
                  , s.description as desc_status
                  , CASE WHEN CONVERT(c.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(c.created_at,VARCHAR(45)) END as created_at
                  , CASE WHEN CONVERT(c.updated_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(c.updated_at,VARCHAR(45)) END as updated_at
                  , crt.username
                  , upd.username
                  , c.code_status
          FROM mst_category_tes as c
          INNER JOIN mst_user_login as crt ON crt.id_user = c.created_by
          INNER JOIN mst_status as s ON s.code_status = c.code_status
          LEFT JOIN mst_user_login as upd ON upd.id_user = c.updated_by
          WHERE c.id_category = '".$r->id."'";
    } 
    

    $data = collect(DB::select($q))->first();
    return $data;
  }

  public function updateData(Request $r){
    date_default_timezone_set("Asia/Jakarta");
    $this->user_id = auth()->user()->id_user;

    if($r->e_titleEditInput == "Tes Kategori"){
      DB::table('mst_category_tes')
      ->where('id_category', $r->e_id)
      ->update([
        'duration'       => $r->e_duration,
        'description'    => $r->e_description,
        'instruction'    => $r->e_instruction,
        'code_status'    => $r->e_code_status,
        'updated_by'     => $this->user_id,
        'updated_at'     => Date("Y-m-d H:i:s")
      ]);
    } 
    
    return "";
  }

  public function saveData(Request $r){
    date_default_timezone_set("Asia/Jakarta");
    $this->user_id = auth()->user()->id_user;

    $idCategory = 1;
    $getID = DB::table('mst_category_tes')
                ->orderby('id_category','desc')
                ->first();
    if($getID){
      $idCategory = $getID->id_category + 1;
    }

    DB::table('mst_category_tes')->insert([
      'id_category' => $idCategory,
      'code'        => $r->a_code,
      'duration'    => $r->a_duration,
      'description' => $r->a_description,
      'instruction' => $r->a_instruction,
      'code_status' => '001',
      'created_at'  => Date('Y-m-d H:i:s'),
      'created_by'  => $this->user_id
    ]);

    return "";

  }
}
