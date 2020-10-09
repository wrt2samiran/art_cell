@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Shared Service Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.shared-service.list')}}">Shared Services</a></li>
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
                <h3 class="card-title">Edit Shared Service</h3>
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
                      <form  method="post" id="admin_shared_service_edit_form" action="{{route('admin.shared-service.edit',$details->id)}}" method="post" >
                        @csrf
                        
                        <div>
                          <div class="form-group required">
                            <label for="name">Service Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):$details->name}}" name="name" id="name"  placeholder="Shared Service Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="description">Description <span class="error">*</span></label>
                             <textarea rows="5" class="form-control"  name="description" id="description"  placeholder="Description">{{old('description')?old('description'):$details->description}}</textarea>
                             @if($errors->has('description'))
                              <span class="text-danger">{{$errors->first('description')}}</span>
                             @endif
                          </div>

                          <div class="form-group required">
                            <label for="number_of_days">Number of Days <span class="error">*</span></label>
                            <input type="number" class="form-control" value="{{old('number_of_days')?old('number_of_days'):$details->number_of_days}}" name="number_of_days" id="number_of_days"  placeholder="Please Enter Number of Days">
                            @if($errors->has('number_of_days'))
                            <span class="text-danger">{{$errors->first('number_of_days')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="price">Price <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('price')?old('price'):$details->price}}" name="price" id="price"  placeholder="Please Enter Price">
                            @if($errors->has('price'))
                            <span class="text-danger">{{$errors->first('price')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="extra_price_per_day">Extra Price/Day <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('extra_price_per_day')?old('extra_price_per_day'):$details->extra_price_per_day}}" name="extra_price_per_day" id="extra_price_per_day"  placeholder="Please Enter Extra Price/Day">
                            @if($errors->has('extra_price_per_day'))
                            <span class="text-danger">{{$errors->first('extra_price_per_day')}}</span>
                            @endif
                          </div>
                          

                        </div>
                        <input type="hidden" name="shared_service_id" id="shared_service_id" value="{{$details->id}}">
                        <div>
                           <a href="{{route('admin.shared-service.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<!-- *********Used for CK Editor ***************-->
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
CKEDITOR.replace( 'description' );
</script>
<!-- *********Used for CK Editor ***************-->
<script type="text/javascript" src="{{asset('js/admin/shared_service/edit.js')}}"></script>
@endpush
