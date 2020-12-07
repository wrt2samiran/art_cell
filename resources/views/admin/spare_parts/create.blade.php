@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Spare Parts Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.spare_parts.list')}}">Spare Parts</a></li>
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
                <h3 class="card-title">Create Spare Part</h3>
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
                      <form  method="post" id="admin_spare_parts_add_form" action="{{route('admin.spare_parts.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                          <div class="form-group required">
                            <label for="name">Spare Part Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):''}}" name="name" id="name"  placeholder="Please Enter Spare Part Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="manufacturer">Manufacturer <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('manufacturer')?old('manufacturer'):''}}" name="manufacturer" id="manufacturer"  placeholder="Please Enter Manufacturer name">
                            @if($errors->has('manufacturer'))
                            <span class="text-danger">{{$errors->first('manufacturer')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="unit_master_id">Unit <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" style="width: 100%;" name="unit_master_id" id="unit_master_id">
                                <option value="">Select a Unit</option>
                                @forelse($unit_list as $unit_data)
                                   <option value="{{$unit_data->id}}" {{(old('unit_master_id')== $unit_data->id)? 'selected':''}}>{{$unit_data->unit_name}}</option>
                                @empty
                               <option value="">No Unit Found</option>
                                @endforelse
            
                              </select>
                            @if($errors->has('unit_master_id'))
                            <span class="text-danger">{{$errors->first('unit_master_id')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="description">Description </label>
                           <textarea rows="5" class="form-control"  name="description" id="description"  placeholder="Description">{{old('description')?old('description'):''}}</textarea>
                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
                            @endif
                          </div>
                          
                          <div class="form-group required">
                            <label for="price">Price <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('price')?old('price'):''}}" name="price" id="price"  placeholder="Please Enter Price">
                            @if($errors->has('price'))
                            <span class="text-danger">{{$errors->first('price')}}</span>
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
                        <div>
                           <a href="{{route('admin.spare_parts.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/spare_parts/create.js')}}"></script>
@endpush
