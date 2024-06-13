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

  public function loadProfile(Request $r){
    $this->user_id = auth()->user()->id;
    $q = "SELECT mu.tmpt_lahir
                  , MID(mu.tgl_lahir,1,10) as tgl_lahir
                  , mu.pendidikan
                  , mu.jabatan
                  , mu.masa_kerja
                  , mu.tujuan_tes
          FROM users as u
          INNER JOIN mst_user as mu ON mu.id_user = u.id
          where u.id = '".$this->user_id."'";
    $data = collect(DB::select($q))->first();
    return $data;
  }

  public function simpan_data(Request $r){
    date_default_timezone_set("Asia/Jakarta");
    $this->user_id = auth()->user()->id;
    $getUser = DB::table('mst_user')
                ->where('id_user',$this->user_id)
                ->first();
    
    if($getUser){
      DB::table('mst_user')
      ->where('id_user', $this->user_id)
      ->update([
        'tmpt_lahir'    => $r->tmptLahir,
        'tgl_lahir'     => $r->tglLahir,
        'umur'          => null,
        'tgl_tes'       => null,
        'pendidikan'    => $r->pendidikan,
        'jabatan'       => $r->jabatan,
        'masa_kerja'    => $r->masaKerja,
        'tujuan_tes'    => $r->tujuanTes,
        'start_date'    => null,
        'end_date'      => null,
        'updated_at'      => Date("Y-m-d H:i:s")
      ]);
    }else{
      DB::table('mst_user')->insert([
        'id_user'       => $this->user_id,
        'tmpt_lahir'    => $r->tmptLahir,
        'tgl_lahir'     => $r->tglLahir,
        'umur'          => null,
        'tgl_tes'       => null,
        'pendidikan'    => $r->pendidikan,
        'jabatan'       => $r->jabatan,
        'masa_kerja'    => $r->masaKerja,
        'tujuan_tes'    => $r->tujuanTes,
        'start_date'    => null,
        'end_date'      => null,
        'created_at'    => Date('Y-m-d H:i:s'),
        'updated_at'    => null
      ]);
    }
  }
}
