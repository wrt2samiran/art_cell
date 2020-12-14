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
              <li class="breadcrumb-item"><a href="{{route('admin.shared_services.list')}}">Shared Services</a></li>
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
                <h3 class="card-title">Create Shared Service</h3>
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
                      <form  method="post" id="admin_shared_service_add_form" action="{{route('admin.shared_services.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                          <div class="form-group required">
                            <label for="name">Shared Service Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):''}}" name="name" id="name"  placeholder="Please Enter Country Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="description">Description </label>
                           <textarea rows="5" class="form-control"  name="description" id="description"  placeholder="Description">
                           {{old('description')?old('description'):''}}</textarea>

                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
                            @endif
                          </div>
                          <div class="form-group">
                            
                            <label for="is_sharing" class="">Are you sharing the service ?</label><br>
                            <input type="checkbox" checked id="is_sharing" name="is_sharing" data-bootstrap-switch data-off-color="danger" data-on-color="success"
                            data-off-text="no" data-on-text="yes">
                          </div>

                          <div class="form-group required is_sharing_field">
                            <label for="number_of_days">Number of Days <span class="error">*</span></label>
                            <input type="number" class="form-control" value="{{old('number_of_days')?old('number_of_days'):''}}" name="number_of_days" id="number_of_days"  placeholder="Please Enter Number of Days">
                            @if($errors->has('number_of_days'))
                            <span class="text-danger">{{$errors->first('number_of_days')}}</span>
                            @endif
                          </div>
                          <div class="form-group required is_sharing_field">
                            <label for="price">Price <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('price')?old('price'):''}}" name="price" id="price"  placeholder="Please Enter Price">
                            @if($errors->has('price'))
                            <span class="text-danger">{{$errors->first('price')}}</span>
                            @endif
                          </div>
                          <div class="form-group required is_sharing_field">
                            <label for="extra_price_per_day">Extra Price/Day <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('extra_price_per_day')?old('extra_price_per_day'):''}}" name="extra_price_per_day" id="extra_price_per_day"  placeholder="Please Enter Extra Price/Day">
                            @if($errors->has('extra_price_per_day'))
                            <span class="text-danger">{{$errors->first('extra_price_per_day')}}</span>
                            @endif
                          </div>
                          <div class="form-group">
                            <label for="is_selling" class="">Are you selling the shared service ?</label><br>
                            <input type="checkbox" id="is_selling" name="is_selling" data-bootstrap-switch data-off-color="danger" data-on-color="success"
                            data-off-text="no" data-on-text="yes">
                          </div>
                          <div class="form-group required" id="selling_price_container" style="display: none;">
                            <label for="selling_price">Selling Price <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('selling_price')?old('selling_price'):''}}" name="selling_price" id="selling_price"  placeholder="Please Enter Selling Price">
                            @if($errors->has('selling_price'))
                            <span class="text-danger">{{$errors->first('selling_price')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="images">Images <span class="text-muted">(upload max. 3 images of type jpeg/png/gif)</span></label>
                            <input type="file" class="form-control" name="images[]" id="images" multiple="true" accept="image/jpg,image/jpeg,image/gif">
                            @if($errors->has('images'))
                            <span class="text-danger">{{$errors->first('images')}}</span>
                            @endif
                          </div>
                        </div>
                        <div class="mt-2">
                           <a href="{{route('admin.shared_services.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script src="{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>

<!-- *********Used for CK Editor ***************-->
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
CKEDITOR.replace( 'description' );
</script>
<!-- *********Used for CK Editor ***************-->
<script type="text/javascript" src="{{asset('js/admin/shared_service/create.js')}}"></script>
@endpush