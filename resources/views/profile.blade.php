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
    $(function(){
      $('#datepicker').datepicker({
       language: "en",
       autoclose: true,
       todayHighlight: true,
       dateFormat: 'mm/dd/yyyy',
       orientation: "bottom"
      });
    });

    $('#updateProfile').on('submit',function(e){
      e.preventDefault();
      window.swal.fire({
        title     : "Process ...",
        text      : "Please wait",
        imageUrl  : "public/assets/images/ajaxloader.gif",
        showConfirmButton : false,
        allowOutsideClick : false
      });

      $.ajax({
        url         : "{{ url('/rdmmdc') }}/claimdate/simpan_data",
        method      : "POST",
        data        : new FormData(this),
        contentType : false,
        cache       : false,
        processData : false,
        success     : function (data) {
          // console.log(data);
          if(data){
            swal.fire("Info!",data, "info");
          }else{
            $('#modalAdd').modal('hide');
            swal.fire("Success!","Your data is successfully saved.","success");
            searchIt();
            clearIt();
          }
        }
      });
    });

  });
</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>Profile</h1>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <form id="updateProfile" name="updateProfile" enctype="multipart/form-data">
            <div class="row form-group">
              <label for="nmLengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
              <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm" id="nmLengkap" value="{{ $nama }}" disabled>
              </div>
            </div>
            <div class="row form-group">
              <label for="tmptLahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
              <div class="col-sm-2">
                <input type="text" class="form-control form-control-sm" id="tmptLahir" required>
              </div>

              <label for="tglLahir" class="col-sm-2 col-form-label">Tgl. Lahir (mm/dd/yyyy)</label>
              <div class="col-sm-2">
                <div class="input-group date" id="datepicker">
                  <span class="input-group-append input-group-text"><i class="bi bi-calendar"></i></span>
                  <input type="text" id="tglLahir" class="datepicker-here form-control form-control-sm" data-min-view="days" data-view="days"  value="<?php echo date('m/d/Y'); ?>">
                </div>
              </div>
            </div>
            <div class="row form-group">
              <label for="pendidikan" class="col-sm-2 col-form-label">Pendidikan</label>
              <div class="col-sm-1">
                <select id="pendidikan" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                  <option value="">--</option>
                  <option value="SMA">SMA</option>
                  <option value="D3">D3</option>
                  <option value="S1">S1</option>
                  <option value="S2">S2</option>
                </select>
              </div>

              <label for="masaKerja" class="col-sm-2 col-form-label">Masa Kerja (Tahun)</label>
              <div class="col-sm-1">
                <select id="masaKerja" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
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
                <input type="text" class="form-control form-control-sm" id="jabatan">
              </div>
            </div>
            <div class="row form-group">
              <label for="tujuanTes" class="col-sm-2 col-form-label">Tujuan Tes</label>
              <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm" id="tujuanTes" required>
              </div>
            </div>
            <br/>
            <div class="row form-group ">
              <div class="col-sm-8">
                <button type="submit" class="btn btn-primary float-end" id="save">Simpan</button>
              </div>
            </div>
          {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection