//initializing galleries datatable
    var daily_task_management_table=$('#daily_task_management_table').DataTable({
        
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: $('#daily_task_management_table').val(),


        columns: [
            { data: 'id', name: 'id' },
            // { data: 'task.task_title', name: 'task.task_title'},
           

            { data: 'task_date', name: 'task_date' },
            { data: 'user_details.name', name: 'user_details.name' },
            { data: 'user_feedback', name: 'user_feedback' },
            { data: 'status', name: 'ststus' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
         order: [ [0, 'asc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }]

    });



    $("#admin_labour_task_add_form").validate({
        rules: {

            
           work_order_id: {
                required: true,
            },
            task_title: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            
            service_id: {
                required: true,
            },
            property_id: {
                required: true,
            },
            country_id: {
                required: true,
            },
            state_id: {
                required: true,
            },           
            city_id: {
                required: true,
            },
            labour_id: {
                required: true,
            },
            date_range: {
                required: true,
            },


        },
        messages: {
            work_order_id: {
                required:  "Please select service",
            },
            task_title: {
                required:  "Job title is required",
                minlength: "Job title should have 3 characters",
                maxlength: "Job title should not be more then 50 characters"
            },
            service_id: {
                required:  "Please select service",
            },
            property_id: {
                required:  "Please select property",
            },
            country_id: {
                required:  "Please select country",
            },
            state_id: {
                required:  "Please select state",
            },
            city_id: {
                required:  "Please select city",
            },
            labour_id: {
                required:  "Please select user",
            },
            date_range: {
                required:  "Please select date range",
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
            form.submit();
        }
    });

    
    var labour_task_management_table=$('#labour_task_management_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: baseUrl+'/admin/work-order-management',
        columns: [
            { data: 'id', name: 'id' },
            // { data: 'task.property.property_name', name: 'task.property.property_name' },
            { data: 'service.service_name', name: 'service.service_name' },
            // { data: 'country.name', name: 'country.name' },
            // { data: 'state.name', name: 'state.name' },
            // { data: 'city.name', name: 'city.name' },

            { data: 'task_date', name: 'task_date' },

            { data: 'status', name: 'ststus' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
         order: [ [0, 'asc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }]

    });

 //function to delete labour task
 function delete_task(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this labour task!",
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
          $.LoadingOverlay("hide");
          toastr.success('Labour task successfully deleted.', 'Success', {timeOut: 5000});
          daily_task_management_table.ajax.reload(null, false);
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
  });

 }

//function to change status of gallery
 function change_status(url,complete_or_pending){
  swal({
  title: "Are you sure?",
  text: "You want to "+complete_or_pending+" the daily task.",
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
          $.LoadingOverlay("hide");
          toastr.success('Status successfully updated.', 'Success', {timeOut: 5000});
          //daily_task_management_table.ajax.reload(null, false);
          window.location.href=url;
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

     
    // 
    } 
  });


 }

 

 $("#admin_labour_task_feedback_form").validate({

        rules: {
            user_feedback: {
                required: true,
                maxlength: 5000,
            },
           
        },
        messages: {
            user_feedback: {
                required: 'Please enter your Feedback',
                maxlength: "Feedback should not be more than 5000 characters"
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
            form.submit();
        }
    });


 $("document").ready(function(){
    setTimeout(function(){
        $(".alert-success").remove();
    }, 5000 );
});