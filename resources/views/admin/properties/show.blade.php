@extends('admin.layouts.after-login-layout')


@section('unique-content')

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
          <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card card-success">
                <div class="card-header">
                  {{__('property_manage_module.property_details')}}
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="property-details-table">
                      <tbody>
                        <tr>
                          <td>{{__('property_manage_module.labels.property_code')}}</td>
                          <td >{{$property->code}}</td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.property_name')}}</td>
                          <td >{{$property->property_name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.property_owner')}}</td>
                          <td>
                            @if($property_owner=$property->owner_details()->withTrashed()->first())
                              @if($property_owner->deleted_at)
                                <span class="text-danger"><del>{{$property_owner->name}} </del>(user deleted)</span>
                              @else
                                <span>{{$property_owner->name}} ({{$property_owner->email}})</span>
                              @endif
                            @else
                            N/A
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.property_manager')}}</td>
                          <td>
                            @if($property->manager_details)
                            <span>{{$property->manager_details->name}} ({{$property->manager_details->email}})</span>
                            @else
                            N/A
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.no_of_active_units')}}</td>
                          <td >{{$property->no_of_active_units}}</td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.no_of_inactive_units')}}</td>
                          <td >{{$property->no_of_inactive_units}}</td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.property_type')}}</td>
                          <td >{{($property->property_type)?$property->property_type->type_name:'N/A'}}</td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.description')}}</td>
                          <td >{{$property->description}}</td>
                        </tr>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.address')}}</td>
                          <td >{{$property->address}}</td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.city')}}</td>
                          <td >{{$property->city->name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.labels.location')}}</td>
                          <td >{{$property->location}}</td>
                        </tr>
                        <tr>
                          <td >{{__('property_manage_module.labels.contact_number')}}</td>
                          <td >{{$property->contact_number?$property->contact_number:'N/A'}}</td>
                        </tr>
                        <tr>
                          <td >{{__('property_manage_module.labels.contact_email')}}</td>
                          <td >{{$property->contact_email?$property->contact_email:'N/A'}}</td>
                        </tr>

                        <tr>
                          <td>{{__('property_manage_module.labels.status')}}</td>
                          <td>
                            <button role="button" class="btn btn-{{($property->is_active)?'success':'danger'}}">{{($property->is_active)?__('general_sentence.active'):__('general_sentence.inactive')}}</button>
                          </td>
                        </tr>

                        <tr>
                          <td >{{__('property_manage_module.labels.electricity_account_number')}}</td>
                          <td >
                            {{$property->electricity_account_number}}
                          </td>
                        </tr>

                        <tr>
                          <td >{{__('property_manage_module.labels.water_account_number')}}</td>
                          <td >
                          {{$property->water_account_number}}
                          </td>
                        </tr>
                        <tr>
                          <td>{{__('property_manage_module.downloadable_files')}}</td>
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
                              <div class="col-md-12">{{__('property_manage_module.no_files')}}</div>
                              @endif
                            </div>
                          </td>
                        </tr>
                        

                        <tr>
                          <td>{{__('property_manage_module.labels.created_at')}}</td>
                          <td>{{$property->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.properties.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a></td>
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

