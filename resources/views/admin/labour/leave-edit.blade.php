@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.leaveList')}}">Labour Leave</a></li>
              <li class="breadcrumb-item active">Edit</li>
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
                <h3 class="card-title">Edit Labour Leave</h3>
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
                      <form  method="post" id="labour_edit_form" action="{{route('admin.updateLeave',$leaveData->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="first_name">Labour Name <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" style="width: 100%;" name="labour_id" id="labour_id" >
                                <option value="">Select Labour</option>
                                @foreach($labourList as $labourData)
                                   <option value="{{@$labourData->id}}" {{(@$leaveData->labour_id== @$labourData->id)? 'selected':''}}>{{@$labourData->name}}</option>
                                @endforeach   
                            </select>
                            @if($errors->has('labour_id'))
                            <span class="text-danger">{{$errors->first('labour_id')}}</span>
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
                              <input type="text" class="form-control" id="date_range" name="date_range">
                            </div>
                            <!-- /.input group -->
                          </div>

                          <div class="form-group">
                            <label for="service_id">Reason</label>
                            <textarea class="form-control" name="leave_reason" id="task_description">{{@$leaveData->leave_reason}}</textarea>
                          </div>
                        <!--  this the url for remote validattion rule for user email -->
                          <div class="form-group">
                             <a href="{{route('admin.labour.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
                             <button type="submit" class="btn btn-success">Submit</button> 
                          </div>
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

         startDate: new Date('<?=$leaveData->leave_start?>'),
         endDate: new Date('<?=$leaveData->leave_end?>'),
         dateFormat: 'dd/mm/yy',
             
          })
    });

  $('#labour_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select Labour'
});
</script>
<script type="text/javascript" src="{{asset('js/admin/labour/edit.js')}}"></script>
@endpush
