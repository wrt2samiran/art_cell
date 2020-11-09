@extends('admin.layouts.after-login-layout')


@section('unique-content')

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
              <li class="breadcrumb-item active">Details</li>
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
                  Property Details
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="property-details-table">
                      <tbody>
                        <tr>
                          <td>Property Code</td>
                          <td >{{$property->code}}</td>
                        </tr>
                        <tr>
                          <td>Property Name</td>
                          <td >{{$property->property_name}}</td>
                        </tr>
                        <tr>
                          <td>Number Of Active Units</td>
                          <td >{{$property->no_of_units}}</td>
                        </tr>
                        <tr>
                          <td>Number Of Inactive Units</td>
                          <td >{{$property->no_of_inactive_units}}</td>
                        </tr>
                        <tr>
                          <td>Property Type</td>
                          <td >{{($property->property_type)?$property->property_type->type_name:'N/A'}}</td>
                        </tr>
                        <tr>
                          <td>Description</td>
                          <td >{{$property->description}}</td>
                        </tr>
                        </tr>
                        <tr>
                          <td>Addeess</td>
                          <td >{{$property->address}}</td>
                        </tr>
                        <tr>
                          <td>City</td>
                          <td >{{$property->city->name}}</td>
                        </tr>
                        <tr>
                          <td>Location</td>
                          <td >{{$property->location}}</td>
                        </tr>
                        <tr>
                          <td >Contact Number</td>
                          <td >{{$property->contact_number}}</td>
                        </tr>
                        <tr>
                          <td >Contact Email</td>
                          <td >{{$property->contact_email}}</td>
                        </tr>
                        <tr>
                          <td>Property Owner</td>
                          <td>
                            @if($property_owner=$property->owner_details()->withTrashed()->first())
                              @if($property_owner->deleted_at)
                                <span class="text-danger"><del>{{$property_owner->name}} </del>(user deleted)</span>
                              @else
                                <a target="_blank" href="{{route('admin.users.show',$property_owner->id)}}">{{$property_owner->name}}</a>
                              @endif
                            @else
                            N/A
                            @endif
                          </td>
                        </tr>

                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($property->is_active)?'success':'danger'}}">{{($property->is_active)?'Active':'Inactive'}}</button>
                          </td>
                        </tr>

                        <tr>
                          <td >Electricity Account Number</td>
                          <td >
                            @if($property->electricity_account_day)
                              {{App\Http\Helpers\Helper::Ordinal($property->electricity_account_day)}} day of every month
                            @else
                            N/A
                            @endif
                            
                          </td>
                        </tr>

                        <tr>
                          <td >Water Account Number</td>
                          <td >
             

                            @if($property->water_account_day)
                              {{App\Http\Helpers\Helper::Ordinal($property->water_account_day)}} day of every month
                            @else
                            N/A
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td>Downloadable Files</td>
                          <td>
                            <div class="row">
                              @if(count($files=$property->property_attachments))
                                @foreach($files as $file)
                                  @php
                                    if($file->file_type=='pdf'){
                                      $font_icon='far fa-file-pdf';
                                      $color='red';
                                    }elseif($file->file_type=='doc'){
                                      $font_icon='far fa-file-word';
                                      $color='blue';
                                    }elseif($file->file_type=='text'){
                                      $font_icon='far fa-file-alt';
                                      $color='white';
                                    }elseif($file->file_type=='image'){
                                      $font_icon='far fa-file-image';
                                      $color='grey';
                                    }else{
                                      $font_icon='fas fa-file';
                                      $color='grey';
                                    }
                                  @endphp
                            
                                  <div style="height: 55px" class="col-sm-1 col-xs-1">
                                    <a title="Click to download" href="{{route('admin.properties.download_attachment',$file->id)}}"><i style="color: {{$color}};" class="fa-4x {{$font_icon}}"></i></a>
                                  </div>
                                @endforeach
                              @else
                              <div class="col-md-12">No files</div>
                              @endif
                            </div>
                          </td>
                        </tr>
                        

                        <tr>
                          <td>Created At</td>
                          <td>{{$property->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.properties.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
                        </tr>
                      </tfoot>
                  </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
</div>
@endsection 

