@extends('admin.layouts.after-login-layout')
@section('unique-content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Complaint Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.complaints.list')}}">Complaints</a></li>
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
                <h3 class="card-title">Create Complaint</h3>
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
                      <form  method="post" id="complaint_create_form" action="{{route('admin.complaints.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                          <div class="form-group required">
                             <label for="contract_id">Contract<span class="error">*</span></label>
                              <select class="form-control " id="contract_id" name="contract_id" style="width: 100%;">
                                <option value="">Select contract</option>
                                @forelse($contracts as $contract)
                                   <option data-work_orders="{{json_encode($contract->work_orders)}}" value="{{$contract->id}}" >{{$contract->title}}({{$contract->code}})</option>
                                @empty
                                <option value="">No Contract Found</option>
                                @endforelse
                              </select>
                            @if($errors->has('contract_id'))
                            <span class="text-danger">{{$errors->first('contract_id')}}</span>
                            @endif
                          </div>
                          <div class="form-group required" id="work_order_select_container" style="display: none;">
                             <label for="work_order_id">Work Order</label>
                              <select class="form-control " id="work_order_id" name="work_order_id" style="width: 100%;">
                                <option value="">Select Order Order</option>
                              </select>
                            @if($errors->has('work_order_id'))
                            <span class="text-danger">{{$errors->first('work_order_id')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="subject">Subject <span class="error">*</span></label>

                            <input type="text" class="form-control" value="{{old('subject')?old('subject'):''}}" name="subject" id="subject"  placeholder="Subject">
                            @if($errors->has('subject'))
                            <span class="text-danger">{{$errors->first('subject')}}</span>
                            @endif
                          </div>



                          <div class="form-group required">
                            <label for="details">Details <span class="error">*</span></label>
                            <textarea class="form-control" name="details" id="details"  placeholder="Details">{!!old('details')?old('details'):''!!}</textarea>
                            @if($errors->has('details'))
                            <span class="text-danger">{{$errors->first('details')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="file">Attach File</label>
                            <input type="file" class="form-control" id="file" name="file">
                            @if($errors->has('file'))
                            <span class="text-danger">{{$errors->first('file')}}</span>
                            @endif
                          </div>
                        </div>
                        <div>
                           <a href="{{route('admin.complaints.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/complaints/create.js')}}"></script>
@endpush
