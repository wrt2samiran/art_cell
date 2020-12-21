//initializing galleries datatable
    var daily_task_management_table=$('#daily_task_management_table').DataTable({
        
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: $('#daily_task_management_table').val(),


        columns: [
            { data: 'id', name: 'id' },          
            { data: 'task_title', name: 'task_title' },
            { data: 'created_at', name: 'created_at' },
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



    // $("#admin_labour_task_add_form").validate({
    //     rules: {

            
    //        work_order_id: {
    //             required: true,
    //         },
    //         task_title: {
    //             required: true,
    //             minlength: 3,
    //             maxlength: 50,
    //         },
            
    //         service_id: {
    //             required: true,
    //         },
    //         user_id: {
    //             required: true,
    //         },
    //         work_date: {
    //             required: true,
    //         },
            

    //     },
    //     messages: {
    //         work_order_id: {
    //             required:  "Please select Work Order first",
    //         },
    //         task_title: {
    //             required:  "Task title is required",
    //             minlength: "Task title should have 3 characters",
    //             maxlength: "Task title should not be more then 50 characters"
    //         },
    //         service_id: {
    //             required:  "Please select service",
    //         },
    //         user_id: {
    //             required:  "Please select Labour",
    //         },
    //         work_date: {
    //             required:  "Please select Atleast one Work Date",
    //         },
            

    //     },

    //     errorPlacement: function (error, element) {
    //       error.addClass('invalid-feedback');
    //       error.insertAfter(element);
    //       if (element.prop("type") === "checkbox") {
    //         alert('called');
    //         error.insertAfter(element.parent("label"));
    //           } else if (element.hasClass('multiselect')) {
    //               error.insertAfter(element.next('.btn-group'))
    //           } else {
    //         error.insertAfter(element);
    //       }
    //     },
    //     highlight: function (element, errorClass, validClass) {
    //       $(element).addClass('is-invalid');
    //     },
    //     unhighlight: function (element, errorClass, validClass) {
    //       $(element).removeClass('is-invalid');
    //     },
    //     submitHandler: function(form) {
    //         form.submit();
    //     }
    // });



    $("#admin_labour_task_add_form").validate({
    ignore: [],
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
        user_id: {
            required: true,
        },
        work_date: {
            required: true,
        },
        
    },
    messages: {
        work_order_id: {
            required:  "Please select Work Order first",
        },
        task_title: {
            required:  "Task title is required",
            minlength: "Task title should have 3 characters",
            maxlength: "Task title should not be more then 50 characters"
        },
        service_id: {
            required:  "Please select service",
        },
        user_id: {
            required:  "Please select Labour",
        },
        work_date: {
            required:  "Please select Atleast one Work Date",
        },
        
    },
    //errorElement: "em",
    errorPlacement: function(error, element) {
        // Add the `help-block` class to the error element
        error.addClass('invalid-feedback');
    //       error.insertAfter(element);

        if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
        } else if (element.hasClass('multiselect')) {
            error.insertAfter(element.next('.btn-group'))
        } else {
      error.insertAfter(element);
    }
    },
    highlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
    }
});



$("#admin_maintanence_labour_task_add_form").validate({
    ignore: [],
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
        maintanence_user_id: {
            required: true,
        },
        work_date: {
            required: true,
        },
        
    },
    messages: {
        work_order_id: {
            required:  "Please select Work Order first",
        },
        task_title: {
            required:  "Task title is required",
            minlength: "Task title should have 3 characters",
            maxlength: "Task title should not be more then 50 characters"
        },
        service_id: {
            required:  "Please select service",
        },
        maintanence_user_id: {
            required:  "Please select Labour",
        },
        work_date: {
            required:  "Please select Atleast one Work Date",
        },
        
    },
    //errorElement: "em",
    errorPlacement: function(error, element) {
        // Add the `help-block` class to the error element
        error.addClass('invalid-feedback');
    //       error.insertAfter(element);

        if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
        } else if (element.hasClass('multiselect')) {
            error.insertAfter(element.next('.btn-group'))
        } else {
      error.insertAfter(element);
    }
    },
    highlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
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
            { data: 'country.name', name: 'country.name' },
            { data: 'state.name', name: 'state.name' },
            { data: 'city.name', name: 'city.name' },

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