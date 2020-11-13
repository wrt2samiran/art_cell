$('#service').select2({
    theme: 'bootstrap4',
    placeholder:'Select service',
    "language": {
       "noResults": function(){
           return "No Service Found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#service_type').select2({
    theme: 'bootstrap4',
    placeholder:'Select service type',
    "language": {
       "noResults": function(){
           return "No Service Type Found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#frequency_type').select2({
    theme: 'bootstrap4',
    placeholder:'Select frequency type',
    "language": {
       "noResults": function(){
           return "No frequency type found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


$("#add_service_form").validate({
    rules: {
        service:{
            required: true,
        },
        service_type:{
            required: true
        },
        number_of_time_can_used:{
            number:true
        },
        frequency_type:{
            required: true, 
        },
        frequency_number:{
            required: true, 
            number:true
        },
        service_price:{
            required: true, 
            number:true
        }
    },
    messages: {
        service:{
            required:  "Select service",
        },
        service_type:{
            required:  "Select service type",
        },
        service_price:{
            required:  "Enter service price",
        },
    },
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        error.insertAfter(element);
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


$('#service_type').on('change',function(){
  var service_type=$(this).val();
  if(service_type=='Maintenance'){
    $('#number_of_time_can_used_holder').hide();
    $('#number_of_time_can_used').val('');
    $('#frequency_type_holder').show();
    $('#frequency_number_holder').show();
    $('#date_time_row').show();

  }else if(service_type=='On Demand') {
    $('#number_of_time_can_used_holder').show();
    $('#frequency_type_holder').hide();
    $('#frequency_number').val('');
    $('#frequency_number_holder').hide();
    $('#date_time_row').hide();
  }else{
    $('#number_of_time_can_used').val('');
    $('#frequency_number').val('');
    $('#frequency_type_holder').hide();
    $('#frequency_number_holder').hide();
    $('#number_of_time_can_used_holder').hide();
    $('#date_time_row').hide();
  }

  if(service_type=='Free'){
    $('#service_price').val('0');
    $('#service_price').prop('disabled', true);
  }else{
    $('#service_price').prop('disabled', false);

    var service_price=$('#service').find(":selected").data("service_price");

    if(service_price){

      $('#service_price').val(service_price);
    }else{
      $('#service_price').val('');
    }
    
  }

});



$('#frequency_type').on('change',function(){

  var frequency_type_name=$('#frequency_type').find(":selected").data("type_name");

  if(frequency_type_name=='Weekly'){
    $('#weekly_day_container').show();
  }else{
    $('#weekly_day_container').hide();
  }
  
});

$('#service').on('change',function(){

  var service_price=$('#service').find(":selected").data("service_price");

  var service_type=$('#service_type').find(":selected").val();

  if(service_type && service_type=='Free'){
    $('#service_price').val('0');
  }else{

    var frequency_number=$('#frequency_number').val();
    if(frequency_number && $.isNumeric(frequency_number)){
      service_price=service_price* parseInt(frequency_number);
    }

    $('#service_price').val(service_price);
  }
  
});

$('#frequency_number').on('keyup keydown blur change',function(){

  var service_price=$('#service').find(":selected").data("service_price");
  if(service_price){
      if($.isNumeric(this.value)){
      service_price=service_price* parseInt(this.value);
      }
      $('#service_price').val(service_price);
  }

    
});

var contract_services_table=$('#contract_services_table').DataTable({
    "responsive": true,
    "autoWidth": false,
    processing: true,
    serverSide: true,
    ajax:$('#contract_services_data_url').val(),
    columns: [
        { data: 'id', name: 'id' },
        { data: 'service.service_name', name: 'service.service_name'},
        { data: 'service_type', name: 'service_type' },
        { data: 'price', name: 'price' },
        { data: 'is_enable', name: 'is_enable' },
        {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
    order: [ [0, 'asc']],
    columnDefs: [
    {   "targets": [0],
        "visible": false,
        "searchable": false
    }]

});



function delete_service(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this contract service!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.LoadingOverlay("show");
      $.ajax({
        url: url,
        type: "DELETE",
        data:{ "_token": $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
          contract_services_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Contract service successfully deleted.', 'Success', {timeOut: 5000});
        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }else if(status=='400'){
            if(response.message){
               toastr.error(response.message, 'Error', {timeOut: 5000});
             }else{
               toastr.error('Server error', 'Error', {timeOut: 5000});
             }
           
           }else{
             toastr.error('Internal server error.', 'Error', {timeOut: 5000});
           }
        }
     });

    } 
  });

 }


 function toggle_enable_disable(url,enable_or_diable){
  swal({
  title: "Are you sure?",
  text: "You want to "+enable_or_diable+" the service.",
  icon: "warning",
  buttons: true,
  dangerMode: true,
  })
  .then((confirm) => {
    if (confirm) {
      $.LoadingOverlay("show");
      $.ajax({
        url: url,
        type: "GET",
        data:{},
        success: function (data) {
          contract_services_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Status successfully updated.', 'Success', {timeOut: 5000});
        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }else if(status=='400'){
            if(response.message){
               toastr.error(response.message, 'Error', {timeOut: 5000});
             }else{
               toastr.error('Server error', 'Error', {timeOut: 5000});
             }
           
           }else{
             toastr.error('Internal server error.', 'Error', {timeOut: 5000});
           }
        }
     });

   
    // window.location.href=url;
    } 
  });

 }


 function service_details(service_details_url){


    $.LoadingOverlay("show");
    $.ajax({
        url: service_details_url,
        type: "GET",
        data:{ },
        success: function (data) {
            
            $('#contract_service_details_modal_body').html(data.html);
            $('#contract_service_details_modal').modal('show');
            $.LoadingOverlay("hide");
        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }else{
             toastr.error('Internal server error.', 'Error', {timeOut: 5000});
           }
        }
     });


 }


$('.datepicker').datepicker({
  dateFormat:'dd/mm/yy'
});


$('#start_time').datetimepicker({
  format: 'LT'
});
$('#end_time').datetimepicker({
  format: 'LT'
})