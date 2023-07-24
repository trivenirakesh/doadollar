 <!-- jQuery -->
 <script src="{{ asset('public/plugins/jquery/jquery.min.js') }}"></script>
 <!-- Jquery Validation  -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
 <!-- DataTables  & Plugins -->
 <script src="{{ asset('public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
 <script src="{{ asset('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
 <script src="{{ asset('public/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
 <script src="{{ asset('public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
 <script src="{{ asset('public/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
 <script src="{{ asset('public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
 <script src="{{ asset('public/plugins/jszip/jszip.min.js') }}"></script>
 <script src="{{ asset('public/plugins/pdfmake/pdfmake.min.js') }}"></script>
 <script src="{{ asset('public/plugins/pdfmake/vfs_fonts.js') }}"></script>
 <script src="{{ asset('public/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
 <script src="{{ asset('public/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
 <script src="{{ asset('public/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
 <!-- DataTables  & Plugins -->
 <!-- jQuery UI 1.11.4 -->
 <script src="{{ asset('public/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
 <script>
   $.widget.bridge('uibutton', $.ui.button)
 </script>
 <!-- Bootstrap 4 -->
 <script src="{{ asset('public/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <!-- ChartJS -->
 <script src="{{ asset('public/plugins/chart.js/Chart.min.js') }}"></script>
 <!-- Sparkline -->
 <script src="{{ asset('public/plugins/sparklines/sparkline.js') }}"></script>
 <!-- JQVMap -->
 <script src="{{ asset('public/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
 <script src="{{ asset('public/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
 <!-- jQuery Knob Chart -->
 <script src="{{ asset('public/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
 <!-- daterangepicker -->
 <script src="{{ asset('public/plugins/moment/moment.min.js') }}"></script>
 <script src="{{ asset('public/plugins/daterangepicker/daterangepicker.js') }}"></script>
 <!-- Tempusdominus Bootstrap 4 -->
 <script src="{{ asset('public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
 <!-- Summernote -->
 <script src="{{ asset('public/plugins/summernote/summernote-bs4.min.js') }}"></script>
 <!-- overlayScrollbars -->
 <script src="{{ asset('public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
 <!-- AdminLTE App -->
 <script src="{{ asset('public/dist/js/adminlte.js') }}"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.18/sweetalert2.min.js"></script>
 <!-- AdminLTE for demo purposes -->
 <!-- <script src="{{ asset('public/dist/js/demo.js') }}"></script> -->
 <!-- Toaster message -->
 <script src="{{ asset('public/plugins/toastr/toastr.min.js') }}"></script>
 <script src="{{ asset('public/dist/js/pages/backoffice/common.js') }}"></script>
 @stack('script')