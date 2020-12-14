
$("#labour_leave_create_form").validate({
    rules: {
        labour_id:{
            required: true, 
        },
        date_range:{
            required: true,
    },
    messages: {
        labour_id: {
            required:  "First name is required",
        },
        date_range: {
            required:  "Last name is required",
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




  function onCountryChange(country_id){
     $.ajax({
       
        url: "{{route('admin.cities.getStates')}}",
        type:'post',
        dataType: "json",
        data:{country_id:country_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allState);
             var stringified = JSON.stringify(response.allStates);
            var statedata = JSON.parse(stringified);
             var state_list = '<option value=""> Select State</option>';
             $.each(statedata,function(index, state_id){
                    state_list += '<option value="'+state_id.id+'">'+ state_id.name +'</option>';
             });
                $("#state_id").html(state_list);
            }
        });
    }




