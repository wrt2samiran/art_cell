
    var spare_parts_for_order_table=$('#spare_parts_for_order_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: $('#spare_parts_for_order_data_url').val(),
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name'},
            { data: 'manufacturer', name: 'manufacturer'},
            { data: 'quantity_available', name: 'quantity_available'},
            { 
                data: "price",
                render: function ( data, type, row, meta ) {
                return row['currency'] + ' ' + data+' / '+row.unitmaster.unit_name ;
                } 
            },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
         order: [ [0, 'asc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }]

    });

    var spare_parts_ordered_table=$('#spare_parts_ordered_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: $('#spare_parts_ordered_data_url').val(),
        columns: [
            { data: 'id', name: 'id' },
            { data: 'ordered_spare_parts_count', name: 'ordered_spare_parts_count'},
            { data: 'total_amount', name: 'total_amount'},
            { data: 'curent_status', name: 'curent_status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [ [0, 'asc'] ],
    });


function order_details(url){

    $.LoadingOverlay("show");
    $.ajax({
        url: url,
        type: "GET",
        data:{ },
        success: function (data) {
            
            $('#order_details_modal_body').html(data.html);
            $('#order_details_modal').modal('show');
            $.LoadingOverlay("hide");
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

$('.update_quantity_field').on('change blur',function(){
    var cart_id=$(this).data('cart_id');
    $('#cart_update_'+cart_id).show();
});


$("#spare_part_checkout_form").validate({
    rules: {
        first_name:{
            required: true,
            minlength: 2,
            maxlength: 100,  
        },
        last_name:{
            required: true,
            minlength: 2,
            maxlength: 100,  
        },
        contact_number:{
            required: true,
            minlength: 8,
            maxlength: 20,
            number:true  
        },
        address_line_1:{
            required: true,
            minlength: 2,
            maxlength: 255,  
        },
        address_line_2:{
            minlength: 2,
            maxlength: 255,  
        },
        pincode:{
            required:true,
            minlength: 4,
            maxlength: 10,  
        },
        city_id:{
            required:true
        }
    },
    messages: {
        first_name: {
            required:  "First name is required",
            minlength: "First name should have 2 characters",
            maxlength: "First name should not be more then 100 characters",
        },
        last_name: {
            required:  "Last name is required",
            minlength: "Last name should have 2 characters",
            maxlength: "Last name should not be more then 100 characters",
        },
        contact_number: {
            required:  "Contact number is required",
            minlength: "Contact number should have minimum 8 characters",
            maxlength: "Contact number should not be more then 20 characters",
            number:"Only number allowed"
        },
        address_line_1: {
            required:  "Address line 1 is required",
            minlength: "Address line 1 should have 2 characters",
            maxlength: "Address line 1 should not be more then 100 characters",
        },
        address_line_2: {
            minlength: "Address line 2 should have 2 characters",
            maxlength: "Address line 2 should not be more then 100 characters",
        },
        city_id:{
            required:'Select city'
        },
        pincode:{
            required:'Pincode is required'
        }
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