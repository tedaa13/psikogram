@extends('layouts.master')
@section('title', 'Form')
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

  textarea{resize: none !important;}
</style>
<!-- end section CSS -->

<!-- section Javascript -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script>
  $(document).ready(function () {
    GetData("Tes Kategori");

    $('#updateForm').on('submit',function(e){
      e.preventDefault();
      Swal.fire({
        title: "Apakah Anda yakin akan menyimpan?",
        icon: 'warning',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        allowOutsideClick: false
      }).then(function(x) {
        if(x.value === true){
          var form_data = new FormData(document.getElementById("updateForm"));

          $.ajax({
            url         : "{{ url('/form') }}/updateData",
            method      : "POST",
            data        : form_data,
            contentType : false,
            cache       : false,
            processData : false,
            headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success     : function (data) {
              if(data){
                swal.fire("Info!",data, "info");
              }else{
                GetData(document.getElementById("e_titleEditInput").value);
                $('#modal_edit').modal('hide');
                swal.fire("Sukses!","Data Anda berhasil disimpan.","success");
              }
            }
          });
        }
      });
    });

    $('#saveForm').on('submit',function(e){
      e.preventDefault();
      Swal.fire({
        title: "Apakah Anda yakin akan menyimpan?",
        icon: 'warning',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        allowOutsideClick: false
      }).then(function(x) {
        if(x.value === true){
          var form_data = new FormData(document.getElementById("saveForm"));

          $.ajax({
            url         : "{{ url('/form') }}/saveData",
            method      : "POST",
            data        : form_data,
            contentType : false,
            cache       : false,
            processData : false,
            headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success     : function (data) {
              if(data){
                swal.fire("Info!",data, "info");
              }else{
                GetData("Tes Kategori");
                $('#modal_Add').modal('hide');
                swal.fire("Sukses!","Data Anda berhasil disimpan.","success");
              }
            }
          });
        }
      });
    });
  });

  function editIt($id, $tipe){
    $('#modal_edit').modal('show');
    $.ajax({
      url         : "{{ url('/form') }}/getDataDetail",
      method      : "POST",
      data        : {
        "id" : $id,
        "tipe"    : "Tes Kategori"
      },
      headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      success     : function (data) {
        document.getElementById("e_duration").value = data.duration;
        document.getElementById("e_description").value = data.desc_category;
        document.getElementById("e_instruction").value = data.instruction;
        document.getElementById("e_code_status").value = data.code_status;
        document.getElementById("titleEdit").innerHTML = "[EDIT] " +$tipe;
        document.getElementById("e_titleEditInput").value = $tipe;
        document.getElementById("e_id").value = $id;
      }
    });
  }

  function kembali(){
    var Tab1 = document.getElementById("0");
    var Tab2 = document.getElementById("1");
    var Tab3 = document.getElementById("2");
    Tab2.classList.remove("active");
    Tab3.classList.remove("active");
    Tab1.classList.add("active");
    Tab2.classList.add("disabled");
    Tab3.classList.add("disabled");

    var content1 = document.getElementById("page1");
    var content2 = document.getElementById("page2");
    var content3 = document.getElementById("page3");
    
    content2.classList.remove("active");
    content2.classList.add("fade");
    content3.classList.remove("active");
    content3.classList.add("fade");
    content1.classList.add("active");
  }

  function kembali1(){
    var Tab1 = document.getElementById("0");
    var Tab2 = document.getElementById("1");
    var Tab3 = document.getElementById("2");
    Tab3.classList.remove("active");
    Tab2.classList.add("active");
    Tab3.classList.add("disabled");

    var content1 = document.getElementById("page1");
    var content2 = document.getElementById("page2");
    var content3 = document.getElementById("page3");
    
    content3.classList.remove("active");
    content3.classList.add("fade");
    content2.classList.add("active");
  }

  function openQuiz($idCategory, $code){
    var Tab1 = document.getElementById("0");
    var Tab2 = document.getElementById("1");
    var Tab3 = document.getElementById("2");
    Tab1.classList.remove("active");
    Tab2.classList.remove("disabled");
    Tab1.classList.add("disabled");
    Tab2.classList.add("active");

    var content1 = document.getElementById("page1");
    var content2 = document.getElementById("page2");
    var content3 = document.getElementById("page3");
    content1.classList.remove("active");
    content2.classList.remove("fade");
    content2.classList.add("active");

    document.getElementById("JudulKontent1").innerHTML = $code;

    getDataQuiz($idCategory);
  }

  function openDetail($idCategory, $code, $id_quiz){
    var Tab1 = document.getElementById("0");
    var Tab2 = document.getElementById("1");
    var Tab3 = document.getElementById("2");
    Tab2.classList.remove("active");
    Tab3.classList.remove("disabled");
    Tab2.classList.add("disabled");
    Tab3.classList.add("active");

    var content1 = document.getElementById("page1");
    var content2 = document.getElementById("page2");
    var content3 = document.getElementById("page3");
    content2.classList.remove("active");
    content3.classList.remove("fade");
    content3.classList.add("active");

    document.getElementById("JudulKontent2").innerHTML = $code;
    getDataPilihan($idCategory, $id_quiz);
  }

  function GetData($tipe){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('form') }}/getData",
      data    : {
        "tipe" : $tipe,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $('#tblCategoryHTML').html('');
        $('#tblCategoryHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblCategory">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Code</th>'+
                '<th>Duration</th>'+        
                '<th>Description</th>'+
                '<th>Instruction</th>'+
                '<th>Status</th>'+
                '<th>Created At</th>'+             
                '<th>Updated At</th>'+   
                '<th>Action</th>'+
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblCategory').append(''+
          '<tr>'+
            '<td>'+i+'</td>'+
            '<td><span style="cursor:pointer; text-decoration:underline; color:blue;" onclick="openQuiz('+y.id_category+',\''+y.code+'\');">'+y.code+'</span></td>'+
            '<td>'+y.duration+'</td>'+
            '<td style="word-wrap: break-word; white-space:pre-line">'+y.description+'</td>'+
            '<td style="word-wrap: break-word; white-space:pre-line">'+y.instruction+'</td>'+
            '<td style="word-wrap: break-word; white-space:pre-line">'+y.desc_status+'</td>'+
            '<td>'+y.created_at+'</td>'+
            '<td>'+y.updated_at+'</td>'+
            '<td>'+
              '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="editIt('+y.id_category+',\''+$tipe+'\');"><i class="bi bi-pencil-square"></i></button>'+
            '</td>'+
          '</tr>'+
          '');
        
        });

        $('#tblCategory').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblCategory').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
      },
      error : function(xhr){

      }
    });
  }

  function getDataQuiz($idCategory){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('form') }}/getData",
      data    : {
        "tipe" : "Detail Quiz",
        "idCategory" : $idCategory
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $('#tblQuizHTML').html('');
        $('#tblQuizHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblDtlQuiz">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Tipe Soal</th>'+
                '<th>Pertanyaan</th>'+        
                '<th>Jawaban Benar</th>'+
                '<th>Kategori Soal</th>'+
                '<th>Tanggal Buat</th>'+
                '<th>Tanggal Edit</th>'+
                '<th>&nbsp;</th>'+
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblDtlQuiz').append(''+
          '<tr>'+
            '<td>'+i+'</td>'+
            '<td>'+y.desc_input+'</td>'+
            '<td style="word-wrap: break-word; white-space:pre-line"><span style="cursor:pointer; text-decoration:underline; color:blue;" onclick="openDetail('+y.id_category+',\''+y.code+'\',\''+y.id_quiz+'\');">Detail</span></td>'+
            '<td>'+y.correct_answer+'</td>'+
            '<td>'+y.Ket_Soal+'</td>'+
            '<td>'+y.created_at+'</td>'+
            '<td>'+y.updated_at+'</td>'+
            '<td>'+
              '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="editItQuiz('+y.id_category+',\''+y.code+'\',\''+y.id_quiz+'\');" disabled><i class="bi bi-pencil-square"></i></button>'+
            '</td>'+
          '</tr>'+
          '');
        
        });

        $('#tblDtlQuiz').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblDtlQuiz').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
      }
    });
  }

  function getDataPilihan($idCategory, $idQuiz){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('form') }}/getData",
      data    : {
        "tipe" : "PG Detail Quiz",
        "idCategory" : $idCategory,
        "idQuiz" : $idQuiz
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $('#tblQuizDtlHTML').html('');
        $('#tblQuizDtlHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblDtlQuiz2">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Tipe Soal</th>'+
                '<th>Pilihan Jawaban</th>'+
                '<th>Tanggal Buat</th>'+
                '<th>Tanggal Edit</th>'+
                '<th>&nbsp;</th>'+
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblDtlQuiz2').append(''+
          '<tr>'+
            '<td>'+i+'</td>'+
            '<td>'+y.desc_input+'</td>'+
            '<td>'+y.pil_desc+'</span></td>'+
            '<td>'+y.created_at+'</td>'+
            '<td>'+y.updated_at+'</td>'+
            '<td>'+
              '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="editItPG();"disabled><i class="bi bi-pencil-square"></i></button>'+
            '</td>'+
          '</tr>'+
          '');
        });

        $('#tblDtlQuiz2').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblDtlQuiz2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
      }
    });
  }
