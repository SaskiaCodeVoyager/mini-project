<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Tiny Dashboard - A Bootstrap Dashboard Template</title>

    <!-- Simple Bar CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">

    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

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

    <!-- Custom CSS for Active State -->
    <style>
      .navbar-nav .nav-item.active .nav-link {
        background-color: #007bff;  /* Blue */
        color: white;               /* White text */
      }

      .navbar-nav .nav-item.active .nav-link i {
        color: white;               /* White icon */
      }

      /* Profile Section Styling */
      .profile-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;  /* Rata tengah untuk teks */
        margin-top: 20px;
      }

      /* Profile Details Styling */
      .profile-info span {
        font-size: 14px;
        text-align: center;
        margin-bottom: 10px;  /* Menambahkan jarak antar teks */
      }

      /* Profile Button Styling */
      .profile-info .nav-item {
        margin-top: 15px;
      }

      /* Mobile Responsiveness */
      @media (max-width: 768px) {
        .navbar-nav {
          flex-direction: column;
        }
      }
    </style>
  </head>
  <body class="vertical light">
    <div class="wrapper">
      <nav class="topnav navbar navbar-light">
        <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
          <i class="fe fe-menu navbar-toggler-icon"></i>
        </button>

        <!-- Menu Section -->
        <ul class="navbar-nav flex-fill w-100 mb-2">
          <!-- Dashboard Menu -->
          <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="#dashboard" data-toggle="collapse" aria-expanded="false" class="nav-link">
              <i class="fe fe-home fe-16"></i>
              <span class="ml-3 item-text">Dashboard</span>
            </a>
          </li>
        </ul>

        <p class="text-muted nav-heading mt-4 mb-1">
          <span>Apps</span>
        </p>

        <!-- Other Menu Items -->
        <ul class="navbar-nav flex-fill w-100 mb-2">
          <li class="nav-item {{ request()->is('jadpik*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('jadpik.index') }}">
              <i class="fe fe-calendar fe-16"></i>
              <span class="ml-3 item-text">Jadpik</span>
            </a>
          </li>

          @if(auth()->user()->role === 'admin')
            <li class="nav-item {{ request()->is('tahap*') ? 'active' : '' }}">
              <a href="{{ route('tahap.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
                <i class="fe fe-book fe-16"></i>
                <span class="ml-3 item-text">Tahap</span>
              </a>
            </li>

            <li class="nav-item {{ request()->is('divisi*') ? 'active' : '' }}">
              <a href="{{ route('divisi.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
                <i class="fe fe-user fe-16"></i>
                <span class="ml-3 item-text">Divisi</span>
              </a>
            </li>
          @endif

          <li class="nav-item {{ request()->is('absens*') ? 'active' : '' }}">
            <a href="{{ route('absens.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
              <i class="fe fe-user fe-16"></i>
              <span class="ml-3 item-text">Absensi</span>
            </a>
          </li>

          <li class="nav-item {{ request()->is('projects*') ? 'active' : '' }}">
            <a href="{{ route('projects.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
              <i class="fe fe-pie-chart fe-16"></i>
              <span class="ml-3 item-text">Project</span>
            </a>
          </li>

          <li class="nav-item {{ request()->is('jurnals*') ? 'active' : '' }}">
            <a href="{{ route('jurnals.index') }}" data-toggle="collapse" aria-expanded="false" class="nav-link">
              <i class="fe fe-folder fe-16"></i>
              <span class="ml-3 item-text">Jurnal</span>
            </a>
          </li>
        </ul>

        <!-- Profile Info Below Menu -->
        <div class="profile-info">
          <span class="font-weight-bold d-block">{{ auth()->user()->username }}</span>
          <span class="text-muted d-block mb-3">{{ auth()->user()->email }}</span>
          <div class="nav-item">
            <a href="#" class="nav-link text-white bg-primary py-2 px-4 rounded" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fe fe-log-out fe-16"></i>
              <span class="ml-3 item-text">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </nav>
    </div>
  </body>
</html>
