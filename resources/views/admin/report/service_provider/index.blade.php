@extends('admin.layouts.after-login-layout')
@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('service_provider_report_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">

          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <!-- SELECT2 EXAMPLE -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{__('service_provider_report_module.generate_report')}}</h3>
              </div>
              <div class="card-body">
                  @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissable __web-inspector-hide-shortcut__">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{ Session::get('success') }}
                    </div>
                  @endif

                  @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{ Session::get('error') }}
                    </div>
                  @endif
                  <div class="row justify-content-center">
                    <div class="col-md-10 col-sm-12">
                      <form method="post" id="report_form" >
                        @csrf
                          <div>
                          <div class="form-group required">
                             <label for="report_on">{{__('service_provider_report_module.report_on')}}  <span class="error">*</span></label>
                              <select class="form-control " id="report_on" name="report_on" style="width: 100%;" onchange="getAssignedProperty(this.value);">
                                <option value=""> {{__('service_provider_report_module.select_type')}} </option>
                                <option value="work_order">Work Orders</option>
                                <!-- <option value="maintenance_schedule">Maintenance Schedule</option> -->
                              </select>
                          </div>

                          <div class="form-group required">
                             <label for="contract_id">{{__('service_provider_report_module.property')}}<span class="error">*</span></label>
                              <select class="form-control contract" multiple="multiple" size="1" searchable="Search for..." id="property_id" name="property_id[]" style="width: 100%;" onchange="getWorkOderList()">
                              </select>
                          </div>

                          <div class="form-group required">
                             <label for="contract_id">{{__('service_provider_report_module.work_orders')}} <span class="error">*</span></label>
                              <select class="form-control contract" multiple="multiple" size="1" searchable="Search for..." id="work_order_id" name="work_order_id[]" style="width: 100%;" onchange="getTaskList()">
                              </select>
                          </div>

                        

                          <div class="form-group required">
                             <label for="service_status">{{__('service_provider_report_module.task_list')}} <span class="error">*</span></label>
                              <select class="form-control" multiple="multiple" size="1" searchable="Search for..." name="task_id[]" id="task_id" style="width: 100%;" onchange="getServices();">
                               
                              </select>
                          </div>
                          
                          <div class="form-group required">
                             <label for="service_status">{{__('service_provider_report_module.service')}} <span class="error">*</span></label>
                              <select class="form-control" multiple="multiple" size="1" searchable="Search for..." name="service_id[]" id="service_id" style="width: 100%;">
                               
                              </select>
                          </div>

                          <div class="form-group required">
                             <label for="service_status">{{__('service_provider_report_module.task_type')}} <span class="error">*</span></label>
                              <select class="form-control" Placeholder="Select Task Type" name="task_type" id="task_type" style="width: 100%;" onchange="getLabour()">
                                <option value="">{{__('service_provider_report_module.select_task_type')}}</option>
                                <option value="labour_task">Labour Task</option>
                                <option value="main_task">Main Task</option>
                              </select>
                          </div>

                          <div class="form-group required assigned_labour_id" style="display: none;">
                             <label for="service_status">{{__('service_provider_report_module.assigned_labour_list')}} <span class="error">*</span></label>
                              <select class="form-control" multiple="multiple" size="1" searchable="Search for..." name="labour_id[]" id="labour_id" style="width: 100%;">
                               
                              </select>
                          </div>

                          <div class="form-group required">
                             <label for="service_status">{{__('service_provider_report_module.task_status')}} <span class="error">*</span></label>
                              <select class="form-control" Placeholder="Select Task Status" multiple="multiple" size="1" searchable="Search for..."  name="task_status[]" id="task_status" style="width: 100%;">
                                <option value="0">Pending</option>
                                <option value="1">Over Due</option>
                                <option value="2">Completed</option>
                              </select>
                          </div>
                            
                          <div class=" form-group required">
                           <label for="from_date">{{__('service_provider_report_module.date_form')}} <span class="error">*</span></label>
                           <input type="text" readonly="readonly" autocomplete="off" id="from_date" class="form-control" name="from_date">
                          </div>
              
                          <div class="form-group required">
                             <label for="to_date">{{__('service_provider_report_module.date_to')}} <span class="error">*</span></label>
                             <input type="text"  readonly="readonly" autocomplete="off" class="form-control" id="to_date" name="to_date">
                          </div>

                          <div class="mb-3">
                              <label >Download As &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">Excel
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">PDF
                                </label>
                              </div>
                              
                        </div>
                        <div>
                           
                          <button type="submit" class="btn btn-success">{{__('service_provider_report_module.generate_report')}}</button> 
                        </div>
                      </form>
                    </div>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </section>
    
