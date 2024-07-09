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

  .box{
    width: 20px;
    height: 20px;
    padding: 5px;
    border: 1px solid black;
    margin: auto;
  }

  .boxsudahDiisi{
    width: 20px;
    height: 20px;
    padding: 5px;
    border: 1px solid black;
    margin: auto;
    background: #EEEEEE;
  }

  .pilihanDISC{
    background: #0EF3F3;
  }
</style>
<!-- end section CSS -->

<!-- section Javascript -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
  // sb-sidenav-toggled
  $(document).ready(function () {
    $("#body").addClass('sb-sidenav-toggled');
    showIt();
    // openProfile();
  });

  function openIt($idUser, $idCategory, $jmlSoal){
    var elMenu = document.getElementById('hal_menu');
    var elQuiz = document.getElementById('hal_quiz');

    elMenu.style.display = 'none';  
    elQuiz.style.display = 'block';

    $flstart = true;
    $noQuiz = 0;

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
        if(result.code_status == '003' || result.code_status == null){
          $flstart = false;
        }
        $noQuiz = result.MIN_TERJAWAB == null ? $jmlSoal : result.MIN_TERJAWAB;
        $jmlSoal = $jmlSoal;
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
        if($idCategory == '2'){
          $contentHTML = "";
          $pilihanGanda = '';
          $.each(result, function(x, y) {
            if(y.id_type == '0'){
              //onclick="ResultAnswer('+y.id_quiz+','+y.no_quiz+','+y.correct_answer+','+$idCategory+','+y.id_quiz_dtl+',\''+ y.description + '\','+$idUser+');"
              // $pilihanGanda = $pilihanGanda + 
              //                   '<div class="row textCenter" >'+
              //                     '<div class="col-md-1 border klikAnswer"></div>'+
              //                     '<div class="col-md-5 border">'+ y.description + '</div>'+
              //                     '<div class="col-md-1 border klikAnswer"></div>'+
              //                   '</div>';
              $pilihanGanda = $pilihanGanda + 
                                '<table class="textCenter table" style="margin: auto;" width="100%">'+
                                    '<input type="text" id="temp_P" hidden>'+
                                    '<input type="text" id="temp_K" hidden>'+
                                    '<tr>'+
                                      '<td class="textCenter" width="5%"><div id="P_'+y.id_quiz_dtl+'" class="box klikAnswer" onclick="DISCAnswer1('+y.id_quiz+','+y.no_quiz+','+y.correct_answer+','+$idCategory+','+y.id_quiz_dtl+',\''+ y.description + '\','+$idUser+',\'P\');"></div></td>'+
                                      '<td>'+ y.description + '</td>'+
                                      '<td class="textCenter" width="5%"><div id="K_'+y.id_quiz_dtl+'" class="box klikAnswer" onclick="DISCAnswer1('+y.id_quiz+','+y.no_quiz+','+y.correct_answer+','+$idCategory+','+y.id_quiz_dtl+',\''+ y.description + '\','+$idUser+',\'K\');"></div></td>'+
                                    '</tr>'+
                                '</table>';
            }
          });
          $headPilihanGanda = '<table class="textCenter" style="border-bottom: 1px solid black; font-weight: bold; margin: auto;" width="100%"><tr><td width="5%">P</td><td>Pernyataan</td><td width="5%">K</td></tr></table>'
          $contentHTML =  '<div class="row justify-content-md-center">'+
                                '<div class="col-md-10">'+
                                  '<div class="card">'+
                                    '<div class="card-body">'+
                                      '<div class="row">'+
                                        '<div id="contentQuiz" class="textCenter">'+
                                          '<h1>Contoh Soal</h1>'+
                                          '<hr/>'+
                                          $headPilihanGanda +
                                          $pilihanGanda +
                                          '<div id="resultAnswer"></div>'+
                                        '</div>'+
                                      '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>';
          $('#hal_quiz').html('');
          $('#hal_quiz').append($contentHTML);
        }else{
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
                                              '<input type="text" autocomplete="off" class="form-control form-control-sm" id="E_'+y.id_quiz+'" name="E_'+y.id_quiz+'" value=""></input>'+
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
                                '<div class="col-md border klikAnswer" onclick="ResultAnswer('+y.id_quiz+','+y.no_quiz+','+y.correct_answer+','+$idCategory+','+y.id_quiz_dtl+',\''+ y.description + '\','+$idUser+');">'+
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
        }
      },
      error : function(xhr){

      }
    });
  }

  function DISCAnswer1($idQuiz,$noQuiz,$correctAnswer,$idCategory,$idQuizDtl,$desc,$idUser,$tipe){
    $tempK = document.getElementById("temp_K").value;
    $tempP = document.getElementById("temp_P").value;

    if($tipe == 'P'){
      for($x=1;$x<=4;$x++){
        $("#P_"+$x).removeClass('pilihanDISC');
        document.getElementById("temp_P").value = "";
      }
      if($tempK != $idQuizDtl){
        document.getElementById("temp_P").value = $idQuizDtl;
        $("#P_"+$idQuizDtl).addClass('pilihanDISC');
      }
    }else{
      for($x=1;$x<=4;$x++){
        $("#K_"+$x).removeClass('pilihanDISC');
        document.getElementById("temp_K").value = "";
      }
      if($tempP != $idQuizDtl){
        document.getElementById("temp_K").value = $idQuizDtl;
        $("#K_"+$idQuizDtl).addClass('pilihanDISC');
      }
    }

    $tempK = document.getElementById("temp_K").value;
    $tempP = document.getElementById("temp_P").value;

    if($tempK != "" && $tempP != ""){
      document.getElementById('resultAnswer').innerHTML = '<span> Jawaban Anda: PALING MENGGAMBARKAN '+$tempP+', KURANG MENGGAMBARKAN '+$tempK+' </span><br/><button type="button" class="btn btn-primary btn-sm" onclick="GetStarted('+$idCategory+',0,'+$idUser+');">Next >></button>';
    }else{
      document.getElementById('resultAnswer').innerHTML = "";
    }
  }

  function DISCAnswer2($idQuiz,$noQuiz,$idCategory,$idQuizDtl,$desc,$idUser,$tipe,$jmlSoal){
    $tempK = document.getElementById("temp_K").value;
    $tempP = document.getElementById("temp_P").value;

    if($tipe == 'P'){
      for($x=1;$x<=4;$x++){
        $("#P_"+$x).removeClass('pilihanDISC');
        document.getElementById("temp_P").value = "";
      }
      if($tempK != $idQuizDtl){
        document.getElementById("temp_P").value = $idQuizDtl;
        $("#P_"+$idQuizDtl).addClass('pilihanDISC');
      }
    }else{
      for($x=1;$x<=4;$x++){
        $("#K_"+$x).removeClass('pilihanDISC');
        document.getElementById("temp_K").value = "";
      }
      if($tempP != $idQuizDtl){
        document.getElementById("temp_K").value = $idQuizDtl;
        $("#K_"+$idQuizDtl).addClass('pilihanDISC');
      }
    }

    $tempK = document.getElementById("temp_K").value;
    $tempP = document.getElementById("temp_P").value;

    if($tempK != "" && $tempP != ""){
      $answer = $tempP + "|" + $tempK;
      SubmitAnswer($idQuiz,$idCategory,$answer,$idUser,$jmlSoal,$noQuiz,'0');
    }
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
    if($idCategory == '0' || $idCategory == '3'){
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
    }else{
      document.getElementById('resultAnswer').innerHTML = '<span style="color:green"> Jawaban Anda: '+$ResultAnswer+' </span><br/><button type="button" class="btn btn-primary btn-sm" onclick="GetStarted('+$idCategory+',0,'+$idUser+');">Next >></button>';
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
                                    '<button type="button" class="btn btn-primary btn-sm" onclick="StartIt('+$idCategory+',1,'+$idUser+','+result["master_soal"].JMLH_L+','+result["master_category"].fl_waktu+');">MULAI</button>'+
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
    CekTimer($idCategory,$idUser);
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
        if($idCategory == '2'){
          $contentHTML = "";
          $pilihanGanda = '';
          $numberSoal = getTableNumber($idCategory, $noQuiz, $idUser, $jmlSoal);
          $.each(result, function(x, y) {
            $answerP = '';
            $answerK = '';
            if(y.answer){
              $arrAnswer = y.answer.split("|");
              $answerP = parseInt($arrAnswer[0]);
              $answerK = parseInt($arrAnswer[1]);
            }
            if(y.id_type == '0'){
              $pilihanGanda = $pilihanGanda + 
                                '<table class="textCenter table" style="margin: auto;" width="100%">'+
                                    '<input type="text" id="temp_P" hidden>'+
                                    '<input type="text" id="temp_K" hidden>'+
                                    '<tr>'+
                                      '<td class="textCenter" width="5%"><div id="P_'+y.id_quiz_dtl+'" class="klikAnswer '+($answerP == y.id_quiz_dtl ? "boxsudahDiisi" : "box")+'" onclick="DISCAnswer2('+y.id_quiz+','+y.no_quiz+','+$idCategory+','+y.id_quiz_dtl+',\''+ y.description + '\','+$idUser+',\'P\','+$jmlSoal+');"></div></td>'+
                                      '<td>'+ y.description + '</td>'+
                                      '<td class="textCenter" width="5%"><div id="K_'+y.id_quiz_dtl+'" class="klikAnswer '+($answerK == y.id_quiz_dtl ? "boxsudahDiisi" : "box")+'" onclick="DISCAnswer2('+y.id_quiz+','+y.no_quiz+','+$idCategory+','+y.id_quiz_dtl+',\''+ y.description + '\','+$idUser+',\'K\','+$jmlSoal+');"></div></td>'+
                                    '</tr>'+
                                '</table>';
            }
          });
          $headPilihanGanda = '<table class="textCenter" style="border-bottom: 1px solid black; font-weight: bold; margin: auto;" width="100%"><tr><td width="5%">P</td><td>Pernyataan</td><td width="5%">K</td></tr></table>'
          $contentHTML =  '<div class="row justify-content-md-center">'+
                                '<div class="col-md-10">'+
                                  '<div class="card">'+
                                    '<div class="card-header" style="background-color: white;">'+
                                      $numberSoal+
                                    '</div>'+
                                    '<div class="card-body">'+
                                      '<div class="row">'+
                                        '<div id="contentQuiz" class="textCenter">'+
                                          $headPilihanGanda +
                                          $pilihanGanda +
                                          '<div id="resultAnswer"></div>'+
                                        '</div>'+
                                      '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>';
          $('#hal_quiz').html('');
          $('#hal_quiz').append($contentHTML);
        }else{
          $contentHTML = "";
          $pilihanGanda = '';
          $numberSoal = getTableNumber($idCategory, $noQuiz, $idUser, $jmlSoal);
          
          $.each(result, function(x, y) {
            if(y.id_type == '2'){
              $contentHTML =  '<div class="row justify-content-md-center">'+
                                '<div class="col-md-10">'+
                                  '<div class="card">'+
                                    '<div class="card-header" style="background-color: white;">'+
                                      $numberSoal+
                                    '</div>'+
                                    '<div class="card-body">'+
                                      '<div class="row">'+
                                        '<div id="contentQuiz" class="textCenter">'+
                                          '<p style="white-space:pre-line">'+y.question+'</p>'+
                                          '<img src="{{url("/img/soal")}}/'+y.img_desc+'" alt="Image"/>'+
                                          '<div class="d-flex justify-content-center mb-3">'+
                                            '<div data-mdb-input-init class="form-outline me-2" style="width: 8rem">'+
                                              '<input type="text" autocomplete="off" class="form-control form-control-sm" id="E_'+y.id_quiz+'" name="E_'+y.id_quiz+'" value="'+(y.answer == null ? "" : y.answer)+'"></input>'+
                                            '</div>'+
                                            '<button type="button" class="btn btn-primary btn-sm" onclick="SubmitAnswer('+y.id_quiz+','+$idCategory+','+y.id_quiz_dtl+','+$idUser+','+$jmlSoal+','+$noQuiz+','+y.id_type+');">SUBMIT</button>'+
                                          '</div>'+
                                          '<div id="resultAnswer"></div>'+
                                        '</div>'+
                                      '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>';
            }else if(y.id_type == '1'){
              $contentHTML =  '<div class="row justify-content-md-center">'+
                                '<div class="col-md-10">'+
                                  '<div class="card">'+
                                    '<div class="card-header" style="background-color: white;">'+
                                      $numberSoal+
                                    '</div>'+
                                    '<div class="card-body">'+
                                      '<div class="row">'+
                                        '<div id="contentQuiz" class="textCenter">'+
                                          '<p style="white-space:pre-line">'+y.question+'</p>'+
                                          '<div class="d-flex justify-content-center mb-3">'+
                                            '<div data-mdb-input-init class="form-outline me-2" style="width: 8rem">'+
                                              '<input type="text" autocomplete="off" class="form-control form-control-sm" id="E_'+y.id_quiz+'" name="E_'+y.id_quiz+'" value="'+(y.answer == null ? "" : y.answer)+'"></input>'+
                                            '</div>'+
                                            '<button type="button" class="btn btn-primary btn-sm" onclick="SubmitAnswer('+y.id_quiz+','+$idCategory+','+y.id_quiz_dtl+','+$idUser+','+$jmlSoal+','+$noQuiz+','+y.id_type+');">SUBMIT</button>'+
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
                                '<div class="col-md border klikAnswer '+(y.answer == y.id_quiz_dtl ? "sudahDiisi" : "")+'" onclick="SubmitAnswer('+y.id_quiz+','+$idCategory+','+y.id_quiz_dtl+','+$idUser+','+$jmlSoal+','+$noQuiz+','+y.id_type+');">'+
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
                                          '<p style="white-space:pre-line">'+y.question+'</p>'+
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
        }
        
      },
      error : function(xhr){

      }
    });
  }

  function CekTimer($idCategory,$idUser){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/cekWaktu",
      data    : {
        "IdCategory" : $idCategory,
        "idUser" : $idUser,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        var timeleft = result;
        var downloadTimer = setInterval(function(){
          if(timeleft <= 0){
            clearInterval(downloadTimer);
            updateDone($idCategory, $idUser);
            Swal.fire({
              title: "Test Sudah Selesai",
              text: "Silahkan klik 'Selesai'",
              icon: 'info',
              inputAttributes: {
                autocapitalize: 'off'
              },
              showCancelButton: false,
              confirmButtonText: "Selesai",
              allowOutsideClick: false
            }).then(function(x) {
              if(x.value === true){
                showIt();
                var elMenu = document.getElementById('hal_menu');
                var elQuiz = document.getElementById('hal_quiz');

                swal.fire("Sukses!","Tes anda sudah selesai.", "success");

                elMenu.style.display = 'block';  
                elQuiz.style.display = 'none';
              }
            });
          }else{
            // console.log("sisa waktu: " + timeleft);
          }
          timeleft -= 1;
        }, 1000);
      }
    });
  }

  function SubmitAnswer($id_quiz,$idCategory,$id_quiz_dtl,$idUser,$jmlSoal,$noQuiz,$type_quiz){
    $getAnswer = "";
    if($type_quiz == '0'){
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
        $flstart = true;
        $noQuiz = 0;
        $jmlSoal = $jmlSoal;

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
            if(result.MIN_TERJAWAB == null){
              $flstart = false;
            }
            $noQuiz = result.MIN_TERJAWAB;
          }
        });

        if($noQuiz <= $jmlSoal && $flstart == true){
          StartIt($idCategory, $noQuiz, $idUser, $jmlSoal);
        }else{
          if($flstart == false){
            Swal.fire({
              title: "Test Sudah Selesai",
              text: "Silahkan periksa kembali sebelum klik 'Selesai'",
              icon: 'info',
              inputAttributes: {
                autocapitalize: 'off'
              },
              showCancelButton: true,
              confirmButtonText: "Selesai",
              cancelButtonText: "Close",
              allowOutsideClick: false
            }).then(function(x) {
              if(x.value === true){
                updateDone($idCategory, $idUser);
                showIt();
                var elMenu = document.getElementById('hal_menu');
                var elQuiz = document.getElementById('hal_quiz');

                swal.fire("Sukses!","Tes anda sudah selesai.", "success");

                elMenu.style.display = 'block';  
                elQuiz.style.display = 'none';
              }
            });
          }else{
            StartIt($idCategory, $noQuiz, $idUser, $jmlSoal);
          }
        }
      },
      error : function(xhr){
        swal.fire("Info!","Terjadi kendala. Hubungi IT Administrator.", "info");
      }
    });
  }

  function updateDone($idCategory, $idUser){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/updateDone",
      data    : {
        "idUser" : $idUser,
        "idCategory" : $idCategory
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
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
              $rows = $rows + '<td border="1" width="57px" class="klikNomor textCenter terisi '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
              $count = $count + 1;
            }else{
              if($count == 1){
                $rows = $rows + '<tr><td width="57px" class="klikNomor textCenter terisi '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
                $count = $count + 1;
              }else{
                $rows = $rows + '</tr>'+'<tr><td width="57px" class="klikNomor textCenter terisi '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
                $count = 2;
              }
            }
          }else{
            if($batas >= $count && $count != "1"){
              $rows = $rows + '<td width="57px" class="klikNomor textCenter '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
              $count = $count + 1;
            }else{
              if($count == 1){
                $rows = $rows + '<tr><td width="57px" class="klikNomor textCenter '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
                $count = $count + 1;
              }else{
                $rows = $rows + '</tr>'+'<tr><td width="57px" class="klikNomor textCenter '+($noQuiz == y.id_quiz ? "active" : "")+'" onClick="StartIt('+$idCategory+','+y.id_quiz+','+$idUser+','+$jmlSoal+')">'+y.id_quiz+'</td>';
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
          if(y.code_status == '004'){
            $('#tblQuiz').append(''+
            '<tr>'+
              '<td class="textRight">'+i+'</td>'+
              '<td class="textLeft">'+y.quiz+'</td>'+
              '<td class="textRight">'+y.jmlh_soal+'</td>'+
              '<td class="textRight">'+y.lama_waktu+'</td>'+
              '<td class="textCenter">'+y.ket_status+'</td>'+
              '<td class="textCenter">COMPLETE</td>'+
            '</tr>'+
            '');
          }else{
            $('#tblQuiz').append(''+
            '<tr>'+
              '<td class="textRight">'+i+'</td>'+
              '<td class="textLeft">'+y.quiz+'</td>'+
              '<td class="textRight">'+y.jmlh_soal+'</td>'+
              '<td class="textRight">'+y.lama_waktu+'</td>'+
              '<td class="textCenter">'+y.ket_status+'</td>'+
              '<td class="textCenter">'+
                '<button type="button" class="btn btn-primary btn-sm" onclick="openIt('+y.id_user+','+y.id_category+','+y.jmlh_soal+');">OPEN</button>'+
              '</td>'+
            '</tr>'+
            '');
          }
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