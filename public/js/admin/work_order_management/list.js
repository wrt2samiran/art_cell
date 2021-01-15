//initializing galleries datatable
    var work_order_management_table=$('#work_order_management_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: {
          url:baseUrl+'/admin/work-order-management',
          data: function (d) {
            d.status = $('#status').val();
            d.daterange = $('#daterange').val();
          }
        },

        columns: [
            { data: 'id', name: 'id' },
            { data: 'contract.code', name: 'contract.code'},
            { data: 'task_title', name: 'task_title'},
            { data: 'service_type', name: 'service_type', searchable: false, sortable : false},


            { data: 'property.property_name', name: 'property.property_name' },
            { data: 'service.service_name', name: 'service.service_name', searchable: false, sortable : false },
            
            { data: 'property',
              render: function(data){
                      return data.country.name+', '+
                      data.state.name+', '+data.city.name;
                  }, searchable: false, sortable : false
            }, 
            { data: 'start_date', name: 'start_date' },
            { data: 'task_assigned', render:function(data){
                if(data=='Y')
                {
                  return 'Yes'
                }
                else
                {
                  return 'No'
                }
            }},

            
            { data: 'work_order_complete_percent', render:function(data){
                if(data>0)
                  {
                   return '<div class="progress"><div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="'+data+'" aria-valuemin="0" aria-valuemax="100" style="width:'+data+'%">'+data+'% </div></div>'
                  }
                else
                  {
                    return '<div class="progress"><div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0% </div></div>'
                  }  
                }},
            { data: 'status', name: 'status', searchable: false, sortable : false },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
         order: [ [0, 'desc'] ],
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

 $('#status').change(function(){
        work_order_management_table.draw();
    });

 $('.status-filter').select2({
      theme: 'bootstrap4',
    });

    $('#contract_duration').daterangepicker({
      autoUpdateInput: false,
      timePicker: false,
      timePicker24Hour: true,
      timePickerIncrement: 1,
      startDate: moment().startOf('hour'),
      //endDate: moment().startOf('hour').add(24, 'hour'),
      locale: {
          format: 'YYYY-MM-DD'
      }
    }, dateRangeCallback);


    function dateRangeCallback(start_date, end_date){
      $('#daterange').val(start_date.format('YYYY-MM-DD') + '_' + end_date.format('YYYY-MM-DD'));
        
      //$.LoadingOverlay("show");
        work_order_management_table.draw();
        

      
      $('#contract_duration').val(start_date.format('YYYY-MM-DD') + '-' + end_date.format('YYYY-MM-DD'));
      
    }

 $("document").ready(function(){
    setTimeout(function(){
        $(".alert-success").remove();
    }, 5000 );
});