</div>
@endsection 
@push('custom-scripts')
<script type="text/javascript">
$('#task_status').multiselect({
    columns: 1,
    placeholder: 'Select Work Order',
    search: true,
    selectAll: true
    });


// $('#task_id').multiselect({
//     columns: 1,
//     placeholder: 'Select Task',
//     search: true,
//     selectAll: true
//     });


// $('#service_id').multiselect({
//     columns: 1,
//     placeholder: 'Select Service',
//     search: true,
//     selectAll: true
//     });



function getAssignedProperty(report_on){
    
    $.ajax({
   
    url: "{{route('admin.reports.getAssignedProperty')}}",
    type:'get',
    dataType: "json",
    data:{report_on:report_on,_token:"{{ csrf_token() }}"}
    }).done(function(response) {
       
       console.log(response.status);
        if(response.status==true){
         
        var stringifiedProperty = JSON.stringify(response.property_list);
        var propertyData = JSON.parse(stringifiedProperty);
        console.log(propertyData);
         var property_list = '';
         $.each(propertyData,function(index, property){
                property_list += '<option value="'+property.id+'">'+ property.property_name +'</option>';
         });
            
            $('#property_id').multiselect({
            columns: 1,
            placeholder: 'Select Property',
            search: true,
            selectAll: true,
            
            });
            $("#property_id").html(property_list).multiselect("reload");
        }

      else
        {
            var property_list = '';
            $("#property_id").html(property_list).multiselect("refresh");
        }
    });
}


function getWorkOderList(){
    
    var property_id =  $('#property_id').val();

    $.ajax({
   
    url: "{{route('admin.reports.getWorkOderList')}}",
    type:'get',
    dataType: "json",
    data:{property_id:property_id,_token:"{{ csrf_token() }}"}
    }).done(function(response) {
       
       console.log(response.status);
        if(response.status==true){
         
        var stringified = JSON.stringify(response.allWorkOrders);
        var workOrderData = JSON.parse(stringified);
        console.log(workOrderData);
         var workorder_list = '';
         $.each(workOrderData,function(index, workorder){
                workorder_list += '<option value="'+workorder.id+'">'+ workorder.task_title +'</option>';
         });
            
            $('#work_order_id').multiselect({
            columns: 1,
            placeholder: 'Select Work Order',
            search: true,
            selectAll: true,
            
            });
            $("#work_order_id").html(workorder_list).multiselect("reload");
        }

      else
        {
            var workorder_list = '';
            $("#work_order_id").html(workorder_list).multiselect("refresh");
        }
    });
}

function getTaskList()
{
  var work_order_id =  $('#work_order_id').val();
  $.ajax({
   
    url: "{{route('admin.reports.getTaskList')}}",
    type:'get',
    dataType: "json",
    data:{work_order_id:work_order_id,_token:"{{ csrf_token() }}"}
    }).done(function(response) {
       
       console.log(response.status);
        if(response.status==true){
         
        var stringified = JSON.stringify(response.allTasks);
        var taskData = JSON.parse(stringified);
        console.log(taskData);
         var task_list = '';
         $.each(taskData,function(index, task){
                task_list += '<option value="'+task.id+'">'+ task.task_title +'</option>';
         });
            $('#task_id').multiselect({
            columns: 1,
            placeholder: 'Select Work Order',
            search: true,
            selectAll: true,
            });
            $("#task_id").html(task_list).multiselect("reload");
        }

      else
        {
            var task_list = '';
            $("#task_id").html(task_list).multiselect("refresh");
        }
    });

}
    

