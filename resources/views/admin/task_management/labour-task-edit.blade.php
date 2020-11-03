@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Labour Task Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.task_management.labourTaskList', $sqltaskData->id)}}">Labour Task List</a></li>
              <li class="breadcrumb-item active">Assign Labour Task</li>
            </ol>
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
                <h3 class="card-title">Assign Labour Task</h3>
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
                      <form  method="post" id="admin_labour_assign_form" action="{{route('admin.task_management.editDailyTask', $sqltaskData->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <?php //dd($sqltaskData);?>
                              
                                <div class="form-group required">
                                  <label for="service_id">Task Title <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2"  style="width: 100%;" name="task_id" id="task_id" 
                                    >
                                           <option value="{{$sqltaskData->task_id}}" {{(old('task_id')== $sqltaskData->task_id)? 'selected':''}}>{{@$sqltaskData->task->task_title}}</option>
                                     
                                    </select>
                                  @if($errors->has('task_id'))
                                  <span class="text-danger">{{$errors->first('task_id')}}</span>
                                  @endif
                                </div>
                                
                                <div class="form-group required">
                                  <label for="property_id">Service <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2" style="width: 100%;" name="service_id" id="service_id" >
                                           <option value="{{$sqltaskData->service_id}}" {{(old('service_id')== $sqltaskData->service_id)? 'selected':''}}>{{@$sqltaskData->service->service_name}}</option>
                                    </select>
                                  @if($errors->has('property_id'))
                                  <span class="text-danger">{{$errors->first('property_id')}}</span>
                                  @endif
                                </div>

                                <div class="form-group required">
                                  <label for="service_id">Labour List <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2"  style="width: 100%;" name="user_id" id="user_id" 
                                    >
                                     <option value="">Select Labour</option>
                                     @forelse($labour_list as $labour_data)
                                           <option value="{{$labour_data->id}}" {{($sqltaskData->user_id== $labour_data->id)? 'selected':''}}>{{@$labour_data->name}}</option>
                                      @empty
                                      <option value="">No Labour Found</option>
                                      @endforelse     
                                                               
                                    </select>
                                  @if($errors->has('contract_id'))
                                  <span class="text-danger">{{$errors->first('contract_id')}}</span>
                                  @endif
                                </div>
                                
                                <div class="form-group">
                                  <label>Date range <span class="error">*</span></label>

                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="date_range" name="date_range">
                                  </div>
                                  <!-- /.input group -->
                                </div>
                                

                                <div class="form-group">
                                  <label for="service_id">Task Description</label>
                                  <textarea class="form-control float-right" name="task_description" id="task_description">{{$sqltaskData->task_description}}</textarea>
                                </div>                                            
                            <div>
                           <button type="submit" class="btn btn-success">Submit</button> 
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

    $( document ).ready(function() {
        $('#date_range').daterangepicker({
                 minDate: new Date('<?=$sqltaskData->task->start_date?>'),
                 maxDate: new Date('<?=$sqltaskData->task->end_date?>'),
                 startDate: new Date('<?=$sqltaskData->task_date?>'),
                 endDate: new Date('<?=$sqltaskData->task_date?>'),
              })
    });

    
</script>

<script type="text/javascript" src="{{asset('js/admin/task_management/create.js')}}">

  
</script>
@endpush
