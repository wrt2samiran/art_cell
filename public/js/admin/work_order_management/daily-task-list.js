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
            { data: 'task_complete_percent', render:function(data){
                 if(data<1)
                 {
                  var dataval = 0;
                 }
                 else
                 {
                  var dataval = data;
                 }
                 return '<div class="progress"><div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="'+dataval+'" aria-valuemin="0" aria-valuemax="100" style="width:'+dataval+'%">'+dataval+'% </div></div>'
                }},
            { data: 'status', name: 'ststus' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
         order: [ [0, 'desc'] ],
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
        task_title_maintanence_daily: {
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
        task_title_maintanence_daily: {
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

 
$("#admin_other_maintanence_labour_task_add_form").validate({
    ignore: [],
    rules: {
        work_order_id: {
            required: true,
        },
        task_title_maintanence_other: {
            required: true,
            minlength: 3,
            maxlength: 50,
        },
  
        service_id: {
            required: true,
        },
        maintanence_other_user_id: {
            required: true,
        },
        work_date_other: {
            required: true,
        },
        
    },
    messages: {
        work_order_id: {
            required:  "Please select Work Order first",
        },
        task_title_maintanence_other: {
            required:  "Task title is required",
            minlength: "Task title should have 3 characters",
            maxlength: "Task title should not be more then 50 characters"
        },
        service_id: {
            required:  "Please select service",
        },
        maintanence_other_user_id: {
            required:  "Please select Labour",
        },
        work_date_other: {
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
        ajax: {
          url:baseUrl+'/admin/work-order-management',
          data: function (d) {
            d.status = $('#status').val();
          }
        },

        columns: [
            { data: 'id', name: 'id' },
            { data: 'task.property.property_name', name: 'task.property.property_name' },
            { data: 'task.property',
              render: function(data){
                      return '<table>'+
                      '<tr><td>Country :</td><td>'+data.country.name+'</td></tr>'+
                      '<tr><td>State :</td><td>'+data.state.name+'</td></tr>'+
                      '<tr><td>City :</td><td>'+data.city.name+'</td></tr>'+
                      '</table>';
                  }, searchable: false, sortable : false
            }, 
            
            { data: 'service.service_name', name: 'service.service_name', searchable: false, sortable : false },
            { data: 'task.task_title', name: 'task.task_title'},
            { data: 'task_date', name: 'task_date', searchable: false, sortable : false },
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
            }, searchable: false, sortable : false},
            
            // { data: 'task.task_desc', name: 'task.task_desc' },
            { data: 'task_finish_date_time', name: 'task_finish_date_time', searchable: false, sortable : false},
            { data: 'late_feedback', 
              render: function(data){
                if(data == 'N'){
                  return 'NO';
                }
                else{
                  return "YES";
                }
                name: 'late_feedback' 
              }
            },
            { data: 'status', name: 'ststus', sortable : false },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [ [0, 'desc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }],

    });

    $('#status').change(function(){
        labour_task_management_table.draw();
    });

    $('.status-filter').select2({
      theme: 'bootstrap4',
      placeholder:'Filter by Status'
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
            status: {
                required: true,
            },
           
        },
        messages: {
            user_feedback: {
                required: 'Please enter your Feedback',
                maxlength: "Feedback should not be more than 5000 characters"
            },
            status: {
                required: 'Please select Feedback Status',
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

$("#add_new_file").on("click", function () {
    let random_string = String(Math.random(10)).substring(2,14); 
    var row=`<div class="row mt-1 files_row">`;
    row += `<div class="col-md-6"><input placeholder="Title" class="form-control file_title_list"  id="feedback_file_title_`+random_string+`" name="feedback_file_title[]" type="text"></div>`;
    row += `<div class="col-md-5">
    <input placeholder="File" required class="form-control file_list"  id="feedback_file_`+random_string+`" name="feedback_file[]" type="file">
      <small class="form-text text-muted">
        Upload JPEG/JPG/PNG/SVG files of max. 2mb
      </small>
    </div>`;
    row += `<div class="col-md-1"><button data-delete_url="" type="button" class="btn btn-danger files_row_del_btn"><i class="fa fa-trash" aria-hidden="true"></i></button></div>`;
    row +=`</div>`;
    $("#files_container").append(row);

    $('#feedback_file_title_'+random_string).rules("add", {
       required: true,
       maxlength: 100,
       messages: {
         required: "Enter title",
         maxlength: "Maximum 100 characters allowed",
       }
    });

});



$(document).on('click', '.files_row_del_btn', function(){  
    
    var element_to_remove=$(this).closest(".files_row");
    element_to_remove.remove();
    
});



$(document).on('change', '.file_list', function() {
    
    var files = this.files;

    var file_size_error=false;
    var file_type_error=false;

    var file_size_in_kb=(files[0].size/2048);
    var file_type= files[0].type;

    if(file_size_in_kb>2048){
       file_size_error=true; 
    }

    var allowed_file_types=[
    'image/jpeg',
    'image/jpg',
    'image/png',
    ];

    if(!allowed_file_types.includes(file_type)){
        file_type_error=true;
    }

    if(file_size_error==true || file_type_error==true){
        reset($('#'+$(this).attr("id")));

        var error_message='';

        if(file_size_error==true && file_type_error==true){
            error_message="Please upload only JPG/JPEG/PNG/SVG files of max size 2mb";
        }else if(file_size_error==true && file_type_error==false){
            error_message="File size should not be more than 2mb";
        }else{
            error_message="Please upload only JPG/JPEG/PNG/SVG files";
        }

        swal(error_message);
    }


});

/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}

 $("document").ready(function(){
    setTimeout(function(){
        $(".alert-success").remove();
    }, 5000 );
});