


$("#admin_contract_create_form").validate({
    rules: {


        description:{
            required: true,
            maxlength: 1000,  
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
        "services[]":{
            required: true, 
        },

    },
    messages: {

        property:{
            required:  "Select property",
        },
        description: {
            required:  "Contract info is required",
            maxlength: "Info should not be more then 1000 characters",
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

var row=`<div class="row" id="row`+random_string+`">
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
    } else { 
        $('#installment_input_container').hide(); 
    } 
});



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


