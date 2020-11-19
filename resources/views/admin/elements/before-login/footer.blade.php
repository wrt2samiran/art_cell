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
    
    
  

  $("#add_new_file").on("click", function () {
    let random_string = String(Math.random(10)).substring(2,14); 
    var row=`<div class="row mt-1 files_row">`;
    row += `<div class="col-md-6"><input placeholder="Title" class="form-control file_title_list"  id="title_`+random_string+`" name="title[]" type="text"></div>`;
    row += `<div class="col-md-5">
      <select class="form-control" id="service_id_`+random_string+`" name="service_id[]" style="width: 100%;">
                        <option value="">Select Service</option>
                        @if (count($serviceList))
                                @foreach ($serviceList as $service)
                                    <option value="{{$service->id}}" data-object="{{ json_encode($service['service']['price']) }}">{{$service->service_name}}</option>
                                @endforeach
                        @endif
                      </select>
      
    </div>`;
    row += `<div class="col-md-1"><button data-delete_url="" type="button" class="btn btn-danger files_row_del_btn"><i class="fa fa-trash" aria-hidden="true"></i></button></div>`;
    row +=`</div>`;
    $("#files_container").append(row);

    $('#title_'+random_string).rules("add", {
       required: true,
       maxlength: 100,
       messages: {
         required: "Enter title",
         maxlength: "Maximum 100 characters allowed",
       }
    });







});

$(document).on('click', '.files_row_del_btn', function(){  
    
    var element_to_remove=$(this).closest(".files_row");
    element_to_remove.remove();
    
});
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