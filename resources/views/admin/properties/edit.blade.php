@extends('admin.layouts.after-login-layout')


@section('unique-content')
@php $current_user=auth()->guard('admin')->user(); @endphp
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('property_manage_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.properties.list')}}">{{__('general_sentence.breadcrumbs.properties')}}</a></li>
              <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.edit')}}</li>
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
                <h3 class="card-title">{{__('property_manage_module.edit_property')}}</h3>
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
                      <form  method="post" id="admin_property_edit_form" action="{{route('admin.properties.update',$property->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @php
                        $max_filesize=(isset($setting['contract-and-property-max-filesize']))?$setting['contract-and-property-max-filesize']:'1';
                        @endphp
                        <input type="hidden" value="{{$max_filesize}}"  id="max_filesize">
                        <div>
                          <div class="form-group required">
                            <label for="property_name">{{__('property_manage_module.labels.property_name')}} <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('property_name')?old('property_name'):$property->property_name}}" name="property_name" id="property_name"  placeholder="{{__('property_manage_module.placeholders.property_name')}}">
                            @if($errors->has('property_name'))
                            <span class="text-danger">{{$errors->first('property_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="property_type_id">{{__('property_manage_module.labels.property_type')}} <span class="error">*</span></label>
                              <select class="form-control " id="property_type_id" name="property_type_id" style="width: 100%;">
                                <option value="">{{__('property_manage_module.placeholders.property_type')}}</option>
                                @forelse($property_types as $property_type)
                                   <option value="{{$property_type->id}}"
                                    {{($property_type->id==$property->property_type_id)?'selected':''}}
                                    >{{$property_type->type_name}}</option>
                                @empty
                                <option value="">No Property Type Found</option>
                                @endforelse
                              </select>
                          </div>
                          <div class="form-group required">
                            <label for="description">{{__('property_manage_module.labels.description')}} <span class="error">*</span></label>
                            <textarea class="form-control" name="description" id="description"  placeholder="{{__('property_manage_module.placeholders.description')}}">{!!old('description')?old('description'):$property->description !!}</textarea>
                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
                            @endif
                          </div>
                          <div class="row">
                            <div class="col-md-6 form-group">
                              <label for="no_of_active_units">{{__('property_manage_module.labels.no_of_active_units')}} <span class="error">*</span></label>
                              <input type="number" min="1" class="form-control" value="{{old('no_of_active_units')?old('no_of_active_units'):$property->no_of_active_units}}" name="no_of_active_units" id="no_of_active_units"  placeholder="{{__('property_manage_module.placeholders.no_of_active_units')}}">
                              @if($errors->has('no_of_active_units'))
                              <span class="text-danger">{{$errors->first('no_of_active_units')}}</span>
                              @endif
                            </div>
                            <div class="col-md-6 form-group">
                              <label for="no_of_inactive_units">{{__('property_manage_module.labels.no_of_inactive_units')}}<span class="error">*</span></label>
                              <input type="number" min="0" class="form-control" value="{{old('no_of_inactive_units')?old('no_of_inactive_units'):$property->no_of_inactive_units}}" name="no_of_inactive_units" id="no_of_inactive_units"  placeholder="{{__('property_manage_module.placeholders.no_of_inactive_units')}}">
                              @if($errors->has('no_of_inactive_units'))
                              <span class="text-danger">{{$errors->first('no_of_inactive_units')}}</span>
                              @endif
                            </div>
                        </div>
                          <div class="form-group required">
                             <label for="city_id">{{__('property_manage_module.labels.city')}} <span class="error">*</span></label>
                              <select class="form-control " id="city_id" name="city_id" style="width: 100%;">
                                <option value="">{{__('property_manage_module.placeholders.city')}}</option>
                                @forelse($cities as $city)
                                   <option value="{{$city->id}}" {{($city->id==$property->city_id)?'selected':''}} >{{$city->name}}</option>
                                @empty
                                <option value="">No City Found</option>
                                @endforelse
                              </select>
                          </div>
                          <div class="form-group required">
                            <label for="address">{{__('property_manage_module.labels.address')}} <span class="error">*</span></label>
                            <textarea class="form-control" name="address" id="address"  placeholder="{{__('property_manage_module.placeholders.address')}}">{!!old('address')?old('address'):$property->address!!}</textarea>
                            @if($errors->has('address'))
                            <span class="text-danger">{{$errors->first('address')}}</span>
                            @endif
                          </div>                           
                          <div class="form-group required">
                            <label for="location">{{__('property_manage_module.labels.location')}} <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('location')?old('location'):$property->location}}" name="location" id="location"  placeholder="{{__('property_manage_module.placeholders.location')}}">
                            @if($errors->has('location'))
                            <span class="text-danger">{{$errors->first('location')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="contact_number">{{__('property_manage_module.labels.contact_number')}}</label>
                            <input type="text" class="form-control" value="{{old('contact_number')?old('contact_number'):$property->contact_number}}" name="contact_number" id="contact_number"  placeholder="{{__('property_manage_module.placeholders.contact_number')}}">
                            @if($errors->has('contact_number'))
                            <span class="text-danger">{{$errors->first('contact_number')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="contact_email">{{__('property_manage_module.labels.contact_email')}}</label>
                            <input type="text" class="form-control" value="{{old('contact_email')?old('contact_email'):$property->contact_email}}" name="contact_email" id="contact_email"  placeholder="{{__('property_manage_module.placeholders.contact_email')}}">
                            @if($errors->has('contact_email'))
                            <span class="text-danger">{{$errors->first('contact_email')}}</span>
                            @endif
                          </div>
                          @if ($current_user->role->user_type->slug != 'property-owner')
                          <div class="form-group required">
                             <label for="property_owner">{{__('property_manage_module.labels.property_owner')}} <span class="error">*</span></label>
                              <select class="form-control " name="property_owner" id="property_owner" style="width: 100%;">
                                <option value="">{{__('property_manage_module.placeholders.property_owner')}}</option>
                                @forelse($property_owners as $property_owner)
                                   <option value="{{$property_owner->id}}" {{($property_owner->id==$property->property_owner)?'selected':''}}  >{{$property_owner->name}} ({{$property_owner->email}})</option>
                                @empty
                                <option value="">No Property Owner Found</option>
                                @endforelse                                
                              </select>
                          </div>
                          @endif
                          @if ($current_user->role->user_type->slug == 'property-owner' && $current_user->created_by_admin)
                          <div class="form-group required">
                            <label for="property_manager">{{__('property_manage_module.labels.property_manager')}}</label>
                             <select class="form-control " name="property_manager" id="property_manager" style="width: 100%;">
                               <option value="">{{__('property_manage_module.placeholders.property_manager')}}</option>
                               @forelse($property_managers as $property_manager)
                                  <option value="{{$property_manager->id}}" {{($property_manager->id==$property->property_manager)?'selected':''}}  >{{$property_manager->name}} ({{$property_manager->email}})</option>
                               @empty
                               <option value="">No Property Manager Found</option>
                               @endforelse                                
                             </select>
                          </div>
                          @endif


                          <div class="form-group">
                            <label for="electricity_account_number">{{__('property_manage_module.labels.electricity_account_number')}}</label>
                            <input type="number" min="1" class="form-control" value="{{old('electricity_account_number')?old('electricity_account_number'):$property->electricity_account_number}}" name="electricity_account_number" id="electricity_account_number"  placeholder="{{__('property_manage_module.placeholders.electricity_account_number')}}">
                          </div>

                          <div class="form-group">
                            <label for="water_account_number">{{__('property_manage_module.labels.water_account_number')}}</label>
                            <input type="number" min="1" class="form-control" value="{{old('water_account_number')?old('water_account_number'):$property->water_account_number}}" name="water_account_number" id="water_account_number"  placeholder="{{__('property_manage_module.placeholders.water_account_number')}}">
                            @if($errors->has('water_account_number'))
                            <span class="text-danger">{{$errors->first('water_account_number')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label>{{__('property_manage_module.labels.attach_files')}}</label>
                            <div>
                              <button type="button" id="add_new_file" class="btn btn-outline-success"><i class="fa fa-plus"></i>&nbsp;{{__('general_sentence.button_and_links.add_file')}}</button>
                            </div>
                          </div>
                          <div id="files_container">
                            @if(count($files=$property->property_attachments))
                              @foreach($files as $file)
                                <div class="row mt-1 files_row">
                                  <div class="col-md-6">
                                    <input placeholder="{{__('property_manage_module.placeholders.image_title')}}" class="form-control file_title_list"  id="title_{{$file->id}}" value="{{$file->title}}" name="title[]" type="text">
                                  </div>
                                  <div class="col-md-5">
                                    <input data-is_required="no" placeholder="File" class="form-control file_list"  id="property_files_{{$file->id}}" name="property_files[]" type="file" aria-describedby="imageHelp{{$file->id}}">
                                    <small class="form-text text-muted">
                                      @if(App::getLocale()=='ar')
                                      تحميل ملفات PDF / DOC / JPEG / PNG / TEXT بحد أقصى. {{$max_filesize}}Mb
                                      @else
                                      Upload PDF/DOC/JPEG/PNG/TEXT files of max. {{$max_filesize}}Mb
                                      @endif
                                      
                                    </small>
                                    <small id="imageHelp{{$file->id}}" class="form-text text-muted"><b>{{__('property_manage_module.image_update_help_text')}}(<a href="{{route('admin.properties.download_attachment',$file->id)}}">{{__('property_manage_module.download_file')}}</a>)</b></small>
                                  </div>
                                  <div class="col-md-1">
                                    <button type="button" data-delete_url="{{route('admin.properties.delete_attachment_through_ajax',$file->id)}}" class="btn btn-danger files_row_del_btn"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  </div>
                                  <input type="hidden" name="file_id[]" value="{{$file->id}}">
                                </div>
                              @endforeach
                            @endif
                          </div>

                          <input type="hidden" id="property_manager_create_url" value="{{route('admin.users.create')}}">
                          <input type="hidden" id="property_owner_create_url" value="{{route('admin.users.create')}}">

                        </div>
                        <div>
                           <a href="{{route('admin.properties.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a>
                           <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.submit')}}</button> 
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
<script type="text/javascript" src="{{asset('js/admin/properties/edit.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_map.key')}}&sensor=false&libraries=places"></script>
<script type="text/javascript">
google.maps.event.addDomListener(window, 'load', function () {
        var places = new google.maps.places.Autocomplete(document.getElementById('address'));
        google.maps.event.addListener(places, 'place_changed', function () {

        });
    });

</script>
@endpush
