<footer class="main-footer">
    <strong>Copyright &copy; {{date('Y')}} .</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
    </div>
<!-- ./wrapper -->

<script type="text/javascript">
  window.baseUrl="{{URL::to('/')}}";
  window.current_locale="{{App::getLocale()}}";
</script>
<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('assets/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('assets/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>

<!-- DataTables -->
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/dist/js/adminlte.js')}}"></script>

<script src="{{asset('assets//plugins/select2/js/select2.full.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/dist/js/demo.js')}}"></script>

<!-- <script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script> -->

<!--**********important note about custom jquery validate***********-->
<!-- We modified default jquery validator in checkForm method for array name field validation got the solution from https://stackoverflow.com/questions/24670447/how-to-validate-array-of-inputs-using-validate-plugin-jquery -->
<script src="{{asset('js/jquery.validate.js')}}"></script>
<!---------------->
<script src="{{asset('assets/plugins/jquery-validation/additional-methods.min.js')}}"></script>

@if(App::getLocale()=='ar')
<script src="{{asset('assets/plugins/jquery-validation/localization/messages_ar.min.js')}}"></script>
@endif

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/development-admin.js')}}"></script>

<!-- InputMask -->

<script src="{{asset('assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>


<!-- Sweet alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<!-- <script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<!-- *****************for boostrap multiselect with search option, select all and scrollbar *************-->
<script src='{{asset("js/jquery.multiselect.js")}}'></script>
<!-- *****************for boostrap multiselect with search option, select all and scrollbar End *************-->

<script src='{{asset("js/custom-validator-method.js")}}'></script>
<script src="{{asset('assets/plugins/fancybox/fancybox.min.js')}}"></script>
<script src="{{asset('assets/dist/js/bootstrap-clockpicker.min.js')}}"></script>


<!-- *****************for chosen multiselect with search option, select all and scrollbar End *************-->
<script src="{{asset('assets/dist/js/chosen-multi-select/chosen.jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/dist/js/chosen-multi-select/docsupport/init.js')}}" type="text/javascript" charset="utf-8"></script>
<!-- *****************for chosen multiselect with search option, select all and scrollbar End *************-->

@stack('custom-scripts')
</body>
</html>


   
    
