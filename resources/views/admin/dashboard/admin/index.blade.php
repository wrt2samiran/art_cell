@extends('admin.dashboard.dashboard-layout')

@section('dashboard-content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>
                  {{$total_customers}}
                </h3>
                <p><strong>NUMBER OF CUSTOMERS</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="{{route('admin.users.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>
                  {{$total_service_providers}}
                </h3>

                <p><strong> NUMBER OF SERVICE PROVIDERS</strong> </p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="{{route('admin.users.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>
                  {{$total_contracts}}
                </h3>
                <p><strong>NUMBER OF CONTRACTS </strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-handshake"></i>
              </div>
              <a href="{{route('admin.contracts.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
    
        </div>
        <div class="row">
            <div class="col-md-12">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Tasks
                </h3>
              </div>
              <div class="card-body">
                
              </div>
              <!-- /.card-body -->
  
            </div>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-md-6">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Tasks
                </h3>
              </div>
              <div class="card-body">
                
              </div>
              <!-- /.card-body -->
  
            </div>
            </div>
            <div class="col-md-6">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Tasks
                </h3>
              </div>
              <div class="card-body">
                
              </div>
              <!-- /.card-body -->
  
            </div>
            </div>
            <!-- /.col -->
        </div>
      </div><!-- /.container-fluid -->
    </section>

  </div>
  <!-- /.content-wrapper -->

@endsection