function getServices()
{
  var task_id =  $('#task_id').val();
  $.ajax({
   
    url: "{{route('admin.reports.getServices')}}",
    type:'get',
    dataType: "json",
    data:{task_id:task_id,_token:"{{ csrf_token() }}"}
    }).done(function(response) {
       
       console.log(response.status);
        if(response.status==true){
         
        var stringified = JSON.stringify(response.allServices);
        var serviceData = JSON.parse(stringified);
        console.log(serviceData);
         var service_list = '';
         $.each(serviceData,function(index, serviceValue){
                service_list += '<option value="'+serviceValue.service.id+'">'+ serviceValue.service.service_name +'</option>';
         });
            $('#service_id').multiselect({
            columns: 1,
            placeholder: 'Select Work Order',
            search: true,
            selectAll: true,
            });
            $("#service_id").html(service_list).multiselect("reload");
        }

      else
        {
            var workorder_list = '';
            $("#service_id").html(service_list).multiselect("refresh");
        }

        getLabour();
    });

}   
 

function getLabour()
{
  var task_type = $('#task_type').val();
  var task_id =  $('#task_id').val();
  if(task_type=='labour_task' && task_id.length>0)
  {
    $('.assigned_labour_id').show();
  }
  else
  {
    $('.assigned_labour_id').hide();
  }
  
  $.ajax({
   
    url: "{{route('admin.reports.getLabourList')}}",
    type:'get',
    dataType: "json",
    data:{task_id:task_id,task_type:task_type,_token:"{{ csrf_token() }}"}
    }).done(function(response) {
       
       console.log(response.status);
        if(response.status==true){
         
        var stringified = JSON.stringify(response.allAssignedLabours);
        var labourData = JSON.parse(stringified);
        console.log(labourData);
         var labour_list = '';
         $.each(labourData,function(index, labourData){
                labour_list += '<option value="'+labourData.id+'">'+ labourData.name +'</option>';
         });
            $('#labour_id').multiselect({
            columns: 1,
            placeholder: 'Select Labour',
            search: true,
            selectAll: true,
            });
            $("#labour_id").html(labour_list).multiselect("reload");
        }

      else
        {
            var labour_list = '';
            $("#labour_id").html(labour_list).multiselect("reload");
        }
    });

}   


$('#from_date').datepicker({
    dateFormat:'dd/mm/yy'
});
$('#to_date').datepicker({
    dateFormat:'dd/mm/yy'
});




// $('#property_id').on('change',function(){
//     if(this.value){
//         $('#contract_id').val('').trigger('change');;
//     }
// });
// $('#contract_id').on('change',function(){
    
//     if(this.value){
//         $('#property_id').val('').trigger('change');;
//     }
// });

$("#report_form").validate({
    rules: {
        
        report_on:{
            required: true, 
        },
        'property_id[]': {
          required: true,
        },
        'work_order_id[]': {
          required: true,
        },

        'task_id[]':{
            required: true, 
        },
        'service_id[]':{
            required: true, 
        },
        'service_id[]':{
            required: true, 
        },
        task_type:{
            required: true, 
        },
        'labour_id[]':{
            required : $("#task_type").val()=='labour_id', 
        },
        'task_status[]':{
            required: true,
        },
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldBeGreatherThanFromDate:function(){
                return $('#to_date').val();
            }
        },
    },
    messages: {
        report_on:{
            required: "Select Report On", 
        },
        'property_id[]': {
          required: "Select Property",
        },
        'work_order_id[]': {
          required: "Select Work Order",
        },

        'task_id[]':{
            required: "Select Tasks List", 
        },
        'service_id[]':{
            required: "Select Services", 
        },
       
        task_type:{
            required: "Select Task Type", 
        },

        'labour_id[]':{
            required: "Select Services", 
        },
       
        'task_status[]':{
            required: "Select Task Status",
        },
        from_date:{
            required:  "Enter from date in dd/mm/yyy format",
        },
        to_date:{
            required:  "Enter to date in dd/mm/yyy format",
            toDateShouldBeGreatherThanFromDate : "TO date should be greater than from date"
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
        form.submit();
        $.LoadingOverlay("hide");
        form.reset();

    }
});


</script>
@endpush
