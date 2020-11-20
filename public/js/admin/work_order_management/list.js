//initializing galleries datatable
    var work_order_management_table=$('#work_order_management_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: baseUrl+'/admin/work-order-management',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'contract.code', name: 'contract.code'},
            { data: 'task_title', name: 'task_title'},
            { data: 'property.property_name', name: 'property.property_name' },
            { data: 'service.service_name', name: 'service.service_name' },
            { data: 'property.country.name', name: 'property.country.name' },
            { data: 'property.state.name', name: 'property.state.name' },
            { data: 'property.city.name', name: 'property.city.name' },

            { data: 'start_date', name: 'start_date' },
            

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

 //function to delete city
 function delete_task(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this task!",
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
          toastr.success('Task successfully deleted.', 'Success', {timeOut: 5000});
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

     work_order_management_table.ajax.reload(null, false);


    } 
  });

 }

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

     work_order_management_table.ajax.reload(null, false);
    // window.location.href=url;
    } 
  });


 }

 $("document").ready(function(){
    setTimeout(function(){
        $(".alert-success").remove();
    }, 5000 );
});