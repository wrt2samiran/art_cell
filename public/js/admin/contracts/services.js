
$('#service_type').select2({
    theme: 'bootstrap4',
    placeholder:translations.contract_manage_module.placeholders.service_type,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
             return "لم يتم العثور على نوع الخدمة";
           }else{
              return "No Service Type Found";
           }
          
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});




$('#service').select2({
    theme: 'bootstrap4',
    placeholder:translations.contract_manage_module.placeholders.service,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
             return "لا توجد خدمة";
           }else{
              return "No Service Found";
           }
          
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

        service_price:{
            required: true, 
            number:true
        },
        reccure_every:{
            required: true,
            number:true,
            min:1
        },
        number_of_times:{
          required: true,
          number:true
        },
        start_date:{
            required: true,
        },
        'weekly_days[]':{
          required: true,
        },
        day_number_monthly:{
            required: true,
            number:true,
            min:1,
            max:31
        },
        day_number_yearly:{
            required: true,
            number:true,
            min:1,
            max:31
        },
        day_number_m:{
          required:function(element){
            var interval_type=$('input[name="interval_type"]:checked').val();
            if(interval_type=='monthly'){
              var on_or_on_the_m=$('input[name="on_or_on_the_m"]:checked').val();

              if(on_or_on_the_m=='on'){
                return true
              }
            }

            $(element).removeClass('is-invalid'); 
            return false;
          },
          min:1,
          max:30
        },
        day_number_y:{
          required:function(element){
            var interval_type=$('input[name="interval_type"]:checked').val();
            if(interval_type=='yearly'){
              var on_or_on_the_y=$('input[name="on_or_on_the_y"]:checked').val();

              if(on_or_on_the_y=='on'){
                return true
              }
            }

            $(element).removeClass('is-invalid'); 
            return false;

          },
          min:1,
          max:30
        },
        no_of_occurrences:{
          required:function(element){
            
            var end_by_or_after=$('input[name="end_by_or_after"]:checked').val();
            if(end_by_or_after=='end_after'){
              return true
            }else{
              $(element).removeClass('is-invalid'); 
              return false;
            }
            
          },
          number:true,
          min:1
        },
        end_date:{
          required:function(element){
            //var interval_type=$('input[name="interval_type"]:checked').val();
            var end_by_or_after=$('input[name="end_by_or_after"]:checked').val();
            if(end_by_or_after=='end_by'){
              return true
            }else{
              $(element).removeClass('is-invalid'); 
              return false;
            }
          },
          endDateShouldBeGreatherThanStartDate:true

        }
    },
    messages: {
        end_date:{
          endDateShouldBeGreatherThanStartDate : function(){
            if(current_locale=='ar'){
              return "يجب أن يكون تاريخ الانتهاء أكبر من تاريخ البدء";
            }else{
              return "End date should be greater than start date";
            }
          }
        }
    },
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        if (element.attr('name')=='weekly_days[]') {
          error.insertAfter($('#weekly_days_error_holder'));
        }
        else{
          error.insertAfter(element);
        } 
    },
    highlight: function (element, errorClass, validClass) {

        if(element.getAttribute('name')!='weekly_days[]'){
            $(element).addClass('is-invalid');
        }
        // $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        if(element.getAttribute('name')!='weekly_days[]'){
            $(element).removeClass('is-invalid'); 
        }
           
    },
    submitHandler: function(form) {
        $.LoadingOverlay("show");
        form.submit();
    }
});

