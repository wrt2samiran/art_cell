<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js')  }}"></script>
<script src="{{asset('assets//plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Jquery form-validate -->
<script src="{{asset('js/jquery.validate.js')}}"></script>

<script src="{{ asset('js/development-admin.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOAl0P8rnQSpLJlHq4Y12J9e9IGHpvIqk&sensor=false&libraries=places"></script>

<script>

  $("#add_service").on("click", function () {
    let random_string = String(Math.random(10)).substring(2,14); 
    var row=`<div class="row mt-1 service_row">`;
    row += `<div class="col-md-6"><input placeholder="Work details in short" class="form-control"  id="work_details_`+random_string+`" name="work_details[]" type="text"></div>`;
    row += `<div class="col-md-5">
      <select class="form-control service-list" id="service_id_`+random_string+`" name="service_id[]" style="width: 100%;">
        <option value="">Select Service</option>
        @if (count($services))
                @foreach ($services as $service)
                    <option value="{{$service->id}}"  data-price="{{number_format($service->price, 2, '.', '')}}">{{$service->service_name}} ({{$service->currency}}{{number_format($service->price, 2, '.', '')}})</option>
                @endforeach
        @endif
      </select>
      <div id="service_id_`+random_string+`_error"></div>
    </div>`;
    row += `<div class="col-md-1"><button  type="button" class="btn btn-danger service_row_del_btn"><i class="fa fa-trash" aria-hidden="true"></i></button></div>`;
    row +=`</div>`;
    $("#services_container").append(row);

    $('.service-list').select2({
        theme: 'bootstrap4',
        placeholder:'Select service',
        "language": {
           "noResults": function(){
               return "No service found";
           }
        },
        escapeMarkup: function(markup) {
          return markup;
        },
    });

});

$(document).on('click', '.service_row_del_btn', function(){  
    
    var element_to_remove=$(this).closest(".service_row");
    element_to_remove.remove();

    var service_rows=$('.service_row').length;

    if(service_rows>0){
      $('#disclaimer').show();

      let totalAmount = 0;
      $(".service-list :selected").map(function(i, el) {
          totalAmount += parseFloat( $(el).attr('data-price'))
      });
      
      totalAmount=parseFloat(totalAmount);

      $('#total_amount').html(totalAmount.toFixed(2));
    }else{
      $('#disclaimer').hide();
    }


    
});

$(document).on('change','.service-list',function(e){

    $('#disclaimer').show();

    let totalAmount = 0;
    $(".service-list :selected").map(function(i, el) {
        totalAmount += parseFloat( $(el).attr('data-price'))
    });
    
    totalAmount=parseFloat(totalAmount);

    $('#total_amount').html(totalAmount.toFixed(2));
  
});



$(document).on('change','#state_id',function(e){

  // var cities=$("#state_id").select2().find(":selected").data("cities");
  var cities=$('#state_id').find(":selected").data("cities");
  
  var options=`<option value="">Select City</option>`;
  if(cities.length){
    for (var i = 0; i < cities.length; i++) {
      
      options+=`<option value="`+cities[i].id+`">`+cities[i].name+`</option>`
    }
  }

  $('#city_id').html(options);

  $('#city_id').select2({
      theme: 'bootstrap4',
      placeholder:'Select city',
      "language": {
         "noResults": function(){
             return "No city found";
         }
      },
      escapeMarkup: function(markup) {
        return markup;
      },
  });


});


$(document).ready(function () {


  google.maps.event.addDomListener(window, 'load', function () {
          var places = new google.maps.places.Autocomplete(document.getElementById('landmark'));
          google.maps.event.addListener(places, 'place_changed', function () {

          });
  });


    $("#quotetion_submit_form").validate({
        rules: {
            
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 100,
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 100,
            },
            email: {
                email: true,
            },
            contact_number: {
                required: true,
            },
            
            state_id: {
                required: true,
            },
            city_id: {
                required: true,
            },
            landmark: {
                required: true,
            },
            contract_duration: {
                required: true,
            },
            property_type_id:{
                required: true,
            },
            'work_details[]':{
                required: true,
                maxlength: 250,
            },
            'service_id[]':{
                required: true,
            }
           
        },
        messages: {
           
            first_name: {
                required:  "First name is required",
                minlength: "First name should have 2 characters",
                maxlength: "First name should not be more then 100 characters"
            },
            last_name: {
                required:  "Last name is required",
                minlength: "Last name should have 2 characters",
                maxlength: "Last name should not be more then 100 characters"
            },
            email: {
                required: "Email is required",
            },
            contact_number: {
                required: "Phone number is required",
                number:true
            },
            state_id: {
                required: "State is required",
            },
            city_id: {
                required: "City is required",
            },
            landmark: {
                required: "Landmark is required",
            },
            contract_duration: {
                required: "Contract duration is required",
            },
            property_type_id:{
               required: "Select proeprty type",
            },
            'work_details[]':{
                required: 'Enter work details in short',
                maxlength: 'Maximum 250 characters allowed',
            },
            'service_id[]':{
                required: 'Select service',
            }

        },
        errorPlacement: function (error, element) {

            error.addClass('invalid-feedback');
            if(element.attr('name')=="service_id[]"){
              
              var element_id=element.attr('id');
              error.appendTo($('#'+element_id+'_error'));
            }
            else if(element.attr('name')=="property_type_id"){
              error.appendTo($('#property_type_id_error'));
            }
            else if(element.attr('name')=="city_id"){
              error.appendTo($('#city_id_error'));
            }
            else if(element.attr('name')=="state_id"){
              error.appendTo($('#state_id_error'));
            }
            else{
              error.insertAfter(element);
            }
            
        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');    
        },
        submitHandler: function(form) {
            $.LoadingOverlay("show");
            form.submit();
        }
    });

});


$('#state_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select state',
    "language": {
       "noResults": function(){
           return "No state found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


$('#city_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select city',
    "language": {
       "noResults": function(){
           return "No city found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


$('.service-list').select2({
    theme: 'bootstrap4',
    placeholder:'Select service',
    "language": {
       "noResults": function(){
           return "No service found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

  $('#property_type_id').select2({
      theme: 'bootstrap4',
      placeholder:'Select property type',
      "language": {
         "noResults": function(){
             return "No property type found";
         }
      },
      escapeMarkup: function(markup) {
        return markup;
      },
  });

  
</script>
</body>
</html>