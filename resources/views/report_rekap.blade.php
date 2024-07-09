@extends('layouts.master')
@section('title', 'Report')
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

  .mystyle{
    background-color: #49494A;
    color:white;
  }

</style>
<!-- end section CSS -->
<!-- Latest compiled and minified CSS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>

<script>
  $(document).ready(function () {
    // $(function () {
      // $("select").select2();
    // });
  });

  $("select").select2();

  function getData(sel){
    $id_merchant = $('#datalistOptions [value="' + sel.value + '"]').data('customvalue');
    getDataWPT($id_merchant);
  }

  function getDataWPT($id_merchant){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('reportRekap') }}/getData",
      data    : {
        "id_merchant"	: $id_merchant,
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
          if(y.id_type == "0"){
            $('#tblDtlQuiz').append(''+
            '<tr>'+
              '<td>'+i+'</td>'+
              '<td>'+y.desc_input+'</td>'+
              '<td style="word-wrap: break-word; white-space:pre-line"><span style="cursor:pointer; text-decoration:underline; color:blue;" onclick="openDetail('+y.id_category+',\''+y.id_quiz+'\');">'+y.question+'</span></td>'+
              '<td>'+y.correct_answer+'</td>'+
              '<td>'+y.Ket_Soal+'</td>'+
              '<td>'+y.created_at+'</td>'+
              '<td>'+y.updated_at+'</td>'+
              '<td>'+
                '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="editIt();"><i class="bi bi-pencil-square"></i></button>'+
              '</td>'+
            '</tr>'+
            '');
          }else{
            $('#tblDtlQuiz').append(''+
            '<tr>'+
              '<td>'+i+'</td>'+
              '<td>'+y.desc_input+'</td>'+
              '<td style="word-wrap: break-word; white-space:pre-line">'+y.question+'</td>'+
              '<td>'+y.correct_answer+'</td>'+
              '<td>'+y.Ket_Soal+'</td>'+
              '<td>'+y.created_at+'</td>'+
              '<td>'+y.updated_at+'</td>'+
              '<td>'+
                '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="editIt();"><i class="bi bi-pencil-square"></i></button>'+
              '</td>'+
            '</tr>'+
            '');
          }
        
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

</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>Report Rekap</h1>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-2">
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <label for="searchList" class="form-label">Choose Merchant</label>
          <select name="siteID" id="siteID" class="abcd" style="width:100%">
  <option value='0' selected='true'> Not associated to any Circuit ID </option>
  <option value='2101' > 1007345136 </option> 
  <option value='2102' > 1007921321 </option> 
  <option value='2103' > 1007987235 </option> 
  <option value='2407' > 132 </option> 
  <option value='2408' > 141 </option> 
  <option value='2409' > 142 </option> 
  <option value='2410' > 145 </option> 
  <option value='2701' > 225 </option> 
  <option value='2702' > 248 </option> 
  <option value='2703' > 251 </option> 
  <option value='2704' > 254 </option> 
  <option value='2705' > 264 </option> 
  <option value='1804' > 27 </option> 
  <option value='2706' > 274 </option> 
  <option value='2707' > 310 </option> 
  <option value='2708' > 311 </option> 
  <option value='3001' > 333 </option> 
  <option value='2401' > 38 </option> 
  <option value='2402' > 64 </option> 
  <option value='2403' > 68 </option> 
  <option value='2404' > 69 </option> 
  <option value='2405' > 76 </option> 
  <option value='2406' > 81 </option> 
  <option value='2411' > abc123post </option> 
  <option value='3301' > circuit id 50 </option> 
  <option value='2105' > fadhil </option> 
  <option value='2104' > faisal </option> 
  <option value='3002' > IPEN - SITE TEST </option> 
  <option value='3601' > Manual Circuit ID </option> 
  <option value='3302' > new circuit id fadhil </option> 
  <option value='1809' > try iframe </option> 
</select>

        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-12">
          <div id="HTMLReportContent">
            <!-- javascript -->
          </div>
        </div>
      </div>
  </div>
</div>
@endsection