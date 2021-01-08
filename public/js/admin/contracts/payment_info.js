
$(document).ready(function(){

  $("#payment_info_form").validate({
      rules: {
          contract_price:{
              required: true,
              number: true 
          },
          profit_in_percentage:{
              required: true,
              number: true 
          },
          notify_installment_before_days:{
              required: true,
              number: true 
          }
      },
      messages: {

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

          var services_price_total=parseFloat($('#services_price_total').val());
          var contract_price=parseFloat($('#contract_price').val());
          if(contract_price<services_price_total){
            toastr.error('Contract price should not be less than total price for the list of services you added for the contract.', 'Error', {timeOut: 5000});
          }else{

            if($("#in_installment").prop('checked') == true){

              var total_amount=parseFloat(0);

              $('.amount_input_list').each(function(i, obj) {
                  var amount=parseFloat($(this).val());
                  total_amount=total_amount+amount
              });

              var absolute_or_percentage=$('input[name="absolute_or_percentage"]:checked').val();

              if(absolute_or_percentage=='percentage'){

                if(parseFloat(total_amount)!=parseFloat(100)){
                  toastr.error('Sum of total percentage should be equal to 100.', 'Error', {timeOut: 5000});
                }else{
                    $.LoadingOverlay("show");
                    form.submit(); 
                }

              }else{
                if(contract_price!=total_amount){
                    toastr.error('Sum of total installment price should be equal to your contract price.', 'Error', {timeOut: 5000});
                }else{
                    $.LoadingOverlay("show");
                    form.submit();
                }
              }

               
            }else{
              $.LoadingOverlay("show");
              form.submit();
            }
          }

      }
  });

  $('.amount_input_list').each(function(i, obj) {

    var absolute_or_percentage=$('input[name="absolute_or_percentage"]:checked').val();

    $(this).rules("add", {
     required: true,
     number:true,
     messages: {
       required: "Enter price amount",
       maxlength: "Maximum 100 characters allowed",
     }
    });

    if(absolute_or_percentage=='percentage'){
      $(this).rules("add", {
       max: 100,
      });
    }else{
      $(this).rules( 'remove', 'max' );
    }

  });


  $('.due_date_input_list').each(function(i, obj) {
    
    $(this).rules("add", {
       required: true,
       maxlength: 10,
       messages: {

       }
    });

  });




});


$('.absolute_or_percentage').on('change',function(){
  
  if(this.value=='percentage'){
    $('.absolute_or_percentage_label').html('Percentage<span class="error">*</span>');
    $('.amount_input_list').attr('placeholder','Percentage');
    $('.amount_input_list').val('');
    $('.amount_text').show();
  }else{
    $('.absolute_or_percentage_label').html('Amount<span class="error">*</span>');
    $('.amount_input_list').attr('placeholder','Amount');
    $('.amount_input_list').val('');
    $('.amount_text').hide();
  }

  $('.amount_input_list').each(function(i, obj) {

    var absolute_or_percentage=$('input[name="absolute_or_percentage"]:checked').val();

    $(this).rules("add", {
     required: true,
     number:true,
     messages: {
       required: "Enter price amount",
       maxlength: "Maximum 100 characters allowed",
     }
    });

    if(absolute_or_percentage=='percentage'){
      $(this).rules("add", {
       max: 100,
      });
    }else{
      $(this).rules( 'remove', 'max' );
    }

  });


  

});




$(document).on("change keyup keydown blur", ".amount_input_list", function(){
  var absolute_or_percentage=$('input[name="absolute_or_percentage"]:checked').val();
  var id=$(this).data('id');
  if(absolute_or_percentage=='percentage'){

      var contract_price=$('#contract_price').val();
      if(contract_price && $.isNumeric(contract_price)){
          if($.isNumeric(this.value)){

            var id=$(this).data('id');

            var amount=(parseFloat(this.value)/100)*parseFloat(contract_price);
            
            $('#amount_text_'+id).html('Amount='+parseFloat(amount).toFixed(2));
          }else{
            $('#amount_text_'+id).html('');
          }
      }else{
        $('#amount_text_'+id).html('');
      }
  }else{
    $('#amount_text_'+id).html('');
  }
 
});




$(document).on("change keyup keydown blur", "#profit_in_percentage", function(){

      var contract_price=$('#contract_price').val();
      if(contract_price && $.isNumeric(contract_price)){
          if($.isNumeric(this.value)){

            var amount=(parseFloat(this.value)/100)*parseFloat(contract_price);
            
            $('#profit_in_amount_text').html('Amount = '+parseFloat(amount).toFixed(2));
          }else{
            $('#profit_in_amount_text').html('');
          }
      }else{
        $('#profit_in_amount_text').html('');
      }


});

$(document).on("change keyup keydown blur", "#contract_price", function(){

      var profit_in_percentage=$('#profit_in_percentage').val();

      if(profit_in_percentage && $.isNumeric(profit_in_percentage)){
          if($.isNumeric(this.value)){

            var amount=(parseFloat(profit_in_percentage)/100)*parseFloat(this.value);
            
            $('#profit_in_amount_text').html('Amount = '+parseFloat(amount).toFixed(2));
          }else{
            $('#profit_in_amount_text').html('');
          }
      }else{
        $('#profit_in_amount_text').html('');
      }


});


$('#add_installment_button').click(function(){ 
  let random_string = String(Math.random(10)).substring(2,14); 

  var absolute_or_percentage=$('input[name="absolute_or_percentage"]:checked').val();

  if(absolute_or_percentage=='percentage'){
    var label=translations.contract_manage_module.labels.percentage;
    var placeholder=translations.contract_manage_module.placeholders.percentage;
  }else{
    var label=translations.contract_manage_module.labels.amount;
    var placeholder=translations.contract_manage_module.placeholders.amount;
  }

var row=`<div class="row" id="row`+random_string+`">
        <input type="hidden" name="installment_id[]" value="">
        <div class="col-sm-5">
              <div class="form-group required">
                <label class="absolute_or_percentage_label" for="amount_`+random_string+`">`+label+`<span class="error">*</span></label>

                <input type="number" min="1" data-id="`+random_string+`" value="" name="amount[]" class="form-control amount_input_list"  id="amount_`+random_string+`"  placeholder="`+placeholder+`">
                <div id="amount_text_`+random_string+`" class="amount_text"></div>
              </div>
        </div>
        <div class="col-sm-5">
              <div class="form-group required">
                <label for="due_date_`+random_string+`">Due Date<span class="error">*</span></label>

                <input type="text" autocomplete="off" value="" readonly="readonly" name="due_date[]" class="form-control due_date_input_list datepicker"  id="due_date_`+random_string+`"  placeholder="Due Date">
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
      dateFormat:'dd/mm/yy',
      minDate: 0
});




 


