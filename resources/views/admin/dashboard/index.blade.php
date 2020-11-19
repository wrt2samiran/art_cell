
  <!-- /.navbar -->
@extends('admin.layouts.after-login-layout')


@section('unique-content')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{__('general_sentence.dashboard')}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">{{__('general_sentence.dashboard')}}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      @if(count($errors) > 0)
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}</span><br/>
                @endforeach
            </div>
        @endif

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
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>
                  5
                </h3>

                <p><strong>TOTAL APP USER</strong></p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>
                  20
                </h3>

                <p><strong> OUT COUNT</strong> </p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>
                  20
                </h3>

                <p><strong>IN COUNT </strong></p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
    
        </div>
        <!-- /.row -->
        <!-- Main row -->
        
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <section class="content">
      <div class="row">
          <div class="col-md-12">
              <div class="box">
                  <div class="box-header with-border">
                      <h3 class="box-title">Latest Task</h3>
                      <div class="pull-right">
                          <form name="tasks" id="tasks" method="GET" action="{{ route('admin.dashboard') }}">
                                <div class="col-md-4 card-body">
                                  <label for="title">City</label>
                                          <select name="city_id" id="city_id" class="form-control">
                                              <option value="">-Select-</option>
                                          @if(count($cityData)>0)
                                          @foreach ($cityData as $val)
                                              <option value="{{$val->city_id}}" @if(isset($_GET['city_id']) && ($_GET['city_id'] == $val->city_id)) {{'selected'}} @endif>{{$val->city->name}}</option>
                                          @endforeach
                                          @endif
                                      
                                          </select>
                                  </div>
                                  <div class="col-md-4 card-body">
                                    <label for="title">State</label>
                                            <select name="state_id" id="state_id" class="form-control">
                                                <option value="">-Select-</option>
                                                @if(count($stateData)>0)
                                                @foreach ($stateData as $val)
                                                    <option value="{{$val->state_id}}" @if(isset($_GET['state_id']) && ($_GET['state_id'] == $val->state_id)) {{'selected'}} @endif>{{$val->state->name}}</option>
                                                @endforeach
                                                @endif
                                        
                                            </select>
                                    </div>
                                    <div class="col-md-4 card-body">
                                      <label for="title">Property Name</label>
                                              <select name="property_id" id="property_id" class="form-control">
                                                  <option value="">-Select-</option>
                                                  @if(count($popertyData)>0)
                                                  @foreach ($popertyData as $val)
                                                      <option value="{{$val->property_id}}" @if(isset($_GET['property_id']) && ($_GET['property_id'] == $val->property_id)) {{'selected'}} @endif>{{$val->property->property_name}}</option>
                                                  @endforeach
                                                  @endif
                                          
                                              </select>
                                      </div>
                                  
                                  <div class="col-md-4">
                                      <input type="submit" name="tasks" value="Filter" class="btn btn-primary modal-action-btn" style="margin-top:20px;">   
                                      <a class="btn btn-primary modal-action-btn" href="{{ route('admin.dashboard') }}" style="margin-top:20px;">Reset</a>
                                      {{-- <a data-toggle="modal" data-target="#add-member-user" class="btn btn-primary modal-action-btn" data-modalaction="add" style="margin-top:20px;"><i class="fa fa-plus-circle"></i> Add</a> --}}
                                  </div>
                              </form>
                      </div>
                  </div>
                  <div class="row" style="margin:5px;">
                      <div class="col-sm-12">
                          @if(isset($tasks))
                              @if(count($tasks)>0)
                                  <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                      <thead>
                                      <tr role="row">
                                          
                                              <th>Task Title</th>
                                              <th>Property Name</th>
                                              <th>Service</th>
                                              <th>Country</th>
                                              <th>State</th>
                                              <th>City</th>
                                              <th>Service Start Date</th>
                                              <th>Service End Date</th>
                                           
                                          
                                      </tr>
                                      </thead>
                                      @foreach ($tasks as $key => $row)
                                      <tbody>
                                          <tr role="row" class="odd">
                                              <td>{{$row->task_title}}</td>
                                              <td>{{$row->property->property_name}}</td>
                                              <td>{{$row['service']['service_name']}}</td>
                                              <td>{{@$row->country->name}}</td>
                                              <td>{{@$row->state->name}}</td>
                                              <td>{{@$row->city->name}}</td>
                                              <td>{{$row->start_date}}</td>
                                              <td>{{$row->end_date}}</td>
                                          </tr>
                                      </tbody>
                                      @endforeach
                                  </table>
                                    
                              @endif
                                  
                          @endif            
                      </div>
                  </div>
                  {{-- <div class="row" style="margin:5px;">
                      <div class="col-sm-12 col-md-5">
                          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                              <strong>Show Records:</strong>
                              <strong style="color:green">{{ $allSale->firstItem() }}</strong>
                                  To
                              <strong style="color:green">{{ $allSale->lastItem() }}</strong>
                                  Total
                              <strong style="color:green">{{$allSale->total()}}</strong>
                                  Entries
                          </div>
                      </div>
                      <div class="col-sm-12 col-md-7">
                          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                              {{$allSale->appends(request()->input())->links("pagination::bootstrap-4") }}
                          </div>
                      </div>
                  </div> --}}
              </div>
              <!-- /.box -->
          </div>
          <!-- /.col -->
      </div>
  </section>
  </div>
  <!-- /.content-wrapper -->

@endsection
