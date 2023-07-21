<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100;200;300;400;500;600;700;800;900&display=swap">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('public/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Datatable Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('public/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{ asset('public/plugins/jqvmap/jqvmap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('public/dist/css/adminlte.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('public/plugins/daterangepicker/daterangepicker.css') }}">
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('public/plugins/summernote/summernote-bs4.min.css') }}">
<!-- theme customization by VR-->
<!-- Toaster message -->
<link rel="stylesheet" type="text/css" href="{{ asset('public/plugins/toastr/toastr.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/css/custom.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.18/sweetalert2.min.css" />
<style>
    .theme_primary_text {
        color: var(--primary-color);
    }

    .theme_primary_btn {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-circle {
        width: 33px;
        height: 33px;
        border-radius: 50%;
        padding: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .form-group .form-control {
        border: 1px solid #44444459 !important;
    }

    .error {
        color: red;
    }

    .profile-img {
        border: 3px solid #adb5bd;
        margin: 0 auto;
        padding: 3px;
        width: 100px;
        height: 100px;
    }
</style>
@stack('style')