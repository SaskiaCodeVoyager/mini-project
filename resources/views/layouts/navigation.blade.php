<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Tiny Dashboard - A Bootstrap Dashboard Template</title>

    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">

    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/uppy.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.steps.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}">

    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app-light.css') }}" id="lightTheme">
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}" id="darkTheme" disabled>

  </head>
  <body class="vertical light">
    <div class="wrapper">

      <nav class="topnav navbar navbar-light">
        <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
          <i class="fe fe-menu navbar-toggler-icon"></i>
        </button>
        <ul class="navbar-nav flex-fill w-100 mb-2">
          <li class="nav-item">
            <a href="#dashboard" data-toggle="collapse" aria-expanded="false" class="nav-link">
              <i class="fe fe-home fe-16"></i>
              <span class="ml-3 item-text">Dashboard</span><span class="sr-only">(current)</span>
            </a>
          </li>
        </ul>

        <li class="nav-item">
          <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fe fe-log-out fe-16"></i>
            <span class="ml-3 item-text">Logout</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>

        <p class="text-muted nav-heading mt-4 mb-1">
          <span>Apps</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">

          <li class="nav-item w-100">
            <a class="nav-link" href="{{ route('jadpik.index') }}">
              <i class="fe fe-calendar fe-16"></i>
              <span class="ml-3 item-text">Jadpik</span>
            </a>
          </li>

          <!-- Only show "Tahap" if user is admin -->
          @if(auth()->user()->role === 'admin')
            <li class="nav-item">
              <a href="{{ route('tahap.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
                <i class="fe fe-book fe-16"></i>
                <span class="ml-3 item-text">Tahap</span>
              </a>
            </li>
          @endif

          <!-- Only show "Divisi" if user is admin -->
          @if(auth()->user()->role === 'admin')
            <li class="nav-item">
              <a href="{{ route('divisi.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
                <i class="fe fe-user fe-16"></i>
                <span class="ml-3 item-text">Divisi</span>
              </a>
            </li>
          @endif

          <li class="nav-item">
            <a href="{{ route('absens.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
              <i class="fe fe-user fe-16"></i>
              <span class="ml-3 item-text">Absensi</span>
            </a>
          </li>

          <li class="nav-item dropdown">
            <a href="{{ route('projects.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
              <i class="fe fe-pie-chart fe-16"></i>
              <span class="ml-3 item-text">Project</span>
            </a>
          </li>

          <li class="nav-item dropdown">
            <a href="{{ route('jurnals.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
              <i class="fe fe-folder fe-16"></i>
              <span class="ml-3 item-text">Jurnal</span>
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </body>
</html>
