$("#admin_labour_task_add_form").validate({
        rules: {

            
            job_title: {
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
            job_title: {
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

//initializing galleries datatable
    var service_management_table=$('#service_management_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: baseUrl+'/admin/service_management',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'service_name', name: 'service_name'},
            { data: 'property.property_name', name: 'property.property_name'},
            { data: 'service_start_date', name: 'service_start_date'},
            { data: 'service_end_date', name: 'service_end_date'},
            { data: 'work_status', name: 'work_status' },
           
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
         order: [ [0, 'asc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }]

    });



//function to change status of gallery
 function change_status(url,activate_or_deactivate){
  swal({
  title: "Are you sure?",
  text: "You want to "+activate_or_deactivate+" the city.",
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

     service_management_table.ajax.reload(null, false);
    // window.location.href=url;
    } 
  });


 }

 $("document").ready(function(){
    setTimeout(function(){
        $(".alert-success").remove();
    }, 5000 );
});