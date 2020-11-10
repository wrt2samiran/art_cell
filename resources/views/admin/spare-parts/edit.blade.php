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
              <li class="breadcrumb-item"><a href="{{route('admin.shared-service.list')}}">Spare Parts</a></li>
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
                <h3 class="card-title">Edit Spare Parts</h3>
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
                      <form  method="post" id="admin_shared_service_edit_form" action="{{route('admin.spare-parts.edit',$details->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        <div>
                          <div class="form-group required">
                            <label for="name">Spare Part Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):$details->name}}" name="name" id="name"  placeholder="Please Enter Spare Part Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="manufacturer">Manufacturer <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('manufacturer')?old('manufacturer'):$details->manufacturer}}" name="manufacturer" id="manufacturer"  placeholder="Please Enter Manufacturer name">
                            @if($errors->has('manufacturer'))
                            <span class="text-danger">{{$errors->first('manufacturer')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="unit_master_id">Unit <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" style="width: 100%;" name="unit_master_id" id="unit_master_id">
                                <option value="">Select a Unit</option>
                                @forelse($unit_list as $unit_data)
                                   <option value="{{$unit_data->id}}" {{($details->unit_master_id== $unit_data->id)? 'selected':''}}>{{$unit_data->unit_name}}</option>
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
                           <textarea rows="5" class="form-control"  name="description" id="description"  placeholder="Description">{{old('description')?old('description'):$details->description}}</textarea>
                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
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
                            <label for="quantity_available">Quantity Available <span class="error">*</span></label>
                            <input type="number" min="0" step="1" class="form-control" value="{{old('quantity_available')?old('quantity_available'):$details->quantity_available}}" name="quantity_available" id="quantity_available"  placeholder="Quantity Available">
                            @if($errors->has('quantity_available'))
                            <span class="text-danger">{{$errors->first('quantity_available')}}</span>
                            @endif
                          </div>

                          <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <br>
                              
                                  @php
                                  $imgPath = \URL:: asset('images').'/admin/'.Helper::NO_IMAGE;
                                  if ($details->image != null) {
                                      if(file_exists(public_path('/uploads/sparepart/'.'/'.$details->image))) {
                                      $imgPath = \URL::asset('uploads/sparepart/').'/'.$details->image;
                                      }
                                  }
                                  @endphp
                                  <img src="{{ $imgPath }}" alt="" height="50px">
                            
                          </div>
                         
                        </div>
                        <input type="hidden" name="spare_parts_id" id="spare_parts_id" value="{{$details->id}}">
                        <div>
                           <a href="{{route('admin.spare-parts.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
