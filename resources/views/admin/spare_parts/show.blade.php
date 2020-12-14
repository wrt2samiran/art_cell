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
                  Spare Parts Deatils
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover" id="spare-parts-details-table">
                      <tbody>
                        <tr>
                          <td>Spare Parts Name</td>
                          <td>{{$spareParts->name}}</td>
                        </tr>
                        <tr>
                          <td>Unit</td>
                          <td >{{$spareParts->unitmaster->unit_name}}</td>
                        </tr>
                        <tr>
                          <td>Manufacturer</td>
                          <td >{{$spareParts->manufacturer}}</td>
                        </tr>
                        <tr>
                          <td>Description</td>
                          <td>{!!$spareParts->description!!}</td>
                        </tr>

                        
                        <tr>
                          <td>Price</td>
                          <td >{{$spareParts->currency}} {{$spareParts->price}}</td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($spareParts->is_active=='1')?'success':'danger'}}">{{($spareParts->is_active=='1')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>
                        <tr>
                          <td>Images</td>
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
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.spare_parts.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

