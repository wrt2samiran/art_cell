@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sate Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.state.list')}}">State</a></li>
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
                <h3 class="card-title">Create State</h3>
              </div>
              <div class="card-body">
              @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-dismissable alert-{{ $msg }}">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <span>{{ Session::get('alert-' . $msg) }}</span><br/>
                            </div>
                        @endif
                    @endforeach

                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            @foreach ($errors->all() as $error)
                                <span>{{ $error }}</span><br/>
                            @endforeach
                        </div>
                    @endif
                  <div class="row justify-content-center">
                    <div class="col-md-10 col-sm-12">
                    <form  method="post" id="admin_state_edit_form" action="{{route('admin.state.edit', $details->id)}}" method="post" enctype="multipart/form-data">
                        
                        @csrf
                        <div class="tab-content tab-validate pt-3">
                          <div class="tab-pane active" id="english" role="tabpanel">
                        <div class="form-group required">
                            <label for="name">State Name<span class="error">*</span></label>
                            <input type="text" class="form-control" value="@if(isset($details->name)){{$details->name}}@endif" name="name" id="name"  placeholder="Please Enter Country Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="country_id">Country <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" style="width: 100%;" name="country_id" id="country_id">
                                <option value="">Select a Country</option>
                                @forelse($country_list as $country_data)
                                   <option value="{{$country_data->id}}" {{($details->country_id==$country_data->id)?'selected':''}} >{{$country_data->name}}</option>
                                @empty
                               <option value="">No Country Found</option>
                                @endforelse
            
                              </select>
                            @if($errors->has('country_id'))
                            <span class="text-danger">{{$errors->first('country_id')}}</span>
                            @endif
                          </div>

                        </div>
                        <div>
                           
                      </div>
                    
                        </div>
                        <div>
                        <input type="hidden" name="state_id" id="state_id" value="{{$details->id}}">
                           <a href="{{route('admin.state.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/state/edit.js')}}"></script>
@endpush
