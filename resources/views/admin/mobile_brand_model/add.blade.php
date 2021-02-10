@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Brand Model Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.mobile_brand_model.list')}}">Brand Model</a></li>
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
                <h3 class="card-title">Create Brand Model</h3>
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
                    <form  method="post" id="admin_model_add_form" action="{{route('admin.mobile_brand_model.add')}}" method="post" enctype="multipart/form-data">
                
                        @csrf
                        <div class="tab-content tab-validate pt-3">
                          <div class="tab-pane active" id="english" role="tabpanel">
                        <div class="form-group required">
                            <label for="name">Model Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):''}}" name="name" id="name"  placeholder="Please Enter Model Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="mobile_brand_id">Brand Name <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" style="width: 100%;" name="mobile_brand_id" id="mobile_brand_id">
                                <option value="">Select a Brand</option>
                                @forelse($mobile_brand_data as $brand_data)
                                   <option value="{{$brand_data->id}}" {{(old('mobile_brand_id')== $brand_data->id)? 'selected':''}}>{{$brand_data->name}}</option>
                                @empty
                               <option value="">No Brand Found</option>
                                @endforelse
            
                              </select>
                            @if($errors->has('mobile_brand_id'))
                            <span class="text-danger">{{$errors->first('mobile_brand_id')}}</span>
                            @endif
                          </div>
                      </div>
                    
                        </div>
                        <div>
                           <a href="{{route('admin.mobile_brand_model.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/mobile_brand_model/create.js')}}"></script>
@endpush
