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
                  Shared Service Deatils
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover" id="country-details-table">
                      <tbody>
                        <tr>
                          <td>Shared Service Name</td>
                          <td>{{$sharedServices->name}}</td>
                        </tr>
                        <tr>
                          <td>Description</td>
                          <td>{!! $sharedServices->description !!}</td>
                        </tr>

                        <tr>
                          <td>Sharing Price</td>
                          <td >
                            @if($sharedServices->is_sharing)
                            <div><span>{{$sharedServices->currency}} {{number_format($sharedServices->price, 2, '.', '')}}</span> for {{$sharedServices->number_of_days}} days</div>
                            <div>+{{$sharedServices->currency}}{{number_format($sharedServices->extra_price_per_day, 2, '.', '')}}/day</div>
                            @else 
                            <span class="text-muted">Not Available</span>
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td>Selling Price</td>
                          <td>
                            @if($sharedServices->is_selling)
                            <div><span>{{$sharedServices->currency}}{{number_format($sharedServices->selling_price, 2, '.', '')}}</span></div>
                            @else 
                            <span class="text-muted">Not Available</span>
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($sharedServices->is_active=='1')?'success':'danger'}}">{{($sharedServices->is_active=='1')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>
                        <tr>
                          <td>Images</td>
                          <td>
                              <div class="card card-body">
                                     <div class="row">
                                      @if(count($sharedServices->images))
                                      @foreach($sharedServices->images as $image)
                                          <div class="col-md-3" id="menu_image_{{$image->id}}">
                                            <div>
                                                <img width="100%" src="{{asset('uploads/shared_service_images/thumb/'.$image->image_name)}}">
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
                          </td>
                        </tr>
                       
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.shared_services.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

