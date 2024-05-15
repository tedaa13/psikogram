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

  .highcharts-figure,
  .highcharts-data-table table {
      min-width: 360px;
      max-width: 800px;
      margin: 1em auto;
  }

  .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #ebebeb;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
  }

  .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
  }

  .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
  }

  .highcharts-data-table td,
  .highcharts-data-table th,
  .highcharts-data-table caption {
      padding: 0.5em;
  }

  .highcharts-data-table thead tr,
  .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
  }

  .highcharts-data-table tr:hover {
      background: #f1f7ff;
  }

  .mystyle{
    background-color: #49494A;
    color:white;
  }

</style>
<!-- end section CSS -->

<!-- section Javascript -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>


<script>
  $(document).ready(function () {
    clearIt();
  });

  function getDataTest(sel) {
    $id_user = $('#datalistOptions [value="' + sel.value + '"]').data('customvalue');

    getProfileUser($id_user);

    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataUser",
      data    : {
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        if(result.length > 0){
          var element = document.getElementById("detailTest");
          element.style.display = 'block';
          cekTest(result,$id_user);
          printReport($id_user);
        }else{
          clearIt();
        }
        
      },
      error : function(xhr){

      }
    });
  }

  function getProfileUser(id_user){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getProfileUser",
      data    : {
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        if(result.length > 0){
          document.getElementById("namaPeserta").innerHTML        = result[0].name;
          document.getElementById("pendidikanPeserta").innerHTML  = result[0].pendidikan;
          document.getElementById("tglLahirPeserta").innerHTML    = result[0].tmpt_lahir + " / " + result[0].tgl_lahir;
          document.getElementById("jabatanPeserta").innerHTML     = result[0].jabatan;
          document.getElementById("usiaPeserta").innerHTML        = result[0].umur;
          document.getElementById("masaKerjaPeserta").innerHTML   = result[0].masa_kerja;
          document.getElementById("tanggalTesPeserta").innerHTML  = result[0].tgl_tes;
          document.getElementById("tujuanTesPeserta").innerHTML   = result[0].tujuan_tes;
        }else{
          clearIt();
        }
        
      },
      error : function(xhr){

      }
    });
  }

  function cekTest($res,$id_user){
    $fl_active = 0;
    for($x=0;$x<$res.length;$x++){
      var element = document.getElementById($x);
      element.classList.remove("disabled");

      if($res[$x].code == 'PAPI'){
        printResultPAPI($res[$x].id_category,$res[$x].code,$id_user);
        printTestPAPI($res[$x].id_category,$res[$x].code,$id_user);
      }else if($res[$x].code == 'DISC'){
        printResultDISC($res[$x].id_category,$res[$x].code,$id_user);
        printTestDISC($res[$x].id_category,$res[$x].code,$id_user);
      }else{
        printResult($res[$x].id_category,$res[$x].code,$id_user);
        printTest($res[$x].id_category,$res[$x].code,$id_user);
      }

      if($fl_active == 0){
        var e = document.getElementById($x);
        e.classList.add("active");

        var element = document.getElementById($x+"_"+$res[$x].code);
        element.classList.remove("fade");
        element.classList.add("active");
        $fl_active = 1;
      }
    }
  }

  function clearIt(){
    var element = document.getElementById("detailTest");
    element.style.display = 'none';

    var e = document.getElementById("0");
    e.classList.remove("active");
    e.classList.add("disabled");
    var e = document.getElementById("1");
    e.classList.remove("active");
    e.classList.add("disabled");
    var e = document.getElementById("2");
    e.classList.remove("active");
    e.classList.add("disabled");
    var e = document.getElementById("3");
    e.classList.remove("active");
    e.classList.add("disabled");
    var e = document.getElementById("4");
    e.classList.remove("active");

    var e = document.getElementById("0_WPT");
    e.classList.remove("active");
    e.classList.add("fade");
    var e = document.getElementById("1_PAPI");
    e.classList.remove("active");
    e.classList.add("fade");
    var e = document.getElementById("2_DISC");
    e.classList.remove("active");
    e.classList.add("fade");
    var e = document.getElementById("3_ENG");
    e.classList.remove("active");
    e.classList.add("fade");
  }

  function printTest($id_category, $code, $id_user){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataTest",
      data    : {
        "id_category"	: $id_category,
        "code"	: $code,
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#dtl_'+$code).html('');
        $('#dtl_'+$code).append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblTest_'+$code+'">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Question</th>'+
                '<th>Answer</th>'+        
                '<th>Correct</th>'+                  
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          if(y.answer.toUpperCase() == y.correct_answer.toUpperCase()){
            $('#tblTest_'+$code).append(''+
            '<tr>'+
              '<td>'+i+'</td>'+
              '<td>'+y.question+'</td>'+
              '<td style="color:green;">'+y.ket_answer+'</td>'+
              '<td>'+y.ket_correct_answer+'</td>'+
            '</tr>'+
            '');
          }else{
            $('#tblTest_'+$code).append(''+
            '<tr>'+
              '<td>'+i+'</td>'+
              '<td>'+y.question+'</td>'+
              '<td style="color:red;">'+y.ket_answer+'</td>'+
              '<td>'+y.ket_correct_answer+'</td>'+
            '</tr>'+
            '');
          }
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblTest_'+$code).DataTable({
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

  function printTestPAPI($id_category, $code, $id_user){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataTest",
      data    : {
        "id_category"	: $id_category,
        "code"	: $code,
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#dtl_'+$code).html('');
        $('#dtl_'+$code).append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblTest_'+$code+'">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Question</th>'+
                '<th>Answer</th>'+                   
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var id_quiz_before = 0;
        var i = 1;
        $.each(result, function(x, y) {
          $answer = "";
          if(y.id_quiz_dtl == y.answer){
            $answer = y.ket_answer;
          }

          if(id_quiz_before != y.id_quiz){
            $('#tblTest_'+$code).append(''+
            '<tr>'+
              '<td>'+i+'</td>'+
              '<td>'+y.ket_correct_answer+'</td>'+
              '<td>'+$answer+'</td>'+
            '</tr>'+
            '');
            id_quiz_before = y.id_quiz;
            i++
          }else{
            $('#tblTest_'+$code).append(''+
            '<tr style="border-bottom: 3pt solid #CFE2FF;">'+
              '<td></td>'+
              '<td>'+y.ket_correct_answer+'</td>'+
              '<td>'+$answer+'</td>'+
            '</tr>'+
            '');
          }
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblTest_'+$code).DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
      },
      error : function(xhr){

      }
    });
  }

  function printResult($id_category, $code, $id_user){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataResult",
      data    : {
        "id_category"	: $id_category,
        "code"	: $code,
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        document.getElementById("jmlPertanyaan").innerHTML=result[0].JML_TERJAWAB;
        document.getElementById("jmlSalah").innerHTML=result[0].JML_JAWAB_SLH;
        document.getElementById("jmlBenar").innerHTML=result[0].JML_JAWAB_BNR;
        document.getElementById("hslIQ").innerHTML=result[0].HSL_IQ;
        document.getElementById("ketHasil").innerHTML=result[0].KET_IQ;
      },
      error : function(xhr){

      }
    });
  }

  function printResultPAPI($id_category, $code, $id_user){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataResultPAPI",
      data    : {
        "id_category"	: $id_category,
        "code"	: $code,
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        var $kanan = "";
        var $kananHeader = "";
        var $fullcontent = "";
        var $header = "";
        var $footer = "";
        var $tambah = 0;
        var $jmlhPOIN = 0;
        var $pembagi = 0;

        var $jumlahkanan = 1;
        for($j=0; $j<result.length; $j++){
          if($kananHeader != result[$j].grouping){
            $header = $header + '<td>'+result[$j].grouping+'</td>';
            if($kananHeader != ""){
              $footer = $footer + '<td>'+($jmlhPOIN/$pembagi).toFixed(2)+'</td>';
            }
            $jmlhPOIN = 0;
            $pembagi = 0;
            $kananHeader = result[$j].grouping;
          }
          $pembagi  = $pembagi + 1;
          $jmlhPOIN = $jmlhPOIN + result[$j].POIN;
          $jumlahkanan ++ ;

          if($j==result.length-1){
            $footer = $footer + '<td>'+($jmlhPOIN/$pembagi).toFixed(2)+'</td>';
          }
        }

        var $contentStart = '<table class="table table-bordered" border="1" width="100%">'+
                              '<tr class="table-primary boldFont textCenter">'+
                                '<td colspan="'+$jumlahkanan+'" width="100%">Result</td>'+
                              '</tr>'+
                              '<tr class="table-info boldFont textCenter">'+$header+'</tr>'+
                              '<tr>';
        var $contentMiddle = '';
        var $contentFinish = '</tr><tr class="table-info boldFont textCenter">'+$footer+'</tr></table>';
      
        for($z=0; $z<result.length; $z++){

          $z = $tambah;
          if($z >= result.length - 1){
            break;
          }
          $contentMiddle = $contentMiddle + "<td>";

          for($x=$z; $x<result.length; $x++){
            if($kanan != "" && $kanan != result[$x].grouping){
              $contentMiddle =  $contentMiddle + $fullcontent + "</table></td>";
              $fullcontent = "";
              $tambah = $x;
              $kanan = "";
              break;
            }

            if($kanan != result[$x].grouping){
              $fullcontent = $fullcontent + '<table class="table" width="100%" style="zoom:0.8;">'+
                                              '<tr style="height:60px">' +
                                                '<td>'+result[$x].desc_parameter+'</td>'+
                                                '<td>'+result[$x].POIN+'</td>'+
                                              '</tr>';
              $kanan = result[$x].grouping;
            }else{
              $fullcontent = $fullcontent + '<tr style="height:60px"><td>'+result[$x].desc_parameter+'</td><td>'+result[$x].POIN+'</td></tr>';
            }

            if($x >= result.length - 1){
              $contentMiddle =  $contentMiddle + $fullcontent + "</table></td>";
              $tambah = $x;
              break;
            }
          }
          
        }
        $('#contentResultPAPI').html($contentStart+$contentMiddle+$contentFinish);
      },
      error : function(xhr){

      }
    });
  }

  function printResultDISC($id_category, $code, $id_user){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataResultDISC",
      data    : {
        "id_category"	: $id_category,
        "code"	: $code,
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        var $contentStart = '<table class="table table-bordered" border="1" width="100%">'+
                              '<tr class="table-primary boldFont textCenter">'+
                                '<td colspan="4" width="100%">Result [Table]</td>'+
                              '</tr>'+
                              '<tr class="table-info boldFont textCenter">'+
                                '<td></td>'+
                                '<td>Graph 1</td>'+
                                '<td>Graph 2</td>'+
                                '<td>Graph 3</td>'+
                              '</tr>';
        var $contentMiddle = '';

        var skorM = 0;
        var skorL = 0;
        var skorC = 0;

        var ket_M = "";
        var ket_L = "";
        var ket_C = "";

        var desc_M = "";
        var desc_L = "";
        var desc_C = "";

        var arti_M = "";
        var arti_L = "";
        var arti_C = "";

        for($x=0;$x<result.length;$x++){
          $contentMiddle = $contentMiddle + '<tr class="textCenter">'+
                                              '<td class="textCenter boldFont">'+result[$x].keterangan+'</td>'+
                                              '<td>'+result[$x].skor_M+'</td>'+
                                              '<td>'+result[$x].skor_L+'</td>'+
                                              '<td>'+result[$x].skor_C+'</td>'+
                                            '</tr>';
          
          if(result[$x].skor_M > skorM){
            ket_M = result[$x].keterangan;
            arti_M = result[$x].description;
            desc_M = result[$x].desc_M;
            skorM = result[$x].skor_M;
          }
          if(result[$x].skor_L > skorL){
            ket_L = result[$x].keterangan;
            arti_L = result[$x].description;
            desc_L = result[$x].desc_L;
            skorL = result[$x].skor_L;
          }
          if(result[$x].skor_C > skorC){
            ket_C = result[$x].keterangan;
            arti_C = result[$x].description;
            desc_C = result[$x].desc_C;
            skorC = result[$x].skor_C;
          }
        }

        document.getElementById("skor_M").innerHTML=ket_M;
        document.getElementById("skor_L").innerHTML=ket_L;
        document.getElementById("skor_C").innerHTML=ket_C;

        document.getElementById("arti_M").innerHTML=arti_M;
        document.getElementById("arti_L").innerHTML=arti_L;
        document.getElementById("arti_C").innerHTML=arti_C;

        document.getElementById("desc_M").innerHTML=wordsLen(desc_M,arti_M);
        document.getElementById("desc_L").innerHTML=wordsLen(desc_L,arti_L);
        document.getElementById("desc_C").innerHTML=wordsLen(desc_C,arti_C);


        document.getElementById("DISC_REPORT_1").innerHTML=ket_M;
        document.getElementById("DISC_REPORT_2").innerHTML=ket_L;
        document.getElementById("DISC_REPORT_3").innerHTML=ket_C;

        document.getElementById("DISC_REPORT_1_desc").innerHTML=arti_M;
        document.getElementById("DISC_REPORT_2_desc").innerHTML=arti_L;
        document.getElementById("DISC_REPORT_3_desc").innerHTML=arti_C;

        document.getElementById("karakter_umum").innerHTML=desc_L;
        document.getElementById("kelebihan").innerHTML=desc_C;
        document.getElementById("kekurangan").innerHTML=desc_M;

        $('#contentResultDISC').html($contentStart+$contentMiddle);
      },
      error : function(xhr){

      }
    });
  }

  function printTestDISC($id_category, $code, $id_user){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataTest",
      data    : {
        "id_category"	: $id_category,
        "code"	: $code,
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#dtl_'+$code).html('');
        $('#dtl_'+$code).append(''+

        '<div class="table-responsive">'+
          '<table class="table table-bordered" style="width:100%" id="tblTest_'+$code+'">'+
            '<thead>'+
              '<tr>'+
                '<th style="width:5%;">No</th>'+
                '<th style="width:91%;">Description</th>'+
                '<th style="width:2%;">P</th>'+   
                '<th style="width:2%;">K</th>'+                   
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var id_quiz_before = 0;
        var i = 1;
        $.each(result, function(x, y) {
          if(id_quiz_before != y.id_quiz){
            $('#tblTest_'+$code).append(''+
            '<tr style="border-top: 3pt solid #CFE2FF;">'+
              '<td>'+i+'</td>'+
              '<td>'+y.description+'</td>'+
              '<td>'+y.MOST_ANSWER+'</td>'+
              '<td>'+y.LEAST_ANSWER+'</td>'+
            '</tr>'+
            '');
            id_quiz_before = y.id_quiz;
            i++
          }else{
            $('#tblTest_'+$code).append(''+
            '<tr>'+
              '<td></td>'+
              '<td>'+y.description+'</td>'+
              '<td>'+y.MOST_ANSWER+'</td>'+
              '<td>'+y.LEAST_ANSWER+'</td>'+
            '</tr>'+
            '');
          }
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblTest_'+$code).DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
      },
      error : function(xhr){

      }
    });
  }

  function wordsLen(str,code) { 
    const maxString = 13;
    const array = str.trim().split(/\s+/); 

    if(maxString > array.length){
      maxString = array.length;
    }

    $string = "";
    for($x=0;$x<maxString;$x++){
      if($x == 0){
        $string = array[$x];
      }else{
        $string = $string + " " + array[$x];
      }
    }

    return $string + '<span style="font-style:italic; color:blue; cursor:pointer;" onclick="getDetail(\'' + str + '\',\'' + code + '\')">  read more ...</span>'; 
  } 

  function getDetail(str,code){
    Swal.fire({
      title: code,
      text: str,
    });
  }

  function printReport(idUser){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataReport",
      data    : {
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        $('#tableHasil').html("");
        if(result.length > 0){
          $headerTable = '<table class="table table-bordered" style="width:100%; margin-top:25px;">'+
                            '<tr class="textCenter boldFont">'+
                              '<td rowspan="4" style="width:1%;">No</td>'+
                              '<td rowspan="4" style="width:59%;">Aspek Psikologi</td>'+
                              '<td rowspan="4" style="width:10%;">Total Skor</td>'+
                              '<td colspan="4" style="width:30%;">Kategori Penilaian</td>'+
                              //colspan 2
                              //colspan 3
                              //colspan 4
                            '</tr>'+
                            '<tr class="textCenter boldFont">'+
                              //rowspan 2
                              //rowspan 2
                              //rowspan 2
                              '<td>K</td>'+
                              '<td>C</td>'+
                              '<td>B</td>'+
                              '<td>SB</td>'+
                            '</tr>'+
                            '<tr class="textCenter boldFont">'+
                              //rowspan 3
                              //rowspan 3
                              //rowspan 3
                              '<td><80</td>'+
                              '<td>80-90</td>'+
                              '<td>91-100</td>'+
                              '<td>>100</td>'+
                            '</tr>'+
                            '<tr class="textCenter boldFont">'+
                              //rowspan 4
                              //rowspan 4
                              //rowspan 4
                              '<td>0-2.5</td>'+
                              '<td>2.6-5.0</td>'+
                              '<td>5.1-7.5</td>'+
                              '<td>>7.5</td>'+
                            '</tr>';

          $contentTable = "";
          $jmlh_K = 0;
          $jmlh_C = 0;
          $jmlh_B = 0;
          $jmlh_SB = 0;
          $no = 1;
          for($x=0;$x<result.length;$x++){
            if(result[$x].K != ""){
              $jmlh_K = $jmlh_K + 1;
            }
            if(result[$x].C != ""){
              $jmlh_C = $jmlh_C + 1;
            }
            if(result[$x].B != ""){
              $jmlh_B = $jmlh_B + 1;
            }
            if(result[$x].SB != ""){
              $jmlh_SB = $jmlh_SB + 1;
            }

            $contentTable = $contentTable + '<tr>'+
                                              '<td class="textCenter">'+$no+'</td>'+
                                              '<td>'+result[$x].desc_+'<br/>'+result[$x].keterangan+'</td>'+
                                              '<td class="textCenter">'+(result[$x].hasil/1).toFixed(2)+'</td>'+
                                              '<td class="textCenter boldFont">'+result[$x].K+'</td>'+
                                              '<td class="textCenter boldFont">'+result[$x].C+'</td>'+
                                              '<td class="textCenter boldFont">'+result[$x].B+'</td>'+
                                              '<td class="textCenter boldFont">'+result[$x].SB+'</td>'+
                                            '<tr>';
            $no = $no + 1;
          }

          $total_skor = ($jmlh_K*1)+($jmlh_C*2)+($jmlh_B*3)+($jmlh_SB*4);

          if($total_skor < 18){
            var element = document.getElementById("kurang_potensial");
            element.classList.add("mystyle");
          }else if($total_skor >= 18 && $total_skor <= 27){
            var element = document.getElementById("cukup_potensial");
            element.classList.add("mystyle");
          }else{
            var element = document.getElementById("sangat_potensial");
            element.classList.add("mystyle");
          }

          $footerTable =  '<tr>'+
                            '<td class="textCenter boldFont" colspan="2">TOTAL SKOR</td>'+
                            //colspan 2
                            '<td class="textCenter boldFont">'+$total_skor.toFixed(2)+'</td>'+
                            '<td class="textCenter boldFont">'+($jmlh_K*1).toFixed(2)+'</td>'+
                            '<td class="textCenter boldFont">'+($jmlh_C*2).toFixed(2)+'</td>'+
                            '<td class="textCenter boldFont">'+($jmlh_B*3).toFixed(2)+'</td>'+
                            '<td class="textCenter boldFont">'+($jmlh_SB*4).toFixed(2)+'</td>'+
                          '</tr>'+
                          '</table>';

          $('#tableHasil').html($headerTable+$contentTable+$footerTable);
        }
      },
      error : function(xhr){

      }
    });
    printChart();
  }

  function printChart(){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('psikogram') }}/getDataChart",
      data    : {
        "id_user"	: $id_user,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){
        if(result.length > 0){
          Highcharts.chart('container', {
            title: {
                text: 'DISC Profile',
                align: 'center'
            },
            xAxis: {
              categories: ['D', 'I', 'S', 'C']
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                }
            },
            tooltip: {
              pointFormat: '<b>{series.name} : {point.y}</b><br/>',
            },
            series: result,
            responsive: {
              rules: [{
                condition: {
                  maxWidth: 500
                },
                chartOptions: {
                  legend: {
                      layout: 'horizontal',
                      align: 'center',
                      verticalAlign: 'bottom'
                  }
                }
              }]
            }
          });
        }
      },
      error : function(xhr){

      }
    });
  }
