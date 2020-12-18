 

@extends('frontend.layouts.blank-template')

@section('content')

<div class="login-logo quotation-page">  
  <div class="admin-logo" style="padding: 30px 0 15px;"><img src="{{asset('assets/dist/img/OSOOL_logo.png')}}" alt="Logo" style="width: 250px;"></div>
</div>
<div class="container">
  <div class="quotation-form-content">
    <div class="row">

      <div class="col-md-12">
        <div >
          @if(Session::has('quotation_success'))
              <div class="alert alert-success alert-dismissable __web-inspector-hide-shortcut__">
                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                  {{ Session::get('quotation_success') }}
              </div>
          @endif
          @if(Session::has('quotation_error'))
              <div class="alert alert-danger alert-dismissable">
                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                  {{ Session::get('quotation_error') }}
              </div>
          @endif
        </div>

      <div >
        <div class="d-flex justify-content-between">
          <div></div>
          <div>
            <select class="form-control" id="language" onchange="onLanguageChange(this.value)">
              <option value="en" {{(App::getLocale()=='en')?'selected':''}}>English</option>
              <option value="ar" {{(App::getLocale()=='ar')?'selected':''}}>Arabic</option>
            </select> 
          </div>

        </div>
        <div class="quotation-header">
           <p class="submit-quotation">{{__('submit_quotation_page.general_sentences.submit_quotation')}}</p>
           <p class="please-fillup">{{__('submit_quotation_page.general_sentences.please_fillup')}}</p>
        </div>
        <div class="quotation-form-holder">

          <form  method="POST" id="quotetion_submit_form" action="{{route('frontend.store_quotation')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="property_id">{{__('submit_quotation_page.input_labels.first_name')}} <span class="error">*</span></label>
                <input type="text" placeholder="{{__('submit_quotation_page.input_placeholders.first_name')}}" class="form-control" id="first_name" name="first_name">
                @if($errors->has('first_name'))
                  <span class="text-danger">{{$errors->first('first_name')}}</span>
                @endif
              </div>
              <div class="col-md-6 form-group">
                <label for="property_id">{{__('submit_quotation_page.input_labels.last_name')}} <span class="error">*</span></label>
                <input type="text" placeholder="{{__('submit_quotation_page.input_placeholders.last_name')}}" class="form-control" id="last_name" name="last_name">
                @if($errors->has('last_name'))
                  <span class="text-danger">{{$errors->first('last_name')}}</span>
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 form-group">
                <label for="property_id">{{__('submit_quotation_page.input_labels.email')}}</label>
                <input type="text" placeholder="{{__('submit_quotation_page.input_placeholders.email')}}" class="form-control" id="email" name="email">
                @if($errors->has('email'))
                  <span class="text-danger">{{$errors->first('email')}}</span>
                @endif
              </div>
              <div class="col-md-6  form-group">
                <label for="property_id">{{__('submit_quotation_page.input_labels.contact_number')}}<span class="error">*</span></label>
                <input type="text" placeholder="{{__('submit_quotation_page.input_placeholders.contact_number')}}" class="form-control" id="contact_number" name="contact_number">
                @if($errors->has('contact_number'))
                  <span class="text-danger">{{$errors->first('contact_number')}}</span>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-6  form-group">
                <label for="country_id">{{__('submit_quotation_page.input_labels.state')}}<span class="error">*</span></label>
                <select class="form-control " id="state_id" name="state_id" style="width: 100%;">
                  <option value="">{{__('submit_quotation_page.input_placeholders.state')}}</option>
                  @forelse($states as $state)
                    <option data-cities="{{$state->cities}}" value="{{$state->id}}" >{{$state->name}}</option>
                  @empty
                  <option value="">No State Found</option>
                  @endforelse
                </select>
                <div id="state_id_error"></div>
                @if($errors->has('state_id'))
                    <span class="text-danger">{{$errors->first('state_id')}}</span>
                @endif
              </div>
              <div class="col-md-6  form-group">
                <label for="name">{{__('submit_quotation_page.input_labels.city')}}<span class="error">*</span></label>
                <select class="form-control" id="city_id" name="city_id" style="width: 100%;">
                  <option value="">{{__('submit_quotation_page.input_placeholders.city')}}</option>
                  @forelse($cities as $city)
                    <option value="{{$city->id}}" >{{$city->name}}</option>
                  @empty
                  <option value="">No City Found</option>
                  @endforelse
                </select>
                <div id="city_id_error"></div>
                @if($errors->has('city_id'))
                  <span class="text-danger">{{$errors->first('city_id')}}</span>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-12  form-group">
                <label for="landmark">{{__('submit_quotation_page.input_labels.landmark_or_location')}}<span class="error">*</span></label>
                <input type="text" placeholder="{{__('submit_quotation_page.input_placeholders.landmark_or_location')}}" class="form-control" id="landmark" name="landmark">
                    @if($errors->has('landmark'))
                      <span class="text-danger">{{$errors->first('landmark')}}</span>
                    @endif
              </div>
            </div>


            <div class="row">
              <div class="col-md-12  ">
                <div id="map_canvas" style="height:400px"></div>
              </div>
              <div class="col-md-12">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <input type="hidden" name="placeholder_address" id="placeholder_address">
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-3 form-group">
                <label for="contract_duration">{{__('submit_quotation_page.input_labels.contact_duration')}}<span class="error">*</span></label>
                <input type="number" placeholder="{{__('submit_quotation_page.input_placeholders.contact_duration')}}"  min="1" value="1" step="1" class="form-control" id="contract_duration" name="contract_duration">
                @if($errors->has('contract_duration'))
                <span class="text-danger">{{$errors->first('contract_duration')}}</span>
                @endif
              </div>
              <div class="col-md-3 form-group">
                <label for="contract_duration_type">&nbsp;</label>
                <select class="form-control " name="contract_duration_type" id="contract_duration_type">
                  @if(App::getLocale()=='ar')
                  <option value="Year(s)"> سنوات) </option>
                  <option value="Month(s)"> الشهور) </option>
                  <option value="Week(s)"> أسبوع (أسابيع)</option>
                  <option value="Day(s)"> أيام)</option>
                  @else
                  <option value="Year(s)"> Year(s) </option>
                  <option value="Month(s)"> Month(s) </option>
                  <option value="Week(s)"> Week(s)</option>
                  <option value="Day(s)"> Day(s)</option>
                  @endif

                </select>
              </div>

              <div class="col-md-6  form-group">
                <label for="name">{{__('submit_quotation_page.input_labels.property_type')}}<span class="error">*</span></label>
                <select class="form-control" id="property_type_id" name="property_type_id" style="width: 100%;">
                  <option value="">{{__('submit_quotation_page.input_placeholders.property_type')}}</option>
                  @forelse($property_types as $property_type)
                    <option value="{{$property_type->id}}" >{{$property_type->type_name}}</option>
                  @empty
                  <option value="">No Property Type Found</option>
                  @endforelse
                </select>
                <div id="property_type_id_error"></div>
                @if($errors->has('property_type_id'))
                  <span class="text-danger">{{$errors->first('property_type_id')}}</span>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group required">
                  <label>{{__('submit_quotation_page.input_labels.service_required')}}</label>
                  <div>
                    <button type="button" id="add_service" class="btn btn-outline-success"><i class="fa fa-plus"></i>&nbsp;{{__('submit_quotation_page.general_sentences.add_service')}}</button>
                  </div>
                </div>
              </div>
            </div>  
            <div class="row">
              <div class="col-md-12">
                <div id="services_container">

                </div>
              </div>
            </div>
            <div class="row mt-3">

              <div class="col-md-6">
                  <div class="form-group ">
                    <label for="images">{{__('submit_quotation_page.input_labels.images')}}</label>
                    <input type="file" class="form-control" name="images[]" id="images" multiple="true" accept="image/jpg,image/jpeg,image/gif">
                    <span class="text-muted">{{__('submit_quotation_page.general_sentences.image_help')}}</span>
                    @if($errors->has('images'))
                    <span class="text-danger">{{$errors->first('images')}}</span>
                    @endif
                  </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="no_of_resources">{{__('submit_quotation_page.input_labels.no_of_resources')}} </label>
                  <input type="number" min="1" step="1" class="form-control" placeholder="{{__('submit_quotation_page.input_placeholders.no_of_resources')}}" id="no_of_resources" name="no_of_resources">
                  @if($errors->has('no_of_resources'))
                    <span class="text-danger">{{$errors->first('no_of_resources')}}</span>
                  @endif
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="details">{{__('submit_quotation_page.input_labels.description')}}</label>
                  <textarea placeholder="{{__('submit_quotation_page.input_placeholders.description')}}" class="form-control float-right" name="details" id="details">{{old('details')}}</textarea>
                </div>
              </div>
              
            </div>

            <div class="quoatation-message mt-2 mb-2" id="disclaimer" style="display: none;">
            
            <p>{{__('submit_quotation_page.general_sentences.disclaimer')}}</p>
            <p>{{__('submit_quotation_page.general_sentences.total_amount')}}: {{Helper::getSiteCurrency()}}<span id="total_amount">0.00</span></p>
            </div>

            <div class="row mt-2">
              <div class="col-md-12">
                <button type="submit" class="btn btn-success">{{__('submit_quotation_page.general_sentences.submit')}}</button> 
              </div>
              
            </div>
          </form>

        </div>

      </div>



      </div>

    </div>
  </div>
