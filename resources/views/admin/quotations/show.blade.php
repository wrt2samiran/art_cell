@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Quotation Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.quotations.list')}}">Quotations</a></li>
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
                  Quotation Details
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="quotation-details-table">
                      <tbody>
                        <tr>
                          <td>User Name</td>
                          <td>{{$quotation->user_full_name()}}</td>
                        </tr>
                        <tr>
                          <td>Email</td>
                          <td>{{$quotation->email}}</td>
                        </tr>
                        <tr>
                          <td>Contact Number</td>
                          <td>{{$quotation->contact_number}}</td>
                        </tr>
                        
                        <tr>
                          <td>City</td>
                          <td>{{$quotation->city->name}}</td>
                        </tr>
                        <tr>
                          <td>Land Mark</td>
                          <td>{{$quotation->landmark}}</td>
                        </tr>
                        <tr>
                          <td>Contract Duration</td>
                          <td>{{$quotation->contract_duration}}</td>
                        </tr>
                        <tr>
                          <td>Details</td>
                          <td>{{$quotation->details}}</td>
                        </tr>
                        <tr>
                          <td>Property Types</td>
                          <td>{{$quotation->property_types->map(function($property_type) {
                                  return $property_type->type_name;
                              })->implode(',<br>')}}
                          </td>
                        </tr>
                        <tr>
                          <td>Required Services</td>
                          <td>

                            @if(count($quotation->services))
                              <table class="table">
                                <thead>
                                  <tr>
                                    <th>Service</th>
                                    <th>Work Details (in short)</th>
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
                          
                        </tr>
                        <tr>
                          <td>Created At</td>
                          <td>{{$quotation->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.quotations.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

