
$('.contract').select2({
    theme: 'bootstrap4',
    placeholder:'Select Contract',
    "language": {
        "noResults": function(){
            return "No Contract Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});
$('.property').select2({
    theme: 'bootstrap4',
    placeholder:'Select Property',
    "language": {
        "noResults": function(){
            return "No Property Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$('#upcoming_weekly_maintenance_sp_or_labour_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select Service Provider/Labour',
    "language": {
        "noResults": function(){
            return "No Service Provider/Labour Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$('#upcoming_weekly_maintenance_service_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select Service',
    "language": {
        "noResults": function(){
            return "No Service Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});


$('.from_date').datepicker({
    dateFormat:'dd/mm/yy'
});
$('.to_date').datepicker({
    dateFormat:'dd/mm/yy'
});


$('#schedule_compliance_property_id').on('change',function(){
    if(this.value){
        $('#schedule_compliance_contract_id').val('').trigger('change');;
    }
});
$('#schedule_compliance_contract_id').on('change',function(){
    
    if(this.value){
        $('#schedule_compliance_property_id').val('').trigger('change');;
    }
});

$('#schedule_compliance_from_date').datepicker({
    dateFormat:'dd/mm/yy',
    maxDate: 0,
});
$('#schedule_compliance_to_date').datepicker({
    dateFormat:'dd/mm/yy',
    maxDate: 0,
});


$("#schedule_compliance_report_form").validate({
    ignore:[],
    rules: {
        property_id:{
            required: function(){
                
                return $('#schedule_compliance_contract_id').val()=='';
            }
        },
        contract_id:{
            required: function(){
            return $('#schedule_compliance_property_id').val()=='';
            } 
        },
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#schedule_compliance_from_date').val();
            }
        },
    },
    messages: {
        property_id: {
            required:  "Select Contract Or Property",
        },
        contract_id:{
            required:  "Select Contract Or Property",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            data: formData,
            url: form.action,
            cache: false,
            contentType: false,
            processData: false,
            responseType: 'blob',
            success: function(response)
            {
                const url = window.URL.createObjectURL(new Blob([response]));
                const link = document.createElement('a');
                link.href = url;

                var from_date=form.from_date.value.replaceAll('/','-');
                var to_date=form.to_date.value.replaceAll('/','-');

                var file_name='completed-schedule-maintenance-from-'+from_date+'-to-'+to_date+'.csv';

                link.setAttribute('download',file_name);
                document.body.appendChild(link);
                link.click();
                $.LoadingOverlay("hide");
                form.reset(); 
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



$('#maintenance_backlog_from_date').datepicker({
    dateFormat:'dd/mm/yy',
    maxDate: 0,
});
$('#maintenance_backlog_to_date').datepicker({
    dateFormat:'dd/mm/yy',
    maxDate: 0,
});
$('#maintenance_backlog_sp_or_labour_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select Service Provider/Labour',
    "language": {
        "noResults": function(){
            return "No Service Provider/Labour Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$("#maintenance_backlog_report_form").validate({
    ignore:[],
    rules: {

        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#maintenance_backlog_from_date').val();
            }
        },
    },
    messages: {

        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            data: formData,
            url: form.action,
            cache: false,
            contentType: false,
            processData: false,
            responseType: 'blob',
            success: function(response)
            {
                const url = window.URL.createObjectURL(new Blob([response]));
                const link = document.createElement('a');
                link.href = url;

                var from_date=form.from_date.value.replaceAll('/','-');
                var to_date=form.to_date.value.replaceAll('/','-');

                var file_name='maintenance-backlog-report-from-'+from_date+'-to-'+to_date+'.csv';

                link.setAttribute('download',file_name);
                document.body.appendChild(link);
                link.click();
                $.LoadingOverlay("hide");
                form.reset(); 
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







$("#work_order_report_form").validate({
    ignore:[],
    rules: {
        work_order_status:{
            required: true
        },
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#work_order_from_date').val();
            }
        },
    },
    messages: {
        work_order_status:{
            required:  "Select Status",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            data: formData,
            url: form.action,
            cache: false,
            contentType: false,
            processData: false,
            responseType: 'blob',
            success: function(response)
            {
                const url = window.URL.createObjectURL(new Blob([response]));
                const link = document.createElement('a');
                link.href = url;

                var from_date=form.from_date.value.replaceAll('/','-');
                var to_date=form.to_date.value.replaceAll('/','-');

                var file_name=form.work_order_status.value+'-work-orders-from-'+from_date+'-to-'+to_date+'.csv';

                link.setAttribute('download',file_name);
                document.body.appendChild(link);
                link.click();
                $.LoadingOverlay("hide");
                form.reset(); 
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
        //form.submit();
    }
});






$("#work_order_completed_per_month_report_form").validate({
    ignore:[],
    rules: {

        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#work_order_completed_per_month_from_date').val();
            }
        },
    },
    messages: {
        work_order_status:{
            required:  "Select Status",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            data: formData,
            url: form.action,
            cache: false,
            contentType: false,
            processData: false,
            responseType: 'blob',
            success: function(response)
            {

                const url = window.URL.createObjectURL(new Blob([response]));
                const link = document.createElement('a');
                link.href = url;

                var from_date=form.from_date.value.replaceAll('/','-');
                var to_date=form.to_date.value.replaceAll('/','-');

                var file_name='completd-work-orders-per-month-from-'+from_date+'-to-'+to_date+'.csv';

                link.setAttribute('download',file_name);
                document.body.appendChild(link);
                link.click();
                $.LoadingOverlay("hide"); 

                form.reset();
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
        //form.submit();
    }
});





$("#work_order_requested_vs_completed_report_form").validate({
    ignore:[],
    rules: {
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#work_order_requested_vs_completed_from_date').val();
            }
        },
    },
    messages: {
        work_order_status:{
            required:  "Select Status",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            data: formData,
            url: form.action,
            cache: false,
            contentType: false,
            processData: false,
            responseType: 'blob',
            success: function(response)
            {
                
                const url = window.URL.createObjectURL(new Blob([response]));
                const link = document.createElement('a');
                link.href = url;

                var from_date=form.from_date.value.replaceAll('/','-');
                var to_date=form.to_date.value.replaceAll('/','-');

                var file_name='work-orders-requested-vs-completd-from-'+from_date+'-to-'+to_date+'.csv';

                link.setAttribute('download',file_name);
                document.body.appendChild(link);
                link.click();
                $.LoadingOverlay("hide"); 
                form.reset();
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
        //form.submit();
    }
});


$('#upcoming_schedule_maintenance_from_date').datepicker({
    dateFormat:'dd/mm/yy',
    minDate: 0,
});

$("#upcoming_schedule_maintenance_report_form").validate({
    ignore:[],
    rules: {
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#upcoming_schedule_maintenance_from_date').val();
            }
        },
    },
    messages: {
        work_order_status:{
            required:  "Select Status",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            data: formData,
            url: form.action,
            cache: false,
            contentType: false,
            processData: false,
            responseType: 'blob',
            success: function(response)
            {
                
                const url = window.URL.createObjectURL(new Blob([response]));
                const link = document.createElement('a');
                link.href = url;

                var from_date=form.from_date.value.replaceAll('/','-');
                var to_date=form.to_date.value.replaceAll('/','-');

                var file_name='upcoming-schedule-maintenance-from-'+from_date+'-to-'+to_date+'.csv';

                link.setAttribute('download',file_name);
                document.body.appendChild(link);
                link.click();
                $.LoadingOverlay("hide"); 
                form.reset();
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
        //form.submit();
    }
});



$('#upcoming_weekly_maintenance_from_date').datepicker({
    dateFormat:'dd/mm/yy',
    minDate: 0,
});


$("#upcoming_weekly_maintenance_report_form").validate({
    ignore:[],
    rules: {
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#upcoming_weekly_maintenance_from_date').val();
            }
        },
    },
    messages: {
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            data: formData,
            url: form.action,
            cache: false,
            contentType: false,
            processData: false,
            responseType: 'blob',
            success: function(response)
            {
                
                const url = window.URL.createObjectURL(new Blob([response]));
                const link = document.createElement('a');
                link.href = url;

                var from_date=form.from_date.value.replaceAll('/','-');
                var to_date=form.to_date.value.replaceAll('/','-');

                var file_name='upcoming-weekly-schedule-maintenance-from-'+from_date+'-to-'+to_date+'.csv';

                link.setAttribute('download',file_name);
                document.body.appendChild(link);
                link.click();
                $.LoadingOverlay("hide"); 
                form.reset();
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
        //form.submit();
    }
});



$("#contract_status_report_form").validate({
    ignore:[],
    rules: {
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#contract_status_from_date').val();
            }
        },
    },
    messages: {
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            data: formData,
            url: form.action,
            cache: false,
            contentType: false,
            processData: false,
            responseType: 'blob',
            success: function(response)
            {
                
                const url = window.URL.createObjectURL(new Blob([response]));
                const link = document.createElement('a');
                link.href = url;

                var from_date=form.from_date.value.replaceAll('/','-');
                var to_date=form.to_date.value.replaceAll('/','-');

                var file_name='contract-report-from-'+from_date+'-to-'+to_date+'.csv';

                link.setAttribute('download',file_name);
                document.body.appendChild(link);
                link.click();
                $.LoadingOverlay("hide"); 
                form.reset();
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
        //form.submit();
    }
});