//initializing galleries datatable
    var task_labour_list_management_table=$('#task_labour_list_management_table').DataTable({
        
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: $('#task_labour_list_management_table').val(),


        columns: [
            { data: 'id', name: 'id' },          
            { data: 'user_details.name', name: 'user_details.name' },
            { data: 'task_date', name: 'task_date' },
            
            {
               data: 'work_order_slot.daily_slot', 
              render: function(data){
                  if(data == '1') {
                      return 'First Slot';
                  }
                  else if(data == '2'){
                      return 'Second Slot';
                  }
                  else if(data == '3'){
                      return 'Third Slot';
                  }
                  else if(data == '4'){
                      return 'Fourth Slot';
                  }
                  else if(data == '5'){
                      return 'Fifth Slot';
                  }
                  else if(data == '6'){
                      return 'Sixth Slot';
                  }
                  else if(data == '7'){
                      return 'Seventh Slot';
                  }
                  else if(data == '8'){
                      return 'Eighth Slot';
                  }
                  else if(data == '9'){
                      return 'Nineth Slot';
                  }
                  else if(data == '10'){
                      return 'Tenth Slot';
                  }
                  return 'No Slot';
              name: 'work_order_slot.daily_slot'
            }},
            
            { data: 'reschedule_task_details_id', 
              render: function(data){
                  if(data >0){
                    return '<strong style="color:#17a2b8">Rescheduled Task</strong>';
                  }
                  else{
                    return 'Normal Task';
                  }
              }
            },
            
            { data: 'user_feedback', name: 'user_feedback' },
            { data: 'task_finish_date_time', name: 'task_finish_date_time' },
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

 function rescheduleTask(id, allRestrictedDate, task_description)
 {
  //alert(id);
  console.log(id);
  
  console.log(JSON.stringify(allRestrictedDate));
  $('#task_details_id').val(id);
  $('#taskRescheduleModal').modal('show');



    var array = JSON.stringify(allRestrictedDate);

    $('#task_date').datepicker({
        beforeShowDay: function(date){
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [ array.indexOf(string) == -1 ]
        }
    });

    $('#task_description').val(task_description);

 }



function updateLabourTask(id, task_description)
 {
  //alert(id);
  console.log(id);
  
  $('#update_task_details_id').val(id);
  $('#updateLabourTaskModal').modal('show');



    // var array = JSON.stringify(allRestrictedDate);

    // $('#task_date').datepicker({
    //     beforeShowDay: function(date){
    //         var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
    //         return [ array.indexOf(string) == -1 ]
    //     }
    // });

    $('#update_task_description').val(task_description);
    
 }

 $('.select-labour').select2({
      theme: 'bootstrap4',
      placeholder:'Filter by Labour'
    });




//  $(".ui-state-disabled").hover(function() {

//         alert("Test");  
// });


$("#service_provider_reschedule").validate({
    ignore: [],
    rules: {
        task_date: {
            required: true,
        },        
    },
    messages: {
        task_date: {
            required:  "Please select Task Date",
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


 $("document").ready(function(){
    setTimeout(function(){
        $(".alert-success").remove();
    }, 5000 );
});