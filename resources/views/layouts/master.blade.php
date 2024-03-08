<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      background-color: #E5E7E9;
      color: black;
      text-align: center;
    }
  </style>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Aplikasi Psikogram</title>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>

  <link rel="icon" type="image/x-icon" href="{{asset('asset/master/assets/favicon.ico')}}" />
  <link href="{{asset('asset/master/css/styles.css')}}" rel="stylesheet" />
  <script src="{{asset('asset/master/js/scripts.js')}}"></script>
</head>

<body>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar-->
    <div class="border-end bg-white" id="sidebar-wrapper">
      <div class="sidebar-heading border-bottom bg-light">Psikogram</div>
      <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Dashboard</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Settings</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Profile</a>
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
                      <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hi, {{Auth::user()->name}}</a>
                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item">Role: {{Auth::user()->role}}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('actionlogout')}}"><i class="bi bi-power"></i> Log Out</a>
                      </div>
                    </li>
                </ul>
              </div>
          </div>
      </nav>
      <!-- Page content-->
      <div class="container-fluid">
          <h1 class="mt-4">Simple Sidebar</h1>
          <p>The starting state of the menu will appear collapsed on smaller screens, and will appear non-collapsed on larger screens. When toggled using the button below, the menu will change.</p>
          <p>
              Make sure to keep all page content within the
              <code>#page-content-wrapper</code>
              . The top navbar is optional, and just for demonstration. Just create an element with the
              <code>#sidebarToggle</code>
              ID which will toggle the menu when clicked.
          </p>
      </div>
    </div>
  </div>
  <div class="footer">
    <p>Copyright 2024</p>
  </div>

</body>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</html>