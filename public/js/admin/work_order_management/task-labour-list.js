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