</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>Form</h1>
  <div class="card">
    <div class="card-body">
    <div class="card">
        <div class="card-header">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link active" id="0" data-bs-toggle="tab" href="#page1">Category</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" id="1" data-bs-toggle="tab" href="#page2">Soal Tes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" id="2" data-bs-toggle="tab" href="#page3">Pilihan Ganda</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane container active" id="page1">
              <div class="container">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_Add">
                  <i class="bi bi-plus"></i>
                </button>
                <hr/>
                <div class="row">
                  <div class="col-md-12">
                    <div id="tblCategoryHTML">
                      <!-- javascript -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane container fade" id="page2">
              <div class="container">
                <button type="button" class="btn btn-warning" onclick="kembali();">
                  <i class="bi bi-arrow-90deg-left"></i> 
                </button>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_Add1">
                  <i class="bi bi-plus"></i>
                </button>
                <h2><span id="JudulKontent1"></span></h2>
                <hr/>
                <div class="row">
                  <div class="col-md-12">
                    <div id="tblQuizHTML">
                      <!-- javascript -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane container fade" id="page3">
              <div class="container">
                <button type="button" class="btn btn-warning" onclick="kembali1();">
                  <i class="bi bi-arrow-90deg-left"></i>
                </button>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_Add2">
                  <i class="bi bi-plus"></i>
                </button>
                <h2><span id="JudulKontent2"></span></h2>
                <hr/>
                <div class="row">
                  <div class="col-md-12">
                    <div id="tblQuizDtlHTML">
                      <!-- javascript -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<div class="modal fade" id="modal_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><span id="titleEdit"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm" name="updateForm" enctype="multipart/form-data">
          <input type="text" value="" name="e_titleEditInput" id="e_titleEditInput" hidden/>
          <input type="text" value="" name="e_id" id="e_id" hidden/>
          <div class="row form-group">
            <label for="e_duration" class="col-sm-3 col-form-label">Duration</label>
            <div class="col-sm-2">
              <input type="number" class="form-control form-control-sm" id="e_duration" name="e_duration" value="" min-value="0" required>
            </div>
          </div>
          <div class="row form-group">
            <label for="e_description" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="e_description" name="e_description" value="" required>
            </div>
          </div>
          <div class="row form-group">
            <label for="e_instruction" class="col-sm-3 col-form-label">Instruction</label>
            <div class="col-sm-9">
              <textarea type="text" class="form-control form-control-sm" id="e_instruction" name="e_instruction" value="" rows="8" required></textarea>
            </div>
          </div>
          <hr/>
          <div class="row form-group">
            <label for="e_code_status" class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
              <select id="e_code_status" name="e_code_status" class="form-select form-select-sm">
                <option value="001">Aktif</option>
                <option value="002">Tidak Aktif</option>
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="update">Update</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_Add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">[TAMBAH] Tes Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="saveForm" name="saveForm" enctype="multipart/form-data">
          <div class="row form-group">
            <label for="a_code" class="col-sm-3 col-form-label">Code</label>
            <div class="col-sm-2">
              <input type="text" class="form-control form-control-sm" maxlength="3" id="a_code" name="a_code" value="" autocomplete="off" required>
            </div>
          </div>
          <div class="row form-group">
            <label for="a_duration" class="col-sm-3 col-form-label">Duration</label>
            <div class="col-sm-2">
              <input type="number" class="form-control form-control-sm" id="a_duration" name="a_duration" value="" min-value="0" autocomplete="off" required>
            </div>
          </div>
          <div class="row form-group">
            <label for="a_description" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="a_description" name="a_description" value="" autocomplete="off" required>
            </div>
          </div>
          <div class="row form-group">
            <label for="a_instruction" class="col-sm-3 col-form-label">Instruction</label>
            <div class="col-sm-9">
              <textarea type="text" class="form-control form-control-sm" id="a_instruction" name="a_instruction" value="" rows="8" autocomplete="off" required></textarea>
            </div>
          </div>
          <hr/>
          <div class="row form-group">
            <label for="a_code_status" class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
              <select id="a_code_status" name="a_code_status" class="form-select form-select-sm">
                <option value="001">Aktif</option>
                <option value="002">Tidak Aktif</option>
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save">Save</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>