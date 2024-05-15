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
    showIt();
    openProfile();
  });

  function showIt(){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/getQuiz",
      data    : {},
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#tblQuizHTML').html('');
        $('#tblQuizHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblQuiz">'+
            '<thead>'+
              '<tr>'+
                '<th width="5%">No</th>'+
                '<th width="50%">Name Test</th>'+
                '<th width="10%">Total Quiz</th>'+
                '<th width="10%">Time (minutes)</th>'+        
                '<th width="7%">Status</th>'+           
                '<th width="8%">Action</th>'+   
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblQuiz').append(''+
          '<tr>'+
            '<td class="textRight">'+i+'</td>'+
            '<td>'+y.quiz+'</td>'+
            '<td class="textRight">'+y.jmlh_soal+'</td>'+
            '<td class="textRight">'+y.lama_waktu+'</td>'+
            '<td class="textCenter">'+y.ket_status+'</td>'+
            '<td class="textCenter">'+
              '<button type="button" class="btn btn-primary btn-sm" onclick="openIt('+y.id_user+','+y.id_category+');">OPEN</button>'+
            '</td>'+
          '</tr>'+
          '');
         
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblQuiz').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": false
        });
      },
      error : function(xhr){

      }
    });
  }

  function openProfile(){
    
    $('#modalProfile').modal('show');
  }
</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>Dashboard</h1>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div id="tblQuizHTML">
            <!-- javascript -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createForm" name="createForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="tmpLahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
            <input type="text" class="form-control form-control-sm" id="tmpLahir" required>
          </div>
          <div class="mb-3">
            <label for="tglLahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
            <input type="date" id="tglLahir" name="tglLahir" required>
          </div>
          <div class="mb-3">
            <label for="inputName" class="col-sm-2 col-form-label">Pendidikan</label>
            <input type="text" class="form-control form-control-sm" id="inputName" required>
          </div>
          <div class="mb-3">
            <label for="inputName" class="col-sm-2 col-form-label">Jabatan</label>
            <input type="text" class="form-control form-control-sm" id="inputName" required>
          </div>
          <div class="mb-3">
            <label for="inputName" class="col-sm-2 col-form-label">Masa Kerja</label>
            <input type="text" class="form-control form-control-sm" id="inputName" required>
          </div>
          <div class="mb-3">
            <label for="inputName" class="col-sm-2 col-form-label">Tujuan Tes</label>
            <input type="text" class="form-control form-control-sm" id="inputName" required>
          </div>
        <!-- </form> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save">Update</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>
@endsection