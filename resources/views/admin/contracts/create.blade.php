@extends('admin.layouts.after-login-layout')


@section('unique-content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Contract Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.contracts.list')}}">Contracts</a></li>
              <li class="breadcrumb-item active">Create</li>
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
                <h3 class="card-title">Create Contract</h3>
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
                    @include('admin.contracts.partials.multi_step_links')
                    <div class="col-md-11 col-sm-12">
                      <form  method="post" id="admin_contract_create_form" action="{{route('admin.contracts.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                          <div class="form-group required">
                            <label for="title">Contract Title <span class="error">*</span></label>
                            <input type="text" value="{{old('title')?old('title'):''}}" class="form-control" name="title" id="title"  placeholder="Contract Title" />
                            @if($errors->has('title'))
                            <span class="text-danger">{{$errors->first('title')}}</span>
                            @endif
                          </div>
           
                          <div class="form-group required">
                            <label for="description">Description<span class="error">*</span></label>
                           <textarea rows="5" class="form-control CKEDITOR"  name="description" id="description"  placeholder="Description">{{old('description')?old('description'):''}}</textarea>

                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
                            @endif
                            <div id="description_error"></div>
                          </div>
                          <div class="form-group required">
                             <label for="property">Select Property <span class="error">*</span></label>
                              <select class="form-control " id="property" name="property" style="width: 100%;">
                                <option value="">Select property</option>
                                @forelse($properties as $property)
                                   <option value="{{$property->id}}" >{{$property->property_name}}({{$property->code}})</option>
                                @empty
                                <option value="">No Property Found</option>
                                @endforelse
                              </select>
                          </div>

                          <div class="form-group required">
                             <label for="service_provider">Select service provider <span class="error">*</span></label>
                              <select class="form-control " id="service_provider" name="service_provider" style="width: 100%;">
                                <option value="">Select service provider</option>
                                @forelse($service_providers as $service_provider)
                                   <option value="{{$service_provider->id}}" >{{$service_provider->name}} ({{$service_provider->email}})</option>
                                @empty
                                <option value="">No Service Provider Found</option>
                                @endforelse 
                              </select>
                          </div>
      

                          <div class="form-group required">
                            <label for="start_date">Start Date <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('start_date')?old('start_date'):''}}" name="start_date" id="start_date" autocomplete="off"  placeholder="Start Date">
                            @if($errors->has('start_date'))
                            <span class="text-danger">{{$errors->first('start_date')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="end_date">End Date <span class="error">*</span></label>
                            <input type="text" autocomplete="off"  class="form-control" value="{{old('end_date')?old('end_date'):''}}" name="end_date" id="end_date"  placeholder="End Date">
                            @if($errors->has('end_date'))
                            <span class="text-danger">{{$errors->first('end_date')}}</span>
                            @endif
                          </div>


                          <input type="hidden" id="property_create_url" value="{{route('admin.properties.create')}}">

                          <input type="hidden" id="service_provider_create_url" value="{{route('admin.users.create')}}">

                          <input type="hidden" id="property_owner_create_url" value="{{route('admin.users.create')}}">
                        </div>
                        <div>
                           <a href="{{route('admin.contracts.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
                           <button type="submit" class="btn btn-success">Submit & Next &nbsp;<i class="fas fa-forward"></i></button> 
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
<!-- *********Used for CK Editor ***************-->
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
  $(document).ready(function(){
    CKEDITOR.replace('description');
  });

</script>
<script type="text/javascript" src="{{asset('js/admin/contracts/create.js')}}"></script>
@endpush
