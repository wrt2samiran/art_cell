<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js')  }}"></script>
<script src="{{asset('assets//plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Jquery form-validate -->
<script src="{{asset('js/jquery.validate.js')}}"></script>

<script src="{{ asset('js/development-admin.js')}}"></script>


<script>
    
$('.select2').select2()
    
  $(document).on('change','#service_id',function(e){
      let totalAmount = 0;
    $("#service_id :selected").map(function(i, el) {
        totalAmount += parseInt( $(el).attr('data-object'))
    });
    $('#totalAmountText').html(totalAmount);
  
  });
  
  
  
  </script>
</body>
</html>