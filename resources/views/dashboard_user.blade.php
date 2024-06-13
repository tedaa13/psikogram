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

  .active{
    background: #0EF3F3  !important; 
  }

  .sudahDiisi{
    background: #EEEEEE;
  }

  .klikAnswer{
    cursor:pointer;
  }

  .klikAnswer:hover{
    background: #0EF3F3;
  }

  .klikNomor{
    cursor:pointer;
    background-color:#F3F7EC;
    border: 3px white solid;
  }

  .klikNomor:hover{
    background: #0EF3F3;
  }

  .terisi{
    background: #F1E5D1;
  }

  .divElement{
    /* position: absolute; */
    /* top: 50%; */
    /* left: 50%; */
    margin-top: 10%;
    /* margin-left: -50px; */
    /* width: 100px; */
    /* height: 100px; */
  }
</style>
<!-- end section CSS -->

<!-- section Javascript -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
  $(document).ready(function () {
    showIt();
    // openProfile();
  });

  function openIt($idUser, $idCategory){
    var elMenu = document.getElementById('hal_menu');
    var elQuiz = document.getElementById('hal_quiz');

    elMenu.style.display = 'none';  
    elQuiz.style.display = 'block';

    $flstart = true;
    $noQuiz = 0;
    $jmlSoal = 0;

    $.ajax({
      type    : 'POST',
      async   : false,
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/cekLastSave",
      data    : {
        "idUser" : $idUser,
        "idCategory" : $idCategory
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $flstart = false;
        $noQuiz = result.MIN_TERJAWAB;
        $jmlSoal = result.JMLH_SOAL;
      }
    });

    if($flstart == true){
      $.ajax({
        type    : 'POST',
        dataType: 'JSON',
        url   	: "{{ url('dashboard_user') }}/getContentQuiz",
        data    : {
          "idUser" : $idUser,
          "IdCategory" : $idCategory
        },
        headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success : function(result){
          document.getElementById('TotalSoalE').value = result["master_soal"].JMLH_E;
          document.getElementById('TotalSoalL').value = result["master_soal"].JMLH_L;
          $contentHTML = "";
          $contentHTML =  '<div class="row justify-content-md-center">'+
                            '<div class="col-md-10">'+
                              '<div class="card">'+
                                '<div class="card-body">'+
                                  '<div class="row">'+
                                    '<div id="contentQuiz" class="textCenter">'+
                                      '<h1>PETUNJUK</h1>'+
                                      '<hr/>'+
                                      '<p style="white-space:pre-line" class="textLeft">'+result["master_category"].fl_instruction+'</p>'+
                                      '<button type="button" class="btn btn-primary btn-sm" onclick="PageContohSoal('+$idCategory+',1,'+$idUser+');">CONTOH SOAL</button>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</div>';
          $('#hal_quiz').html('');
          $('#hal_quiz').append($contentHTML);
        }
      });
    }else{
      StartIt($idCategory, $noQuiz, $idUser, $jmlSoal);
    }
  }

  function PageContohSoal($idCategory,$no,$idUser){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/getContohSoal",
      data    : {
        "IdCategory" : $idCategory,
        "noQuiz" : $no
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $contentHTML = "";
        $pilihanGanda = '';
        $.each(result, function(x, y) {
          if(y.id_type == '1'){
            $contentHTML =  '<div class="row justify-content-md-center">'+
                              '<div class="col-md-10">'+
                                '<div class="card">'+
                                  '<div class="card-body">'+
                                    '<div class="row">'+
                                      '<div id="contentQuiz" class="textCenter">'+
                                        '<h1>Contoh Soal '+y.no_quiz+'</h1>'+
                                        '<hr/>'+
                                        '<p>'+y.question+'</p>'+
                                        '<div class="d-flex justify-content-center mb-3">'+
                                          '<div data-mdb-input-init class="form-outline me-2" style="width: 8rem">'+
                                            '<input type="text" class="form-control form-control-sm" id="E_'+y.id_quiz+'" name="E_'+y.id_quiz+'" value=""></input>'+
                                          '</div>'+
                                          '<button type="button" class="btn btn-primary btn-sm" onclick="ResultAnswer('+y.id_quiz+','+y.no_quiz+','+y.correct_answer+','+$idCategory+','+y.id_quiz_dtl+','+y.description+','+$idUser+');">SUBMIT</button>'+
                                        '</div>'+
                                        '<div id="resultAnswer"></div>'+
                                      '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>'+
                            '</div>';
          }else{
            $pilihanGanda = $pilihanGanda + 
                              '<div class="col-md border klikAnswer" onclick="ResultAnswer('+y.id_quiz+','+y.no_quiz+','+y.correct_answer+','+$idCategory+','+y.id_quiz_dtl+','+y.description+','+$idUser+');">'+
                                y.description+
                              '</div>';
            $contentHTML =  '<div class="row justify-content-md-center">'+
                              '<div class="col-md-10">'+
                                '<div class="card">'+
                                  '<div class="card-body">'+
                                    '<div class="row">'+
                                      '<div id="contentQuiz" class="textCenter">'+
                                        '<h1>Contoh Soal '+y.no_quiz+'</h1>'+
                                        '<hr/>'+
                                        '<p>'+y.question+'</p>'+
                                        '<div class="row">'+$pilihanGanda + '</div>'+ 
                                        '<div id="resultAnswer"></div>'+
                                      '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>'+
                            '</div>';
          }
        });
        $('#hal_quiz').html('');
        $('#hal_quiz').append($contentHTML);
      },
      error : function(xhr){

      }
    });
  }

  function ResultAnswer($idQuiz,$noQuiz,$correctAnswer,$idCategory,$idQuizDtl,$desc,$idUser){
    $MaxSoal    = document.getElementById('TotalSoalE').value;
    $getAnswer = "";
    $ResultAnswer = "";

    //validasi untuk tipe quiz isian/pilihan ganda
    if($idQuizDtl){
      $getAnswer  = $idQuizDtl;
      $ResultAnswer = $desc;
    }else{
      $getAnswer  = document.getElementById('E_'+$idQuiz).value;
      $ResultAnswer = $correctAnswer;
    }
    
    //validasi untuk hasil jawaban benar/salah
    if($getAnswer == $correctAnswer){
      if($MaxSoal <= $noQuiz ){
        document.getElementById('resultAnswer').innerHTML = '<span style="color:green"> BENAR! Jawabannya '+$ResultAnswer+' </span><br/><button type="button" class="btn btn-primary btn-sm" onclick="GetStarted('+$idCategory+',0,'+$idUser+');">Next >></button>';
      }else{
        $noQuiz = $noQuiz + 1;
        document.getElementById('resultAnswer').innerHTML = '<span style="color:green"> BENAR! Jawabannya '+$ResultAnswer+' </span><br/><button type="button" class="btn btn-primary btn-sm" onclick="PageContohSoal('+$idCategory+','+$noQuiz+','+$idUser+');">Next >></button>';
      }
    }else{
      if($MaxSoal <= $noQuiz ){
        document.getElementById('resultAnswer').innerHTML = '<span style="color:red"> SALAH! Jawabannya '+$ResultAnswer+' </span><br/><button type="button" class="btn btn-primary btn-sm" onclick="GetStarted('+$idCategory+',0,'+$idUser+');">Next >></button>';
      }else{
        $noQuiz = $noQuiz + 1;
        document.getElementById('resultAnswer').innerHTML = '<span style="color:red"> SALAH! Jawabannya '+$ResultAnswer+' </span><br/><button type="button" class="btn btn-primary btn-sm" onclick="PageContohSoal('+$idCategory+','+$noQuiz+','+$idUser+');">Next >></button>';
      }
    }
  }

  function GetStarted($idCategory, $noQuiz, $idUser){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/getContentQuiz",
      data    : {
        "idUser" : $idUser,
        "IdCategory" : $idCategory
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $contentHTML = "";
        $contentHTML =  '<div class="row justify-content-md-center">'+
                          '<div class="col-md-10">'+
                            '<div class="card">'+
                              '<div class="card-body">'+
                                '<div class="row">'+
                                  '<div id="contentQuiz" class="textCenter">'+
                                    '<h1>PSIKOTEST KATEGORI '+result["master_category"].fl_code+'</h1>'+
                                    '<hr/>'+
                                    '<p>Terdapat <b>'+result["master_soal"].JMLH_L+'</b> soal pertanyaan, Kerjakan dalam waktu <b>'+result["master_category"].fl_waktu+'</b> menit.</p>'+
                                    '<button type="button" class="btn btn-primary btn-sm" onclick="StartIt('+$idCategory+',1,'+$idUser+','+result["master_soal"].JMLH_L+');">MULAI</button>'+
                                  '</div>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</div>'+
                        '</div>';
        $('#hal_quiz').html('');
        $('#hal_quiz').append($contentHTML);
      },
      error : function(xhr){

      }
    });
  }

  function StartIt($idCategory, $noQuiz, $idUser, $jmlSoal){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/getSoal",
      data    : {
        "IdCategory" : $idCategory,
        "noQuiz" : $noQuiz,
        "idUser" : $idUser,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $contentHTML = "";
        $pilihanGanda = '';
        $numberSoal = getTableNumber($idCategory, $noQuiz, $idUser, $jmlSoal);

        $.each(result, function(x, y) {
          if(y.id_type == '1'){
            $contentHTML =  '<div class="row justify-content-md-center">'+
                              '<div class="col-md-10">'+
                                '<div class="card">'+
                                  '<div class="card-header" style="background-color: white;">'+
                                    $numberSoal+
                                  '</div>'+
                                  '<div class="card-body">'+
                                    '<div class="row">'+
                                      '<div id="contentQuiz" class="textCenter">'+
                                        '<p>'+y.question+'</p>'+
                                        '<div class="d-flex justify-content-center mb-3">'+
                                          '<div data-mdb-input-init class="form-outline me-2" style="width: 8rem">'+
                                            '<input type="text" class="form-control form-control-sm" id="E_'+y.id_quiz+'" name="E_'+y.id_quiz+'" value="'+(y.answer == null ? "" : y.answer)+'"></input>'+
                                          '</div>'+
                                          '<button type="button" class="btn btn-primary btn-sm" onclick="SubmitAnswer('+y.id_quiz+','+$idCategory+','+y.id_quiz_dtl+','+$idUser+','+$jmlSoal+','+$noQuiz+');">SUBMIT</button>'+
                                        '</div>'+
                                        '<div id="resultAnswer"></div>'+
                                      '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>'+
                            '</div>';
          }else{
            $pilihanGanda = $pilihanGanda + 
                              '<div class="col-md border klikAnswer '+(y.answer == y.id_quiz_dtl ? "sudahDiisi" : "")+'" onclick="SubmitAnswer('+y.id_quiz+','+$idCategory+','+y.id_quiz_dtl+','+$idUser+','+$jmlSoal+','+$noQuiz+');">'+
                                y.description+
                              '</div>';
            $contentHTML =  '<div class="row justify-content-md-center">'+
                              '<div class="col-md-10">'+
                                '<div class="card">'+
                                  '<div class="card-header" style="background-color: white;">'+
                                    $numberSoal+
                                  '</div>'+
                                  '<div class="card-body">'+
                                    '<div class="row">'+
                                      '<div id="contentQuiz" class="textCenter">'+
                                        '<p>'+y.question+'</p>'+
                                        '<div class="row">'+$pilihanGanda + '</div>'+ 
                                        '<div id="resultAnswer"></div>'+
                                      '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>'+
                            '</div>';
          }
        });
        $('#hal_quiz').html('');
        $('#hal_quiz').append($contentHTML);
      },
      error : function(xhr){

      }
    });
  }

  function SubmitAnswer($id_quiz,$idCategory,$id_quiz_dtl,$idUser,$jmlSoal,$noQuiz){
    console.log($id_quiz,$idCategory,$id_quiz_dtl,$idUser);
    $getAnswer = "";
    if($id_quiz_dtl){
      $getAnswer  = $id_quiz_dtl;
    }else{
      $getAnswer  = document.getElementById('E_'+$id_quiz).value;
    }
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/SubmitAnswer",
      data    : {
        "idQuiz"      : $id_quiz,
        "idCategory"  : $idCategory,
        "idUser"      : $idUser,
        "answer"      : $getAnswer
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        if(($noQuiz + 1) <= $jmlSoal){
          StartIt($idCategory, $noQuiz + 1, $idUser, $jmlSoal);
        }else{
          swal.fire("Sukses!","Test Sudah Selesai. Silahkan Periksa Kembali sebelum Submit.", "success");
        }
      },
      error : function(xhr){
        swal.fire("Info!","Terjadi kendala. Hubungi IT Administrator.", "info");
      }
    });
  }

  function getTableNumber($idCategory, $noQuiz, $idUser, $jmlSoal){
    var $table = "";
    $.ajax({
      type    : 'POST',
      async   : false,
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/getTableNumber",
      data    : {
        "IdCategory" : $idCategory
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $rows = "";
        $count = 1;
        $.each(result, function(x, y) {
          $batas = 20;
          if(y.fl_jawab == '1'){
            if($batas >= $count && $count != "1"){
              $rows = $rows + '<td border="1" class="klikNomor textCenter terisi '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
              $count = $count + 1;
            }else{
              if($count == 1){
                $rows = $rows + '<tr><td class="klikNomor textCenter terisi '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
                $count = $count + 1;
              }else{
                $rows = $rows + '</tr>'+'<tr><td class="klikNomor textCenter terisi '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
                $count = 2;
              }
            }
          }else{
            if($batas >= $count && $count != "1"){
              $rows = $rows + '<td class="klikNomor textCenter '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
              $count = $count + 1;
            }else{
              if($count == 1){
                $rows = $rows + '<tr><td class="klikNomor textCenter '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
                $count = $count + 1;
              }else{
                $rows = $rows + '</tr>'+'<tr><td class="klikNomor textCenter '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
                $count = 2;
              }
            }
          }
          
        });
        $rows = $rows + '</tr>';
        $table = '<table width="100%">'+$rows+'</table>';
      }
    });

    return $table;
  }

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
            '<td class="textLeft">'+y.quiz+'</td>'+
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
</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <div id="hal_menu">
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
  <input type="text" id="TotalSoalE" name="TotalSoalE" value="" hidden></input>
  <input type="text" id="TotalSoalL" name="TotalSoalL" value="" hidden></input>
  <div id="hal_quiz" class="divElement">
    <!-- javascript -->
  <div>
</div>
@endsection