$("#update_service_form").validate({
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

        service_price:{
            required: true, 
            number:true
        },
        reccure_every:{
            required: true,
            number:true,
            min:1
        },
        number_of_times:{
          required: true,
          number:true
        },
        start_date:{
            required: true,
        },
        'weekly_days[]':{
          required: true,
        },
        day_number_monthly:{
            required: true,
            number:true,
            min:1,
            max:31
        },
        day_number_yearly:{
            required: true,
            number:true,
            min:1,
            max:31
        },
        day_number_m:{
          required:function(element){
            var interval_type=$('input[name="interval_type"]:checked').val();
            if(interval_type=='monthly'){
              var on_or_on_the_m=$('input[name="on_or_on_the_m"]:checked').val();

              if(on_or_on_the_m=='on'){
                return true
              }
            }

            $(element).removeClass('is-invalid'); 
            return false;
          },
          min:1,
          max:30
        },
        day_number_y:{
          required:function(element){
            var interval_type=$('input[name="interval_type"]:checked').val();
            if(interval_type=='yearly'){
              var on_or_on_the_y=$('input[name="on_or_on_the_y"]:checked').val();

              if(on_or_on_the_y=='on'){
                return true
              }
            }

            $(element).removeClass('is-invalid'); 
            return false;

          },
          min:1,
          max:30
        },
        no_of_occurrences:{
          required:function(element){
            
            var end_by_or_after=$('input[name="end_by_or_after"]:checked').val();
            if(end_by_or_after=='end_after'){
              return true
            }else{
              $(element).removeClass('is-invalid'); 
              return false;
            }
            
          },
          number:true,
          min:1
        },
        end_date:{
          required:function(element){
            //var interval_type=$('input[name="interval_type"]:checked').val();
            var end_by_or_after=$('input[name="end_by_or_after"]:checked').val();
            if(end_by_or_after=='end_by'){
              return true
            }else{
              $(element).removeClass('is-invalid'); 
              return false;
            }
          },
          endDateShouldBeGreatherThanStartDate:true

        }
    },
    messages: {
        end_date:{
          endDateShouldBeGreatherThanStartDate :function(){
            if(current_locale=='ar'){
              return "يجب أن يكون تاريخ الانتهاء أكبر من تاريخ البدء";
            }else{
              return "End date should be greater than start date";
            }
          } 
        }
    },
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        if (element.attr('name')=='weekly_days[]') {
          error.insertAfter($('#weekly_days_error_holder'));
        }
        else{
          error.insertAfter(element);
        } 
    },
    highlight: function (element, errorClass, validClass) {

        if(element.getAttribute('name')!='weekly_days[]'){
            $(element).addClass('is-invalid');
        }
        // $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        if(element.getAttribute('name')!='weekly_days[]'){
            $(element).removeClass('is-invalid'); 
        }
           
    },
    submitHandler: function(form) {
        $.LoadingOverlay("show");
        form.submit();
    }
});



$(document).ready(function () {
  $('input[name="interval_type"]').click(function () {
      $('#reccure_every_text').text($(this).data('reccure_every_text'));
      $('#reccure_every').val('1');
      $(this).tab('show');
      $(this).removeClass('active');
  });
  
  var contract_start_date=$('#contract_start_date').val();
  var contract_end_date=$('#contract_end_date').val();

  $('#start_date').datepicker({
      dateFormat:'dd/mm/yy',
      minDate: new Date(contract_start_date),
      maxDate: new Date(contract_end_date),
  });
  $('#end_date').datepicker({
      dateFormat:'dd/mm/yy',
      minDate: new Date(contract_start_date),
      maxDate: new Date(contract_end_date),
  });

  $('#start_time').datetimepicker({
  format: 'LT',
  });
  $('#end_time').datetimepicker({
  format: 'LT',
  });

  

});



$('#reccure_every').on('change keyup keydown blur',function(){
  var interval_type=$('input[name="interval_type"]:checked').val();
  if(interval_type=='monthly'){
     if($.isNumeric($(this).val())){
      $('#reccure_every_month_no').html($(this).val());
     }
  }
});

$('#consider_as_on_demand').on('change',function(){
  if(this.checked) {
    $('#number_of_time_can_used_holder').show();
  }else{
    $('#number_of_time_can_used_holder').hide();
  }
});


$('#service_type').on('change',function(){
  var service_type=$(this).val();
  if(service_type=='Maintenance'){
    $('#reccurence_container').show();
    $('#number_of_time_can_used_holder').hide();
    $('#number_of_time_can_used').val('');

    $('#consider_as_on_demand_holder').show();


  }else if(service_type=='On Demand') {
    $('#number_of_time_can_used_holder').show();
    $('#reccurence_container').hide();

    $('#consider_as_on_demand_holder').hide();
  }else{
    $('#number_of_time_can_used_holder').hide();
    $('#number_of_time_can_used').val('');
    $('#reccurence_container').hide();
    $('#consider_as_on_demand_holder').hide();
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
    }],
    "language": {
        "url": (current_locale=="ar")?"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"
    }

});



function delete_service(url){
  swal({
      title: translations.contract_manage_module.warning_title,
      text: translations.contract_manage_module.service_delete_warning,
      icon: "warning",
      buttons: [translations.general_sentence.button_and_links.cancel,translations.general_sentence.button_and_links.ok],
      dangerMode: false,
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
          toastr.success(translations.contract_manage_module.service_delete_success_message, 'Success', {timeOut: 5000});
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
      title: translations.contract_manage_module.warning_title,
      text: translations.contract_manage_module.change_status_warning,
      icon: "warning",
      buttons: [translations.general_sentence.button_and_links.cancel,translations.general_sentence.button_and_links.ok],
      dangerMode: false,
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
          toastr.success(translations.contract_manage_module.change_status_success_message, 'Success', {timeOut: 5000});
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





