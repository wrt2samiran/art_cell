
$("#admin_contract_edit_form").validate({
    ignore:[],
    rules: {
        title:{
            required: true,
            maxlength: 255,  
        },
        description:{
            required: function() 
            {
             CKEDITOR.instances.description.updateElement();
             return true;
            },
        },
        total_service:{
            required:true
        },
        property:{
            required: true
        },
        service_provider:{
            required: true, 
        },
        property_owner:{
            required: true, 
        },
        start_date:{
            required: true, 
            maxlength: 10,
        },
        end_date:{
            required: true,
            maxlength: 10, 
        },
        contract_price:{
            required: true, 
            number:true
        },


        contract_status_id:{
            required: true,
        }
    },
    messages: {
        title: {
            required:  "Contract title is required",
        },
        property:{
            required:  "Select property",
        },
        description: {
            required:  "Contract info is required",
            maxlength: "Info should not be more then 1000 characters",
        },
        total_service:{
            required:  "Add services required for the contract",
        },
        property_owner: {
            required:  "Please select property owner",
        },
        service_provider:{
            required:  "Please select service provider",
        },
        contract_price:{
            required:  "Enter contract price",
        },
        start_date:{
            required:  "Enter start date in dd/mm/yyy format",
        },
        end_date:{
            required:  "Enter end date in dd/mm/yyy format",
        },
        "services[]":{
            required:  "Select services required for the contract",
        },

        contract_status_id: {
            required:  "Status is required",
        },
    },
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        if(element.attr('name')=='description'){
            error.appendTo($('#description_error'));
        }
        else if(element.attr('name')=='total_service'){
            error.appendTo($('#services_error'));
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





$('#property_owner').select2({
    theme: 'bootstrap4',
    placeholder:'Select property owner',
    "language": {
       "noResults": function(){
           return "No Property Owner Found <a href='"+$('#property_owner_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#property').select2({
    theme: 'bootstrap4',
    placeholder:'Select property',
    "language": {
       "noResults": function(){
           return "No Property Found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#services').select2({
    theme: 'bootstrap4',
    placeholder:'Select services',
    "language": {
       "noResults": function(){
           return "No Service Found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});



$('#property').select2({
    theme: 'bootstrap4',
    placeholder:'Select property',
    "language": {
        "noResults": function(){
            return "No Property Found <a href='"+$('#property_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$('#service_provider').select2({
    theme: 'bootstrap4',
    placeholder:'Select property',
    "language": {
        "noResults": function(){
            return "No Service Provider Found <a href='"+$('#service_provider_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$('#contract_status_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select status',
    "language": {
       "noResults": function(){
           return "No Status Found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#start_date').datepicker({
    dateFormat:'dd/mm/yy'
});
$('#end_date').datepicker({
    dateFormat:'dd/mm/yy'
});

$('#contract_files').on('change',function(){
    
    var files = document.getElementById("contract_files").files;
    var file_size_error=false;
    var file_type_error=false;
    for (var i = 0; i < files.length; i++)
    {
        var file_size_in_kb=(files[i].size/1024);
        var file_type= files[i].type;

        if(file_size_in_kb>1024){
           file_size_error=true; 
        }

        var allowed_file_types=['application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/msword',
        'application/jpeg',
        'application/jpg',
        'application/png',
        'text/plain'
        ];

        if(!allowed_file_types.includes(file_type)){
            file_type_error=true;
        }

    }

    if(file_size_error==true || file_type_error==true){
        reset($('#contract_files'));

        var error_message='';

        if(file_size_error==true && file_type_error==true){
            error_message="Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files of max size 1mb";
        }else if(file_size_error==true && file_type_error==false){
            error_message="File size should not be more than 1 mb";
        }else{
            error_message="Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files";
        }

        swal(error_message);

    }


});


/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}



$('.datepicker').datepicker({
    dateFormat:'dd/mm/yy'
});



$('#add_installment_button').click(function(){ 
  let random_string = String(Math.random(10)).substring(2,14); 

var row=`<input type="hidden" name="installment_id[]" value=""><div class="row" id="row`+random_string+`">
        <div class="col-sm-5">
              <div class="form-group required">
                <label for="amount_`+random_string+`">Amount<span class="error">*</span></label>

                <input type="number" min="1" name="amount[]" class="form-control amount_input_list"  id="amount_`+random_string+`"  placeholder="Amount">

              </div>
        </div>
        <div class="col-sm-5">
              <div class="form-group required">
                <label for="due_date_`+random_string+`">Due Date<span class="error">*</span></label>

                <input type="text" name="due_date[]" class="form-control due_date_input_list datepicker"  id="due_date_`+random_string+`"  placeholder="Due Date">
              </div>
        </div>
        <div class="col-sm-2">
              <div class="form-group ">
                <label for="">&nbsp;</label>
      
                  <div class="installment_input_add" >
                    <button type="button"  name="remove" id="`+random_string+`" class="btn btn-danger btn_installment_remove">X</button>
                  </div> 
              </div>
        </div>
      </div>`;
  $('#installment_input_container').append(row); 
  $('.datepicker').datepicker({
        dateFormat:'dd/mm/yy'
  });
}); 


$(document).on('click', '.btn_installment_remove', function(){  

var button_id = $(this).attr("id");   
    $('#row'+button_id+'').remove();  
});


$('#in_installment').on('change',function(){
    if(this.checked) { 
        $('#installment_input_container').show();
        add_rules_to_installment_fields();
    } else { 
        remove_rules_from_installment_fields();
        $('#installment_input_container').hide(); 
    } 
});

if($('#in_installment').is(':checked')){
  add_rules_to_installment_fields();
}

function add_rules_to_installment_fields(){
     $('.amount_input_list').each(function(i, obj) {

        $("#"+$(this).attr('id')).rules("add", {
           required: true,
           maxlength: 100,
           number:true,
           messages: {
             required: "Enter price amount",
             maxlength: "Maximum 100 characters allowed",
           }
        });

   });


  $('.due_date_input_list').each(function(i, obj) {
        $("#"+$(this).attr('id')).rules("add", {
           required: true,
           maxlength: 10,
           messages: {
             required: "Please enter due date in dd/mm/yyyy format",
             maxlength: "Maximum 10 characters allowed",
           }
        });
   });
}

function remove_rules_from_installment_fields(){
   $('.amount_input_list').each(function(i, obj) {
        $("#"+$(this).attr('id')).rules('remove');
   });

  $('.due_date_input_list').each(function(i, obj) {
        $("#"+$(this).attr('id')).rules('remove');
   }); 
}




   //function to delete quotation
 function delete_attach_file(url,file_id){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this file!",
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

          $('#attachment_file_'+file_id).remove();

          var NumberOfFilePresent = $('.attachment_files').length;
          if(NumberOfFilePresent=='0'){
            $('.attachment_files_container').append('<div class="col-md-12 text-muted">No files attached to this property</div>');
          }
       
          $.LoadingOverlay("hide");
          toastr.success('File successfully deleted.', 'Success', {timeOut: 5000});
        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }
           else if(status=='403'){
              toastr.error('You do not have permission to perform this action.', 'Error', {timeOut: 5000});
           }
           else{
             toastr.error('Internal server error.', 'Error', {timeOut: 5000});
           }
        }
     });

     
    } 
  });

 }



function add_service(){
  $('#add_service_modal').modal('show');
}

$('#service_type').on('change',function(){
  var service_type=$(this).val();

  if(service_type=='Maintenance'){
    $('#number_of_time_can_used_holder').hide();
    $('#number_of_time_can_used').val('');
    $('#frequency_type_holder').show();
    $('#frequency_number_holder').show();
  }else if(service_type=='On Demand') {
    $('#number_of_time_can_used_holder').show();
    $('#frequency_type_holder').hide();
    $('#frequency_number').val('');
    $('#frequency_number_holder').hide();
  }else{
    $('#number_of_time_can_used').val('');
    $('#frequency_number').val('');
    $('#frequency_type_holder').hide();
    $('#frequency_number_holder').hide();
    $('#number_of_time_can_used_holder').hide();
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
        var uniqueId= Math.floor(Math.random() * 26) + Date.now();

        var service_row=`<tr class="services_row" id="service_row_`+uniqueId+`">`;
        var service_name=$('#service').find(":selected").data("service_name");
        var service_id=$('#service').find(":selected").val();
        
        service_row=service_row+`<td>`+service_name+`<input type="hidden" value="`+service_id+`" name="services[]"></td>`;
        var service_type=$('#service_type').find(":selected").val();
        
        service_row=service_row+`<td>`+service_type+` <input type="hidden" value="`+service_type+`" name="service_type[]"></td>`;

        if(service_type=='Maintenance'){
          var frequency_type_id=$('#frequency_type').find(":selected").val();
          var frequency_type=$('#frequency_type').find(":selected").data("type_name");
          var interval_days=$('#frequency_type').find(":selected").data("interval_days");
       
          var frequency_number=$('#frequency_number').val();

          var number_of_time_can_used='';
          var frequency_text=frequency_type+' (x'+frequency_number+')';

        }else if(service_type=='On Demand') {

          var frequency_type_id='';
          var frequency_number='';
          var number_of_time_can_used=$('#number_of_time_can_used').val();
          if(number_of_time_can_used!=''){
            var frequency_text=`Can use `+number_of_time_can_used+` times`;
          }else{
            var frequency_text=`---`;
          }
          
          var interval_days='';
        }else{
          var frequency_type_id='';
          var number_of_time_can_used='';
          var frequency_text='---';
          var interval_days='';
          var frequency_number='';
        }

        service_row=service_row+`<td>`+frequency_text+`
        <input type="hidden" value="`+frequency_type_id+`" name="frequency_type_id[]">
        <input type="hidden" value="`+frequency_number+`" name="frequency_number[]">
        <input type="hidden" value="`+interval_days+`" name="interval_days[]">
        <input type="hidden" value="`+number_of_time_can_used+`" name="number_of_time_can_used[]">
        </td>`;

        if(service_type=='Free'){
          var service_price='0';
        }else{
          var service_price=$('#service_price').val();
        }
        
        service_row=service_row+`<td>`+service_price+`<input type="hidden" value="`+service_price+`" name="service_price[]"></td>`;
        service_row=service_row+`<td><a href="javascript:void(0)" id="`+uniqueId+`" class="btn_service_remove btn btn-outline-danger">x</a></td>`;

        service_row=service_row+`</tr>`;

        $('#services_container table tbody').append(service_row);
        $('#services_container').show();
        $('#add_service_modal').modal('hide');
        $.LoadingOverlay("hide");
        form.reset();
        
        $('#total_service').val($('.services_row').length);

        $('#frequency_type').val(null).trigger('change');
        $('#service_type').val(null).trigger('change');
        $('#service').val(null).trigger('change');

        //form.submit();
    }
});

$(document).on('click', '.btn_service_remove', function(){  
    
    var button_id = $(this).attr("id");
    
    $('#service_row_'+button_id+'').remove();  

    if($('.services_row').length<1){
      $('#total_service').val('');
      $('#services_container').hide();
    }

});


$(document).on('click', '.btn_service_edit', function(){  
    
    var button_id = $(this).attr("id");
    var element_row_uniqe_id=button_id.split('_')[1];

    var service_details_in_string=$(this).data('service_details');
    
    var service_details=JSON.parse(atob(service_details_in_string));


    $('#edit_contract_service_id').val(service_details.id);
    $('#service_edit').val(service_details.service_id);
    if(service_details.frequency_type_id){
      $('#frequency_type_edit').val(service_details.frequency_type_id);
    }else{
      $('#frequency_type_edit').val('');
    }

    if(service_details.service_type=='Maintenance'){
      $('#frequency_number_edit').val(service_details.frequency_number);
    }else{
      $('#frequency_number_edit').val('');
    }

    $('#service_type_edit').val(service_details.service_type);

    if(service_details.number_of_time_can_used){
      $('#number_of_time_can_used_edit').val(service_details.number_of_time_can_used);
    }else{
      $('#number_of_time_can_used_edit').val('');
    }



    $('#service_price_edit').val(service_details.price);
 
    if(service_details.service_type=='Free'){
        $('#service_price_edit').val('0');
        $('#service_price_edit').prop('disabled', true);
    }

    if(service_details.service_type=='On Demand'){
      $('#number_of_time_can_used_holder_edit').show();
    }

    if(service_details.service_type=='Maintenance'){
      $('#frequency_type_holder_edit').show();
      $('#frequency_number_holder_edit').show();
    }

    $('#service_details').val(service_details_in_string);

    $('#edit_service_modal').modal('show');
    
});



$('#service_type_edit').on('change',function(){
  var service_type=$(this).val();

  if(service_type=='Maintenance'){
    $('#number_of_time_can_used_holder_edit').hide();
    $('#number_of_time_can_used_edit').val('');
    $('#frequency_type_holder_edit').show();
    $('#frequency_number_holder_edit').show();
  }else if(service_type=='On Demand') {
    $('#number_of_time_can_used_holder_edit').show();
    $('#frequency_type_holder_edit').hide();

    $('#frequency_number_edit').val('');
    $('#frequency_number_holder_edit').hide();
  }else{
    $('#number_of_time_can_used_edit').val('');
    $('#frequency_type_holder_edit').hide();
    $('#number_of_time_can_used_holder_edit').hide();
    $('#frequency_number_edit').val('');
    $('#frequency_number_holder_edit').hide();
  }

  if(service_type=='Free'){
    $('#service_price_edit').val('0');
    $('#service_price_edit').prop('disabled', true);
  }else{
    $('#service_price_edit').prop('disabled', false);

    var service_price=$('#service_edit').find(":selected").data("service_price");

    if(service_price){

      $('#service_price_edit').val(service_price);
    }else{
      $('#service_price_edit').val('');
    }
    
  }

});

$('#service_edit').on('change',function(){

  var service_price=$('#service_edit').find(":selected").data("service_price");
  var service_type=$('#service_type_edit').find(":selected").val();
  if(service_type && service_type=='Free'){
    $('#service_price_edit').val('0');
  }else{
    var frequency_number=$('#frequency_number_edit').val();
    if(frequency_number && $.isNumeric(frequency_number)){
      service_price=service_price* parseInt(frequency_number);
    }
    $('#service_price_edit').val(service_price);
  }
  
});

$('#frequency_number_edit').on('keyup keydown blur change',function(){

  var service_price=$('#service_edit').find(":selected").data("service_price");
  if(service_price){
      if($.isNumeric(this.value)){
      service_price=service_price* parseInt(this.value);
      }
      $('#service_price_edit').val(service_price);
  }
  
});

$("#edit_service_form").validate({
    rules: {

        service_edit:{
            required: true,
        },
        service_type_edit:{
            required: true
        },
        number_of_time_can_used_edit:{
            number:true
        },
        frequency_type_edit:{
            required: true, 
        },
        frequency_number_edit:{
            required: true, 
            number:true
        },
        service_price_edit:{
            required: true, 
            number:true
        }
    },
    messages: {
        service_edit:{
            required:  "Select service",
        },
        service_type_edit:{
            required:  "Select service type",
        },
        service_price_edit:{
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

        var service_details_in_string=$('#service_details').val();

        var service_details=JSON.parse(atob(service_details_in_string));


        var uniqueId=$('#edit_contract_service_id').val();
        var contract_service_id=$('#edit_contract_service_id').val();
        var service_row='';
        var service_name=$('#service_edit').find(":selected").data("service_name");
        var service_id=$('#service_edit').find(":selected").val();
        
        service_details['service_id']=service_id;

        service_row=service_row+`<td>`+service_name+`<input type="hidden" value="`+service_id+`" name="services[]"></td>`;
        var service_type=$('#service_type_edit').find(":selected").val();
        
        service_details['service_type']=service_type;

        service_row=service_row+`<td>`+service_type+` <input type="hidden" value="`+service_type+`" name="service_type[]"></td>`;

        if(service_type=='Maintenance'){
          var frequency_type_id=$('#frequency_type_edit').find(":selected").val();
          var frequency_type=$('#frequency_type_edit').find(":selected").data("type_name");
          var interval_days=$('#frequency_type_edit').find(":selected").data("interval_days");
       
          var frequency_number=$('#frequency_number_edit').val();
          var number_of_time_can_used='';

          var frequency_text=frequency_type+' (x'+frequency_number+')';

        }else if(service_type=='On Demand') {
          var frequency_type_id='';
          var frequency_number='';
          var number_of_time_can_used=$('#number_of_time_can_used_edit').val();
          if(number_of_time_can_used!=''){
            var frequency_text=`Can use `+number_of_time_can_used+` times`;
          }else{
            var frequency_text=`---`;
          }
          
          var interval_days='';
        }else{
          var frequency_type_id='';
          var frequency_number='';
          var number_of_time_can_used='';
          var frequency_text='---';
          var interval_days='';
        }

        service_details['frequency_type']=frequency_type;
        service_details['frequency_type_id']=frequency_type_id;
        service_details['frequency_number']=frequency_number;
        service_details['number_of_time_can_used']=number_of_time_can_used;
        
        service_row=service_row+`<td>`+frequency_text+`
        <input type="hidden" value="`+frequency_type_id+`" name="frequency_type_id[]">
        <input type="hidden" value="`+frequency_number+`" name="frequency_number[]">
        <input type="hidden" value="`+interval_days+`" name="interval_days[]">
        <input type="hidden" value="`+number_of_time_can_used+`" name="number_of_time_can_used[]">
        </td>`;

        if(service_type=='Free'){
          var service_price='0';
        }else{
          var service_price=$('#service_price_edit').val();
        }
        
        service_details['price']=service_price;

        service_row=service_row+`<td>`+service_price+`<input type="hidden" value="`+service_price+`" name="service_price[]"></td>`;
        service_row=service_row+`<td><input type="hidden" value="`+contract_service_id+`" name="contract_service_id[]"> <a href="javascript:void(0)" data-service_details="`+btoa(JSON.stringify(service_details))+`" id="edit_`+uniqueId+`" class="btn_service_edit btn btn-outline-success"><i class="fas fa-pen-square text-success"></i></a> <a href="javascript:void(0)" id="`+uniqueId+`" class="btn_service_remove btn btn-outline-danger">x</a></td>`;

        $('#service_row_'+uniqueId).html(service_row);
        

        $('#edit_service_modal').modal('hide');
        $.LoadingOverlay("hide");
        form.reset();
        
        $('#frequency_type_edit').val(null).trigger('change');
        $('#service_type_edit').val(null).trigger('change');
        $('#service_edit').val(null).trigger('change');

        //form.submit();
    }
});