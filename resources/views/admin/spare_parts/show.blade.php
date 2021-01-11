@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('spare_part_manage_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.spare_parts.list')}}">{{__('general_sentence.breadcrumbs.spare_parts')}}</a></li>
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
                  {{__('spare_part_manage_module.spare_part_details')}}
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover" id="spare-parts-details-table">
                      <tbody>
                        <tr>
                          <td>{{__('spare_part_manage_module.labels.spare_part_name')}}</td>
                          <td>{{$spareParts->name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('spare_part_manage_module.labels.unit')}}</td>
                          <td >{{$spareParts->unitmaster->unit_name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('spare_part_manage_module.labels.manufacturer')}}</td>
                          <td >{{$spareParts->manufacturer}}</td>
                        </tr>
                        <tr>
                          <td>{{__('spare_part_manage_module.labels.description')}}</td>
                          <td>{!!$spareParts->description!!}</td>
                        </tr>

                        
                        <tr>
                          <td>{{__('spare_part_manage_module.labels.price')}}</td>
                          <td >{{$spareParts->currency}} {{$spareParts->price}}</td>
                        </tr>
                        <tr>
                          <td>{{__('spare_part_manage_module.labels.status')}}</td>
                          <td>
                            <button role="button" class="btn btn-{{($spareParts->is_active=='1')?'success':'danger'}}">{{($spareParts->is_active=='1')?__('general_sentence.active'):__('general_sentence.inactive')}}</button>
                          </td>
                        </tr>
                        <tr>
                          <td>{{__('spare_part_manage_module.uploaded_images_text')}}</td>
                          <td>
                              <div class="card card-body">
                                     <div class="row">
                                      @if(count($spareParts->images))
                                      @foreach($spareParts->images as $image)
                                          <div class="col-md-3" id="menu_image_{{$image->id}}">
                                            <div>
                                                <img width="100%" src="{{asset('uploads/spare_part_images/thumb/'.$image->image_name)}}">
                                            </div>
                                          </div>
                                      @endforeach
         
                                      @else
                                       <div class="col-md-12">
                                         <p>{{__('spare_part_manage_module.no_images_text')}}</p>
                                       </div>
                                      @endif
                                       
                                    </div>
                              </div>
                          </td>
                        </tr>
                       
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.spare_parts.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a></td>
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