</div> 
@endsection

@push('custom-scripts')

<script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_map.key')}}&sensor=false&libraries=places"></script>

<script>

jQuery.validator.addMethod("mustAutocompleteAddress", function (value, element) {
    
    var placeholder_address=$('#placeholder_address').val();

    if(value){

      if(placeholder_address){
        return placeholder_address==value;
      }
      
      return true;
    } else {
        return true;
    }
},"{{__('submit_quotation_page.validation_messages.landmark.accurate_address')}}");



var geocoder;
var map;
var marker;
var infowindow = new google.maps.InfoWindow({
  size: new google.maps.Size(150, 50)
});



google.maps.event.addDomListener(window, "load", initialize);

function initialize() {
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(-34.397, 150.644);
  var mapOptions = {
    zoom: 8,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
  google.maps.event.addListener(map, 'click', function() {
    infowindow.close();
  });
}


function codeAddress(address) {
  
  geocoder.geocode({
    'address': address
  }, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      if (marker) {
        marker.setMap(null);
        if (infowindow) infowindow.close();
      }
      marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: results[0].geometry.location
      });

      google.maps.event.addListener(marker, 'dragend', function() {
        geocodePosition(marker.getPosition());
      });
      google.maps.event.addListener(marker, 'click', function() {
        if (marker.formatted_address) {
          infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
        } else {
          infowindow.setContent(address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
        }
        infowindow.open(map, marker);
      });
      google.maps.event.trigger(marker, 'click');
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      marker.formatted_address = responses[0].formatted_address;

      $('#landmark').val(responses[0].formatted_address);
      $('#placeholder_address').val(responses[0].formatted_address);

    } else {
      marker.formatted_address = 'Cannot determine address at this location.';

        $('#latitude').val('');
        $('#longitude').val('');
        $('#landmark').val('');
        $('#placeholder_address').val('');

    }
    infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
    infowindow.open(map, marker);
  });
}


  google.maps.event.addDomListener(window, 'load', function () {
        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('landmark'));

        autocomplete.addListener("place_changed", () => {
          //infowindow.close();
 
          const place = autocomplete.getPlace();
          
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }else{

            var lat=place.geometry.location.lat();
            var lng=place.geometry.location.lng();

            $('#latitude').val(lat);
            $('#longitude').val(lng);

            
            
            //$('#placeholder_address').val(place.formatted_address);
            $('#placeholder_address').val($('#landmark').val());

            map.setCenter(place.geometry.location);
            if (marker) {
              marker.setMap(null);
              if (infowindow) infowindow.close();
            }
            marker = new google.maps.Marker({
              map: map,
              draggable: true,
              position: place.geometry.location
            });

            google.maps.event.addListener(marker, 'dragend', function() {
              geocodePosition(marker.getPosition());
              
              $('#latitude').val(marker.getPosition().lat());
              $('#longitude').val(marker.getPosition().lng());

            });
            google.maps.event.addListener(marker, 'click', function() {
              if (marker.formatted_address) {
                infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
              } else {
                infowindow.setContent(place.name + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
              }
              infowindow.open(map, marker);
            });
            google.maps.event.trigger(marker, 'click');
          }

          //codeAddress(place.formatted_address)

        });
  });


  $("#add_service").on("click", function () {
    let random_string = String(Math.random(10)).substring(2,14); 
    var row=`<div class="row mt-1 service_row">`;
    row += `<div class="col-md-6 form-group"><input placeholder="{{__('submit_quotation_page.input_placeholders.work_details')}}" class="form-control"  id="work_details_`+random_string+`" name="work_details[]" type="text"></div>`;
    row += `<div class="col-md-5 form-group">
      <select class="form-control service-list" id="service_id_`+random_string+`" name="service_id[]" style="width: 100%;">
        <option value="">"{{__('submit_quotation_page.input_placeholders.service')}}"</option>
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
        placeholder:"{{__('submit_quotation_page.input_placeholders.service')}}",
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
      placeholder:"{{__('submit_quotation_page.input_placeholders.city')}}",
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
                maxlength: 150,
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
                mustAutocompleteAddress:true
            },
            contract_duration: {
                required: true,
            },
            no_of_resources:{
              number:true,
              maxlength: 100,
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
                required:  "{{__('submit_quotation_page.validation_messages.first_name.required')}}",
                minlength: "{{__('submit_quotation_page.validation_messages.minlength.required')}}",
                maxlength: "{{__('submit_quotation_page.validation_messages.maxlength.required')}}"
            },
            last_name: {
                required:  "{{__('submit_quotation_page.validation_messages.last_name.required')}}",
                minlength: "{{__('submit_quotation_page.validation_messages.last_name.minlength')}}",
                maxlength: "{{__('submit_quotation_page.validation_messages.last_name.maxlength')}}"
            },
            email: {
                required: "{{__('submit_quotation_page.validation_messages.email.required')}}",
                maxlength: "{{__('submit_quotation_page.validation_messages.email.maxlength')}}"
            },
            contact_number: {
                required: "{{__('submit_quotation_page.validation_messages.contact_number.required')}}",
                number:"{{__('submit_quotation_page.validation_messages.contact_number.number')}}"
            },
            state_id: {
                required: "{{__('submit_quotation_page.validation_messages.state.required')}}",
            },
            city_id: {
                required: "{{__('submit_quotation_page.validation_messages.city.required')}}",
            },
            landmark: {
                required: "{{__('submit_quotation_page.validation_messages.landmark.required')}}",
            },
            contract_duration: {
                required: "{{__('submit_quotation_page.validation_messages.contract_duration.required')}}d",
            },
            property_type_id:{
               required: "{{__('submit_quotation_page.validation_messages.property_type.required')}}",
            },
            'work_details[]':{
                required: "{{__('submit_quotation_page.validation_messages.work_details.required')}}",
                maxlength: "{{__('submit_quotation_page.validation_messages.work_details.maxlength')}}",
            },
            'service_id[]':{
                required: "{{__('submit_quotation_page.validation_messages.service.required')}}",
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
    placeholder:"{{__('submit_quotation_page.input_placeholders.state')}}",
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
    placeholder:"{{__('submit_quotation_page.input_placeholders.city')}}",
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
      placeholder:"{{__('submit_quotation_page.input_placeholders.property_type')}}",
      "language": {
         "noResults": function(){
             return "No property type found";
         }
      },
      escapeMarkup: function(markup) {
        return markup;
      },
  });


$('#images').on('change',function(){
    
    var files = document.getElementById("images").files;

    if(files.length>3){
        reset($('#images'));
        swal('You can upload maximum 3 images');
    }else{
        var file_size_error=false;
        var file_type_error=false;
        for (var i = 0; i < files.length; i++)
        {
            var file_size_in_kb=(files[i].size/1024);
            var file_type= files[i].type;

            if(file_size_in_kb>2048){
               file_size_error=true; 
            }

            var allowed_file_types=[
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            ];

            if(!allowed_file_types.includes(file_type)){
                file_type_error=true;
            }

        }

        if(file_size_error==true || file_type_error==true){
            reset($('#images'));

            var error_message='';

            if(file_size_error==true && file_type_error==true){
                error_message="Please upload only JPG/JPEG/PNG/GIF files of max size 2mb";
            }else if(file_size_error==true && file_type_error==false){
                error_message="File size should not be more than 2 mb";
            }else{
                error_message="Please upload only JPG/JPEG/PNG/GIF files";
            }

            swal(error_message);

        }
    }
});
  
/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}

</script>
@endpush


