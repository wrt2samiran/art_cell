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
                      <form  method="post" id="admin_shared_service_edit_form" action="{{route('admin.spare_parts.update',$spare_part->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="name">Spare Part Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):$spare_part->name}}" name="name" id="name"  placeholder="Please Enter Spare Part Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="manufacturer">Manufacturer <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('manufacturer')?old('manufacturer'):$spare_part->manufacturer}}" name="manufacturer" id="manufacturer"  placeholder="Please Enter Manufacturer name">
                            @if($errors->has('manufacturer'))
                            <span class="text-danger">{{$errors->first('manufacturer')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="unit_master_id">Unit <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" style="width: 100%;" name="unit_master_id" id="unit_master_id">
                                <option value="">Select a Unit</option>
                                @forelse($unit_list as $unit_data)
                                   <option value="{{$unit_data->id}}" {{($spare_part->unit_master_id== $unit_data->id)? 'selected':''}}>{{$unit_data->unit_name}}</option>
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
                           <textarea rows="5" class="form-control"  name="description" id="description"  placeholder="Description">{{old('description')?old('description'):$spare_part->description}}</textarea>
                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
                            @endif
                          </div>
                          
                          <div class="form-group required">
                            <label for="price">Price <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('price')?old('price'):$spare_part->price}}" name="price" id="price"  placeholder="Please Enter Price">
                            @if($errors->has('price'))
                            <span class="text-danger">{{$errors->first('price')}}</span>
                            @endif
                          </div>

                          <div class="form-group">
                          <p>
                            <a class="btn  btn-outline-success btn-sm" data-toggle="collapse" href="#collapseImages" role="button" aria-expanded="false" aria-controls="collapseImages">
                            <i class="fas fa-eye text-primary"></i> Uploaded images
                            </a>

                          </p>
                          <div class="collapse" id="collapseImages">
                            <div class="card card-body">
                              <div class="row">
                                @if(count($spare_part->images))
                                @foreach($spare_part->images as $image)
                                    <div class="col-md-3" id="shared_service_image_{{$image->id}}">
                                      <div>
                                          <img height="100%" width="100%" src="{{asset('uploads/spare_part_images/thumb/'.$image->image_name)}}">
                                      </div>

                                    </div>
                                @endforeach
   
                                @else
                                 <div class="col-md-12">
                                   <p>No images</p>
                                 </div>
                                @endif
                                 
                              </div>
                            </div>
                          </div>
                          <label for="images">Images <span class="text-muted">(upload max. 3 images of type jpeg/png/gif)</span></label>
                          <div class="input-group">
                              <input type="file" name="images[]" multiple="1" class="form-control" id="images" accept="image/jpg,image/jpeg,image/gif">
                              
                          </div>
                         
                          @if($errors->get('images.*'))
                           @foreach($errors->get('images.*') as $err)
                            <span class="text-danger">{{$err[0]}}</span><br>
                            @break
                           @endforeach
                          @endif

                          </div>

                         
                        </div>
                        <input type="hidden" name="spare_parts_id" id="spare_parts_id" value="{{$spare_part->id}}">
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
<script type="text/javascript" src="{{asset('js/admin/shared_service/edit.js')}}"></script>
@endpush
