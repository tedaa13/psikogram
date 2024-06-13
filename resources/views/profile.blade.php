@extends('layouts.master')
@section('title', 'Dashboard')
<!-- section CSS -->
<style>
  .boldFont{
    font-weight: bold;
  }

  .textCenter{
    text-align: center;
    vertical-align: middle;
  }

  .textLeft{
    text-align: left;
    vertical-align: middle;
  }

  .textRight{
    text-align: right;
    vertical-align: middle;
  }

  .navbar-collapse.collapse {
    display: block!important;
  }
</style>
<!-- end section CSS -->

<!-- section Javascript -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
  $(document).ready(function () {
    loadProfile();

    $('#simpan_data').on('submit',function(e){
      e.preventDefault();
      $.ajax({
        url         : "{{ url('/profile') }}/simpan_data",
        method      : "POST",
        data        : new FormData(this),
        contentType : false,
        cache       : false,
        processData : false,
        success     : function (data) {
          if(data){
            swal.fire("Info!",data, "info");
          }else{
            swal.fire("Sukses!","Data anda berhasil disimpan.","success");
          }
        }
      });
    });
  });

  function loadProfile(){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('profile') }}/loadProfile",
      data    : {},
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        console.log(result);
        document.getElementById("tmptLahir").value = result.tmpt_lahir;
        document.getElementById("tglLahir").value = result.tgl_lahir;
        document.getElementById("pendidikan").value = result.pendidikan;
        document.getElementById("masaKerja").value = result.masa_kerja;
        document.getElementById("jabatan").value = result.jabatan;
        document.getElementById("tujuanTes").value = result.tujuan_tes;
      },
      error : function(xhr){

      }
    });
  }
</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>Profile</h1>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <form id="simpan_data" class="simpan_data" method="POST" enctype="multipart/form-data">
            <div class="row form-group">
              <label for="nmLengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
              <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm" id="nmLengkap" name="nmLengkap" value="{{ $nama }}" disabled>
              </div>
            </div>
            <div class="row form-group">
              <label for="tmptLahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
              <div class="col-sm-2">
                <input type="text" class="form-control form-control-sm" id="tmptLahir" name="tmptLahir" required>
              </div>

              <label for="tglLahir" class="col-sm-2 col-form-label">Tgl. Lahir (mm/dd/yyyy)</label>
              <div class="col-sm-2">
                <div class="input-group date" id="datepicker">
                  <input type="date" id="tglLahir" name="tglLahir" class="datepicker-here form-control form-control-sm" data-min-view="days" data-view="days"  value="<?php echo date('m/d/Y'); ?>">
                </div>
              </div>
            </div>
            <div class="row form-group">
              <label for="pendidikan" class="col-sm-2 col-form-label">Pendidikan</label>
              <div class="col-sm-1">
                <select id="pendidikan" name="pendidikan" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                  <option value="">--</option>
                  <option value="SMA">SMA</option>
                  <option value="D3">D3</option>
                  <option value="S1">S1</option>
                  <option value="S2">S2</option>
                </select>
              </div>

              <label for="masaKerja" class="col-sm-2 col-form-label">Masa Kerja (Tahun)</label>
              <div class="col-sm-1">
                <select id="masaKerja" name="masaKerja" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                  <option value="">--</option>
                  <option value="0">0</option>
                  <option value="<1"> < 1</option>
                  <option value="1-3">1-3</option>
                  <option value="3-5">3-5</option>
                  <option value=">5">> 5</option>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
              <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm" id="jabatan" name="jabatan">
              </div>
            </div>
            <div class="row form-group">
              <label for="tujuanTes" class="col-sm-2 col-form-label">Tujuan Tes</label>
              <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm" id="tujuanTes" name="tujuanTes" required>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-8">
                <button type="submit" class="btn btn-primary float-end">SIMPAN</button>
              </div>
            </div>
            <!-- <div class="modal-footer modal-footer-uniform">
              <button type="submit" class="btn btn-md float-right" style="background-color: #54B435;"> SAVE </button>
            </div> -->
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection