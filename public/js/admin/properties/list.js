//initializing galleries datatable
    var property_table=$('#property_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: {
          url:$('#properties_data_url').val(),
          data: function (d) {
            d.city_id = $('#city_id').val();
            d.property_name = $('#property_name').val();
          }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'code', name: 'code'},
            { data: 'property_name', name: 'property_name'},
            { data: 'city.name', name: 'city.name'},
            { data: 'is_active', name: 'is_active',orderable: false },
            { data: 'created_at', name: 'created_at' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
         order: [ [0, 'desc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }],
        "drawCallback": function( settings ) {
          $.LoadingOverlay("hide");
      }

    });

 //function to delete property
 function delete_property(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this property!",
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
          property_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Property successfully deleted.', 'Success', {timeOut: 5000});
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
 function change_status(url,activate_or_deactivate){
  swal({
  title: "Are you sure?",
  text: "You want to "+activate_or_deactivate+" the property.",
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
          property_table.ajax.reload(null, false);
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

   
    // window.location.href=url;
    } 
  });

  

 }

 $('.city-name').select2({
  theme: 'bootstrap4',
  placeholder:'Filter by City'
});
$('#city_id').on('change', function(e) {
  if(this.value!=''){
    $('#city-name-clear').show();
  }else{
    $('#city-name-clear').hide();
  }
  
  $.LoadingOverlay("show");
  property_table.draw();
});
$('#city-name-clear').on('click',function(){
  $('#city_id').val("").change();
});

$('.property-name').select2({
  theme: 'bootstrap4',
  placeholder:'Filter by City'
});
$('#property_name').on('change', function(e) {
  if(this.value!=''){
    $('#property-name-clear').show();
  }else{
    $('#property-name-clear').hide();
  }
  
  $.LoadingOverlay("show");
  property_table.draw();
});
$('#property-name-clear').on('click',function(){
  $('#property_name').val("").change();
});

 