</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>Report</h1>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <label for="searchList" class="form-label">Choose Candidate</label>
          <input class="form-control" list="datalistOptions" id="searchList" placeholder="Type to search..." onkeyup="getDataTest(this)">
          <datalist id="datalistOptions">
            @foreach ($data_user as $data)
              <option data-customvalue={{ $data->id_user }} value={{ $data->name }}> {{ $data->name }}</option>
            @endforeach
          </datalist>
        </div>
        <div class="col-md-6">
          
        </div>
      </div>
    </div>
  </div>
  <hr/>
  <div class="card" id="detailTest">
    <div class="card-body">
      <div class="card text-center">
        <div class="card-header">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link disabled" id="0" data-bs-toggle="tab" href="#0_WPT">WPT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" id="1" data-bs-toggle="tab" href="#1_PAPI">PAPI</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" id="2" data-bs-toggle="tab" href="#2_DISC">DISC</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" id="3" data-bs-toggle="tab" href="#3_ENG">English</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="4" data-bs-toggle="tab" href="#REPORT">Report</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane container fade" id="0_WPT">
              <div class="row">
                <div class="col-sm-12">
                  <table class="table table-bordered" border="1" width="100%">
                    <tr class="table-primary boldFont textCenter">
                      <td colspan="3" width="80%">Result</td>
                      <td width="20%" class="textCenter">IQ (Inteligensi Umum)</td>
                    </tr>
                    <tr>
                      <td width="70%">Jumlah Pertanyaan Terjawab</td>
                      <td width="1%">:</td>
                      <td width="9%" class="boldFont textCenter"><span id="jmlPertanyaan"></span></td>
                      <td width="20%" class="boldFont textCenter" rowspan="2"><span id="hslIQ" style="font-size:50px"></span></td>
                    </tr>
                    <tr>
                      <td>Jumlah Pertanyaan Salah</td>
                      <td>:</td>
                      <td class="boldFont textCenter"><span id="jmlSalah"></span></td>
                      <!-- rowspan -->
                    </tr>
                    <tr>
                      <td>Total Skor Benar</td>
                      <td>:</td>
                      <td class="boldFont textCenter"><span id="jmlBenar"></span></td>
                      <td class="boldFont textCenter"><span id="ketHasil"></span></td>
                    </tr>
                  </table>
                </div>
              </div>
              <div id="dtl_WPT">
                <!-- javascript -->
              </div>
            </div>
            <div class="tab-pane container fade" id="1_PAPI">
              <div class="row">
                <div class="col-sm-12">
                  <div id="contentResultPAPI">
                    <!-- javascript -->
                  </div>
                </div>
              </div>
              <div id="dtl_PAPI">
                <!-- javascript -->
              </div>
            </div>
            <div class="tab-pane container fade" id="2_DISC">
              <div class="row">
                <div class="col-sm-4">
                  <div id="contentResultDISC">
                    <!-- javascript -->
                  </div>
                </div>
                <div class="col-sm-8">
                  <table class="table table-bordered" border="1" width="100%">
                    <tr class="table-primary boldFont textCenter">
                      <td colspan="3" width="100%">Result</td>
                    </tr>
                    <tr class="boldFont textCenter">
                      <td rowspan="2" width="33%" class="boldFont" style="font-size:45px;"><span id="skor_M"></span></td>
                      <td rowspan="2" width="33%" class="boldFont" style="font-size:45px;"><span id="skor_L"></span></td>
                      <td rowspan="2" width="33%" class="boldFont" style="font-size:45px;"><span id="skor_C"></span></td>
                    </tr>
                    <tr>
                      <!-- rowspan 2 -->
                      <!-- rowspan 2 -->
                      <!-- rowspan 2 -->
                    </tr>
                    <tr>
                      <td class="boldFont textCenter" style="font-style:italic;"><span id="arti_M"></span></td>
                      <td class="boldFont textCenter" style="font-style:italic;"><span id="arti_L"></span></td>
                      <td class="boldFont textCenter" style="font-style:italic;"><span id="arti_C"></span></td>
                    </tr>
                    <tr style="height: 75px;">
                      <td style="font-size:12px;"><span id="desc_M"></span></td>
                      <td style="font-size:12px;"><span id="desc_L"></span></td>
                      <td style="font-size:12px;"><span id="desc_C"></span></td>
                    </tr>
                  </table>
                </div>
              </div>
              <div id="dtl_DISC">
                <!-- javascript -->
              </div>
            </div>
            <div class="tab-pane container fade" id="3_ENG">4</div>
            <div class="tab-pane container fade" id="REPORT">
              <div class="row">
                <div class="col-sm-12 textLeft">
                  <h3>PSIKOGRAM</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-2 textLeft">
                  Nama
                </div>
                <div class="col-sm-4 textLeft" style="border-bottom: 1pt solid black">
                  : <span id="namaPeserta"></span>
                </div>

                <div class="col-sm-2 textLeft">
                  Pendidikan
                </div>
                <div class="col-sm-4 textLeft" style="border-bottom: 1pt solid black">
                  : <span id="pendidikanPeserta"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-2 textLeft">
                  Tmpt/Tgl Lahir
                </div>
                <div class="col-sm-4 textLeft" style="border-bottom: 1pt solid black">
                  : <span id="tglLahirPeserta"></span>
                </div>

                <div class="col-sm-2 textLeft">
                  Jabatan
                </div>
                <div class="col-sm-4 textLeft" style="border-bottom: 1pt solid black">
                  : <span id="jabatanPeserta"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-2 textLeft">
                  Usia
                </div>
                <div class="col-sm-4 textLeft" style="border-bottom: 1pt solid black">
                  : <span id="usiaPeserta"></span>
                </div>

                <div class="col-sm-2 textLeft">
                  Masa Kerja
                </div>
                <div class="col-sm-4 textLeft" style="border-bottom: 1pt solid black">
                  : <span id="masaKerjaPeserta"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-2 textLeft">
                  Tanggal Tes
                </div>
                <div class="col-sm-4 textLeft" style="border-bottom: 1pt solid black">
                  : <span id="tanggalTesPeserta"></span>
                </div>

                <div class="col-sm-2 textLeft">
                  Tujuan Tes
                </div>
                <div class="col-sm-4 textLeft" style="border-bottom: 1pt solid black">
                  : <span id="tujuanTesPeserta"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div id="tableHasil">
                    <!-- javascript -->
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div id="chartDISC">
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                    </figure>
                  </div>
                </div>
              </div>
              <div class="row textLeft">
                <div class="col-sm-9" style="border: 1px solid;">
                  Dominasi karakter yang muncul ketika berada di lingkungan sosial
                </div>
                <div class="col-sm-1" style="border: 1px solid;">
                  <span id="DISC_REPORT_1"></span>
                </div>
                <div class="col-sm-2" style="border: 1px solid;">
                  <span id="DISC_REPORT_1_desc"></span>
                </div>
              </div>
              <div class="row textLeft">
                <div class="col-sm-9" style="border: 1px solid;">
                  Dominasi karakter ketika berada dibawah tekanan
                </div>
                <div class="col-sm-1" style="border: 1px solid;">
                  <span id="DISC_REPORT_2"></span>
                </div>
                <div class="col-sm-2" style="border: 1px solid;">
                  <span id="DISC_REPORT_2_desc"></span>
                </div>
              </div>
              <div class="row textLeft">
                <div class="col-sm-9" style="border: 1px solid;">
                  Profile dominasi karakter asli
                </div>
                <div class="col-sm-1" style="border: 1px solid;">
                  <span id="DISC_REPORT_3"></span>
                </div>
                <div class="col-sm-2" style="border: 1px solid;">
                  <span id="DISC_REPORT_3_desc"></span>
                </div>
              </div>
              <br/>
              <div class="row" style="border: 0.5px solid;">
                <div class="row textLeft">
                  <div class="col-sm-12">
                    <h5>Gambaran Karakter Umum</h5>
                    <span id="karakter_umum"></span>
                  </div>
                </div>
                <hr/>
                <div class="row textLeft">
                  <div class="col-sm-12">
                    <h5>Positif (+)</h5>
                    <span id="kelebihan"></span>
                  </div>
                </div>
                <hr/>
                <div class="row textLeft">
                  <div class="col-sm-12">
                    <h5>Negatif (-)</h5>
                    <span id="kekurangan"></span>
                  </div>
                </div>
              </div>
              <br/>
              <div class="row textLeft">
                <h6>Rekomendasi</h6>
                <div class="col-sm-1" style="border: 0.5px solid;" id="kurang_potensial"></div>
                <div class="col-sm-5 textLeft">Kurang Potensial (< 18)</div>
                <div class="col-sm-6 textLeft">Kemampuan Bahasa Inggris</div>
              </div>
              <div class="row">
                <div class="col-sm-1 textLeft" style="border: 0.5px solid;" id="cukup_potensial"></div>
                <div class="col-sm-5 textLeft">Cukup Potensial (18 - 27)</div>
                <div class="col-sm-2 textLeft">Skor</div>
                <div class="col-sm-1 textLeft" style="border: 0.5px solid;">-</div>
              </div>
              <div class="row">
                <div class="col-sm-1 textLeft" style="border: 0.5px solid;" id="sangat_potensial"></div>
                <div class="col-sm-5 textLeft">Sangat Potensial (> 27)</div>
                <div class="col-sm-2 textLeft">Predikat</div>
                <div class="col-sm-1 textLeft" style="border: 0.5px solid;">-</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection