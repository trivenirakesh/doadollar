<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{config('app.name')}}{{isset($title)? " | $title":''}}</title>
  @include('admin.layouts.styles')
</head>

<body class="hold-transition sidebar-mini cm-layout layout-fixed layout-navbar-fixed layout-footer-fixed">
  <input type="hidden" value="{{ URL::to('/') }}" id="appurl">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="{{ asset('public/dist/img/logo.png') }}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <!-- <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index3.html" class="nav-link">Home</a>
        </li> -->
        <li class="nav-item d-none d-sm-inline-block">
          <!-- <a href="#" class="nav-link">Contact</a> -->
          <!-- GlobalSearch Form -->
          <div class="form-inline cm-global-search">
            <div class="input-group" data-widget="sidebar-search">
              <input class="form-control form-control-sidebar" type="search" placeholder="Search…" aria-label="Search">
              <i class="fas fa-search fa-fw"></i>
            </div>
          </div>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <!-- <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li> -->

        <li class="nav-item">
          <!-- <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a> -->
          <!-- header user panel (optional) -->
          <div class="user-panel d-flex">
            <div class="image">
              <img src="{{ asset('public/dist/img/avatar.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="info">
              <a href="#" class="d-block">@if(!empty(session('entity_name'))) {{ session('entity_name')}} @endif
                <!-- <p>Super Admin</p>  -->
              </a>
            </div>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin.layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      @yield('content')
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="d-none d-sm-block text-center">
        © Copyright 2023. Powered by <span class="text-bold">DoADollar</strong>
      </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  @include('admin.layouts.modals')
  @include('admin.layouts.script')
  @include('admin.layouts.messages')
</body>

</html>