@extends('admin.layouts.after-login-layout')


@section('unique-content')
@php $current_user=auth()->guard('admin')->user(); @endphp
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Property Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.properties.list')}}">Properties</a></li>
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
                <h3 class="card-title">Create Property</h3>
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
                      <form  method="post" id="admin_property_create_form" action="{{route('admin.properties.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                          <div class="form-group required">
                            <label for="property_name">Property Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('property_name')?old('property_name'):''}}" name="property_name" id="property_name"  placeholder="Property Name">
                            @if($errors->has('property_name'))
                            <span class="text-danger">{{$errors->first('property_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="property_type_id">Property Types<span class="error">*</span></label>
                              <select class="form-control " id="property_type_id" name="property_type_id" style="width: 100%;">
                                <option value="">Select property type</option>
                                @forelse($property_types as $property_type)
                                   <option value="{{$property_type->id}}" >{{$property_type->type_name}}</option>
                                @empty
                                <option value="">No Property Type Found</option>
                                @endforelse
                              </select>
                          </div>
                          <div class="form-group required">
                            <label for="description">Description <span class="error">*</span></label>
                            <textarea class="form-control" name="description" id="description"  placeholder="Description">{!!old('description')?old('description'):''!!}</textarea>
                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
                            @endif
                          </div>
                          <div class="row">
                            <div class="col-md-6 form-group">
                              <label for="no_of_active_units">Number Of Active Units<span class="error">*</span></label>
                              <input type="number" min="1" class="form-control" value="{{old('no_of_active_units')?old('no_of_active_units'):''}}" name="no_of_active_units" id="no_of_active_units"  placeholder="Number Of Active Units">
                              @if($errors->has('no_of_active_units'))
                              <span class="text-danger">{{$errors->first('no_of_active_units')}}</span>
                              @endif
                            </div>
                            <div class="col-md-6 form-group">
                              <label for="no_of_inactive_units">Number Of Inactive Units<span class="error">*</span></label>
                              <input type="number" min="0" class="form-control" value="{{old('no_of_inactive_units')?old('no_of_inactive_units'):''}}" name="no_of_inactive_units" id="no_of_inactive_units"  placeholder="Number Of Inactive Units">
                              @if($errors->has('no_of_inactive_units'))
                              <span class="text-danger">{{$errors->first('no_of_inactive_units')}}</span>
                              @endif
                            </div>
                          </div>
                          <div class="form-group required">
                             <label for="city_id">City <span class="error">*</span></label>
                              <select class="form-control " id="city_id" name="city_id" style="width: 100%;">
                                <option value="">Select city</option>
                                @forelse($cities as $city)
                                   <option value="{{$city->id}}" >{{$city->name}}</option>
                                @empty
                                <option value="">No City Found</option>
                                @endforelse
                              </select>
                          </div>
                          <div class="form-group required">
                            <label for="address">Address <span class="error">*</span></label>
                            <textarea class="form-control" name="address" id="address"  placeholder="address">{!!old('address')?old('address'):''!!}</textarea>
                            @if($errors->has('address'))
                            <span class="text-danger">{{$errors->first('address')}}</span>
                            @endif
                          </div>                           
                          <div class="form-group required">
                            <label for="location">Location <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('location')?old('location'):''}}" name="location" id="location"  placeholder="Location">
                            @if($errors->has('location'))
                            <span class="text-danger">{{$errors->first('location')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" class="form-control" value="{{old('contact_number')?old('contact_number'):''}}" name="contact_number" id="contact_number"  placeholder="Contact Number">
                            @if($errors->has('contact_number'))
                            <span class="text-danger">{{$errors->first('contact_number')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="contact_email">Contact Email</label>
                            <input type="text" class="form-control" value="{{old('contact_email')?old('contact_email'):''}}" name="contact_email" id="contact_email"  placeholder="Contact Email">
                            @if($errors->has('contact_email'))
                            <span class="text-danger">{{$errors->first('contact_email')}}</span>
                            @endif
                          </div>
                          @if ($current_user->role->user_type->slug != 'property-owner')
                          <div class="form-group required">
                             <label for="property_owner">Property Owner<span class="error">*</span></label>
                              <select class="form-control " name="property_owner" id="property_owner" style="width: 100%;">
                                <option value="">Select property owner</option>
                                @forelse($property_owners as $property_owner)
                                   <option value="{{$property_owner->id}}" >{{$property_owner->name}} ({{$property_owner->email}})</option>
                                @empty
                                <option value="">No Property Owner Found</option>
                                @endforelse                                
                              </select>
                          </div>
                          @endif
                          @if ($current_user->role->user_type->slug == 'property-owner')
                          <div class="form-group required">
                            <label for="property_manager">Property Manager</label>
                             <select class="form-control " name="property_manager" id="property_manager" style="width: 100%;">
                               <option value="">Select property manager</option>
                               @forelse($property_managers as $property_manager)
                                  <option value="{{$property_manager->id}}" >{{$property_manager->name}} ({{$property_manager->email}})</option>
                               @empty
                               <option value="">No Property Manager Found</option>
                               @endforelse                                
                             </select>
                         </div>
                         @endif
                          <div class="form-group">
                            <label for="electricity_account_number">Electricity Account Number</label>
                            <input type="number" min="1" class="form-control" value="{{old('electricity_account_number')?old('electricity_account_number'):''}}" name="electricity_account_number" id="electricity_account_number"  placeholder="Electricity Account Number">
                            @if($errors->has('electricity_account_number'))
                            <span class="text-danger">{{$errors->first('electricity_account_number')}}</span>
                            @endif
                          </div>

                          <div class="form-group">
                            <label for="water_account_number">Water Account Number</label>
                            <input type="number" min="1" class="form-control" value="{{old('water_account_number')?old('water_account_number'):''}}" name="water_account_number" id="water_account_number"  placeholder="Water Account Number">
                            @if($errors->has('water_account_number'))
                            <span class="text-danger">{{$errors->first('water_account_number')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label>Attach Files</label>
                            <div>
                              <button type="button" id="add_new_file" class="btn btn-outline-success"><i class="fa fa-plus"></i>&nbsp;Add File</button>
                            </div>
                          </div>
                          <div id="files_container">

                          </div>

                          <input type="hidden" id="property_manager_create_url" value="{{route('admin.users.create')}}">
                          <input type="hidden" id="property_owner_create_url" value="{{route('admin.users.create')}}">

                        </div>
                        <div>
                           <a href="{{route('admin.properties.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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

<script type="text/javascript" src="{{asset('js/admin/properties/create.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_map.key')}}&sensor=false&libraries=places"></script>
<script type="text/javascript">
google.maps.event.addDomListener(window, 'load', function () {
        var places = new google.maps.places.Autocomplete(document.getElementById('address'));
        google.maps.event.addListener(places, 'place_changed', function () {

        });
    });

  </script>
  
@endpush
