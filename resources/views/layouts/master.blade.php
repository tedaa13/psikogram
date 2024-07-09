<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    html, body {
      margin-bottom:50px;
      padding:0;
      height:auto;
    }
    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      right 0;
      width: 100%;
      background-color: #E5E7E9;
      color: black;
      text-align: center;
      margin-top: 10px;
    }
  </style>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Psikogram</title>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.0/css/select.bootstrap5.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <link rel="icon" type="image/x-icon" href="{{asset('asset/master/assets/favicon.ico')}}" />
  <link href="{{asset('asset/master/css/styles.css')}}" rel="stylesheet" />
  <script src="{{asset('asset/master/js/scripts.js')}}"></script>
</head>
  <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> -->
  <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/select/2.0.0/js/dataTables.select.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/select/2.0.0/js/select.bootstrap5.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.highcharts.com/10/highcharts.js"></script>
  <!-- <script src="https://code.highcharts.com/modules/series-label.js"></script> -->
  <!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <!-- <script src="https://code.highcharts.com/modules/accessibility.js"></script> -->


  <!-- kalo mau ganti sidebar berikut link rekomendasi: https://dev.to/codeply/bootstrap-5-sidebar-examples-38pb -->

<body id="body">
  <div class="d-flex" style="flex-grow: 1;" id="wrapper">
    <!-- Sidebar-->
    <div class="border-end bg-white" id="sidebar-wrapper">
      <div class="sidebar-heading border-bottom bg-light">PSIKOGRAM</div>
      <div class="list-group list-group-flush">
        @if ( Auth::user()->id_role <> '2')
          <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/home">Dashboard</a>
          <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/peserta">Create User</a>
          <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/form">Create Form</a>
          <!-- <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/reportRekap">Report Recap</a> -->
          <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/report">Report Detail</a>
          <!-- <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/setting">Settings</a> -->
        @else
          <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/dashboard_user">Dashboard</a>
          <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/profile">Profile</a>
        @endif
        
      </div>
    </div>
    <!-- Page content wrapper-->
    <div id="page-content-wrapper">
      <!-- Top navigation-->
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">
          <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <!-- <li class="nav-item active"><a class="nav-link" href="#!">Home</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="#!">Link</a></li> -->
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hi, {{Auth::user()->username}}</a>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    @if(Auth::user()->id_role == '0')
                      <a class="dropdown-item">Role: Super Admin</a>
                    @elseif(Auth::user()->id_role == '1')
                      <a class="dropdown-item">Role: Administrator</a>
                    @else
                      <a class="dropdown-item">Role: Guest</a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('actionlogout')}}"><i class="bi bi-power"></i> Log Out</a>
                  </div>
                </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- Page content-->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <!-- Page content-->
      @yield('content')
    </div>
    <div class="footer">
      <p>Copyright 2024</p>
    </div>
  </div>
  
</body>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</html>