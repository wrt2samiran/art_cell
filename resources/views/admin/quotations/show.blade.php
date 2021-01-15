@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('quotation_manage_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.quotations.list')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.details')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card card-success">
                <div class="card-header">
                  {{__('quotation_manage_module.quotation_details')}}
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
                 <table class="table table-bordered table-hover record-details-table" id="quotation-details-table">
                      <tbody>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.name')}}</td>
                          <td>{{$quotation->user_full_name()}}</td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.email')}}</td>
                          <td>{{$quotation->email}}</td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.contact_number')}}</td>
                          <td>{{$quotation->contact_number}}</td>
                        </tr>
                        
                        <tr>
                          <td>{{__('quotation_manage_module.labels.city')}}</td>
                          <td>{{$quotation->city->name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.landmark_or_location')}}</td>
                          <td>
                            <div>
                              {{$quotation->landmark}}
                            </div>
                            
                            <div>
                              <div style="height: 400px" id="map"></div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.contact_duration')}}</td>
                          <td>{{$quotation->contract_duration}}</td>
                        </tr>


                        <tr>
                          <td>{{__('quotation_manage_module.labels.property_type')}}</td>
                          <td>{{$quotation->property_types->map(function($property_type) {
                                  return $property_type->type_name;
                              })->implode(',<br>')}}
                          </td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.service_required')}}</td>
                          <td>

                            @if(count($quotation->services))
                              <table class="table">
                                <thead>
                                  <tr>
                                    <th>{{__('quotation_manage_module.labels.service')}}</th>
                                    <th>{{__('quotation_manage_module.labels.work_details')}}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($quotation->services as $service)
                                  <tr>
                                    <td>{{$service->service->service_name}}</td>
                                    <td>{{$service->work_details}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            @else
                            <span>No services</span>
                            @endif

                          </td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.description')}}</td>
                          <td>{{$quotation->details}}</td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.details_resources')}}</td>
                          <td>{{$quotation->no_of_resources}}</td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.details_image')}}</td>
                          <td>
                            @if(count($quotation->images_array()))
                              <div class="row">
                                @foreach($quotation->images_array() as $image)
                                <div class="col-sm-4">
                                  <a target="_blank" href="{{asset('uploads/quotation_files/'.$image)}}"><img  width="100%" src="{{asset('uploads/quotation_files/'.$image)}}"></a>
                                </div>
                                @endforeach
                              </div>
                            @else
                            No Images
                            @endif
                            
                          </td>
                        </tr>
                        <tr>
                          <td>{{__('quotation_manage_module.labels.created_at')}}</td>
                          <td>{{$quotation->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>

                  </table>
                  <div class="row mt-3">
                    <div class="col-md-12">
                        <h5>{{__('quotation_manage_module.labels.update_status')}}</h5>
                        <form class="form-inline" action="{{route('admin.quotations.update_status',$quotation->id)}}" method="post">
                            @csrf
                            @method("PUT")
                          <label for="status" class="mr-sm-2">Status:</label>
                            <select class="form-control mb-2 mr-sm-2" name="status" id="status">
                              @forelse($statuses as $status)
                              <option {{($status->id==$quotation->status_id)?'selected':''}} value="{{$status->id}}">{{$status->status_name}}</option>
                              @empty
                              <option value="">No status found</option>
                              @endforelse
                            </select>
                          <button type="submit" class="btn btn-success mb-2">{{__('general_sentence.button_and_links.update')}}</button>
                        </form>
                    </div>
                  </div>
              </div>
              <div class="card-footer">
                <a class="btn btn-primary" href="{{route('admin.quotations.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a>
              </div>

            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
</div>
@endsection 

@push('custom-scripts')

<script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_map.key')}}&sensor=false&libraries=places"></script>





<script>

  google.maps.event.addDomListener(window, "load", initMap);
   // Initialize and add the map

  function initMap() {
    var lat=parseFloat('{{$quotation->latitude}}');
    var lng=parseFloat('{{$quotation->longitude}}');
    // The location of quotation landmark
    const latLng = { lat: lat, lng: lng };
    // The map, centered at quotation landmark
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 10,
      center: latLng,
    });
    // The marker, positioned at quotation landmark
    const marker = new google.maps.Marker({
      position: latLng,
      map: map,
    });
  }
</script>
@endpush

