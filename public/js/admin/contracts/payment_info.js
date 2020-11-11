
$(document).ready(function(){

  $("#payment_info_form").validate({
      rules: {
          contract_price:{
              required: true,
              number: true 
          },
          notify_installment_before_days:{
              required: true,
              number: true 
          }
      },
      messages: {
          contract_price:{
              required:  "Enter contract price",
          },
          notify_installment_before_days:{
              required:  "Notify user before home may days from due date.",
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

  $('.amount_input_list').each(function(i, obj) {

    $(this).rules("add", {
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
    
    $(this).rules("add", {
       required: true,
       maxlength: 10,
       messages: {
         required: "Please enter due date in dd/mm/yyyy format",
         maxlength: "Maximum 10 characters allowed",
       }
    });

  });




});




$('#add_installment_button').click(function(){ 
  let random_string = String(Math.random(10)).substring(2,14); 

var row=`<div class="row" id="row`+random_string+`">
        <div class="col-sm-5">
              <div class="form-group required">
                <label for="amount_`+random_string+`">Amount<span class="error">*</span></label>

                <input type="number" min="1" value="" name="amount[]" class="form-control amount_input_list"  id="amount_`+random_string+`"  placeholder="Amount">

              </div>
        </div>
        <div class="col-sm-5">
              <div class="form-group required">
                <label for="due_date_`+random_string+`">Due Date<span class="error">*</span></label>

                <input type="text" autocomplete="off" value="" name="due_date[]" class="form-control due_date_input_list datepicker"  id="due_date_`+random_string+`"  placeholder="Due Date">
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


$('.datepicker').datepicker({
      dateFormat:'dd/mm/yy'
});




 


