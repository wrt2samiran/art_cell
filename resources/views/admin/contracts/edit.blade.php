@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Contract Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.contracts.list')}}">Contracts</a></li>
              <li class="breadcrumb-item active">Edit</li>
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
                <h3 class="card-title">Edit Contract</h3>
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
                      <form  method="post" id="admin_contract_edit_form" action="{{route('admin.contracts.update',$contract->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="description">Contract Info <span class="error">*</span></label>
                            <textarea class="form-control" name="description" id="description"  placeholder="Description">{!!old('description')?old('description'):$contract->description!!}</textarea>
                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="services">Services required for the contract <span class="error">*</span></label>
                              <select class="form-control " multiple name="services[]" id="services" style="width: 100%;">
                                <option value="">Select services</option>
                                @forelse($services as $service)
                                   <option value="{{$service->id}}" {{(in_array($service->id,$contract->services_id_array()))?'selected':''}} >{{$service->service_name}} </option>
                                @empty
                                <option value="">No Service Found</option>
                                @endforelse                                
                              </select>
                          </div>
                          <div class="form-group required">
                             <label for="property_owner">Property Owner <span class="error">*</span></label>
                              <select class="form-control " name="property_owner" id="property_owner" style="width: 100%;">
                                <option value="">Select property owner</option>
                                @forelse($property_owners as $property_owner)
                                   <option value="{{$property_owner->id}}" {{($contract->customer_id==$property_owner->id)?'selected':''}} >{{$property_owner->name}} ({{$property_owner->email}})</option>
                                @empty
                                <option value="">No Property Owner Found</option>
                                @endforelse                                
                              </select>
                          </div>
                          <div class="form-group required">
                             <label for="property">Select Property <span class="error">*</span></label>
                              <select class="form-control " id="property" name="property" style="width: 100%;">
                                <option value="">Select property</option>
                                @forelse($properties as $property)
                                   <option value="{{$property->id}}" {{($contract->property_id==$property->id)?'selected':''}}>{{$property->property_name}}({{$property->code}})</option>
                                @empty
                                <option value="">No Property Found</option>
                                @endforelse
                              </select>
                          </div>

                          <div class="form-group required">
                             <label for="service_provider">Select service provider <span class="error">*</span></label>
                              <select class="form-control " id="service_provider" name="service_provider" style="width: 100%;">
                                <option value="">Select service provider</option>
                                @forelse($service_providers as $service_provider)
                                   <option value="{{$service_provider->id}}" {{($contract->service_provider_id==$service_provider->id)?'selected':''}}>{{$service_provider->name}} ({{$service_provider->email}})</option>
                                @empty
                                <option value="">No Service Provider Found</option>
                                @endforelse 
                              </select>
                          </div>

                          <div class="form-group required">
                            <label for="start_date">Start Date <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('start_date')?old('start_date'):Carbon\Carbon::createFromFormat('Y-m-d', $contract->start_date)->format('d/m/Y')}}" name="start_date" id="start_date"  placeholder="Start Date">
                            @if($errors->has('start_date'))
                            <span class="text-danger">{{$errors->first('start_date')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="end_date">End Date <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('end_date')?old('end_date'):Carbon\Carbon::createFromFormat('Y-m-d', $contract->end_date)->format('d/m/Y')}}" name="end_date" id="end_date"  placeholder="End Date">
                            @if($errors->has('end_date'))
                            <span class="text-danger">{{$errors->first('end_date')}}</span>
                            @endif
                          </div>
                         <div class="form-group required">
                            <label for="contract_price">Contract Price<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('contract_price')?old('contract_price'):$contract->contract_price}}" name="contract_price" id="contract_price"  placeholder="Contract Price">
                            @if($errors->has('contract_price'))
                            <span class="text-danger">{{$errors->first('contract_price')}}</span>
                            @endif
                          </div>
  
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="in_installment" name="in_installment" value="true" {{($contract->in_installment)?'checked':''}}>
                            <label for="in_installment" class="custom-control-label">Payment In Installment ?</label>
                          </div>

                          <div  id="installment_input_container" class="mt-2" style="display: {{($contract->in_installment)?'block':'none'}};">  
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group required">
                                    <label for="notify_installment_before_days">Notify user about due payment before how many days from due date ? <span class="error">*</span></label>
                                    <input type="text" class="form-control" value="{{old('notify_installment_before_days')?old('notify_installment_before_days'):$contract->notify_installment_before_days}}" name="notify_installment_before_days" id="notify_installment_before_days"  placeholder="Notify user about due payment before how many days from due date">
                                    @if($errors->has('notify_installment_before_days'))
                                    <span class="text-danger">{{$errors->first('notify_installment_before_days')}}</span>
                                    @endif
                                  </div>
                                </div>
                              </div>
           
                            @if(count($contract->contract_installments))
                            @foreach($contract->contract_installments as $key=> $installment)
                                      <input type="hidden" name="installment_id[]" value="{{$installment->id}}">
                                      <div class="row" id="row{{$key}}">
                                          
                                          <div class="col-sm-5">
                                                <div class="form-group required">
                                                  <label for="amount_{{$key}}">Amount<span class="error">*</span></label>

                                                  <input type="number" min="1" name="amount[]" class="form-control amount_input_list" value="{{$installment->price}}" id="amount_{{$key}}"  placeholder="Amount">

                                                  @if($errors->has('amount.'.$key))
                                                      <span class="text-danger">{{$errors->first('amount.'.$key)}}</span>
                                                  @endif
                                                </div>
                                          </div>
                                          <div class="col-sm-5">
                                                <div class="form-group required">
                                                  <label for="contract_price">Due Date<span class="error">*</span></label>
                                                  <input type="text" name="due_date[]" class="form-control due_date_input_list datepicker" value="{{Carbon\Carbon::createFromFormat('Y-m-d', $installment->due_date)->format('d/m/Y')}}" id="due_date_{{$key}}"  placeholder="Due Date">

                                                  @if($errors->has('due_date.'.$key))
                                                      <span class="text-danger">{{$errors->first('due_date.'.$key)}}</span>
                                                  @endif
                                                </div>
                                          </div>
                                          <div class="col-sm-2">
                                                <div class="form-group ">
                                                  <label for="">&nbsp;</label>
                                                   @if($key=='0')
                                                    <div class="installment_input_add" >
                                                      <button type="button"  id="add_installment_button" class="btn btn-success btn-add-speaker">+</button>
                                                    </div> 
                                                   @else
                                                    <div class="installment_input_add" >
                                                      <button type="button"  name="remove" id="{{$key}}" class="btn btn-danger btn_installment_remove">X</button>
                                                    </div> 
                                                   @endif
                                                </div>
                                          </div>
                                        </div>
                            @endforeach
                          @else
                                @php
                                 $number_of_installment=(session('number_of_installment')) ? session('number_of_installment'):1;
                                @endphp
                                
                                @for($i=1;$i<=$number_of_installment;$i++)
                                <input type="hidden" name="installment_id[]" value="">
                                <div class="row" id="row{{$i}}">

                                  <div class="col-sm-5">
                                        <div class="form-group required">
                                          <label for="amount_{{$i}}">Amount<span class="error">*</span></label>

                                          <input type="number" min="1" name="amount[]" class="form-control amount_input_list" value="{{old('amount.'.($i-1))}}" id="amount_{{$i}}"  placeholder="Amount">

                                          @if($errors->has('amount.'.($i-1)))
                                              <span class="text-danger">{{$errors->first('amount.'.($i-1))}}</span>
                                          @endif
                                        </div>
                                  </div>
                                  <div class="col-sm-5">
                                        <div class="form-group required">
                                          <label for="contract_price">Due Date<span class="error">*</span></label>

                                          <input type="text" name="due_date[]" class="form-control due_date_input_list datepicker" value="{{old('due_date.'.($i-1))}}" id="due_date_{{$i}}"  placeholder="Amount">

                                          @if($errors->has('due_date.'.($i-1)))
                                              <span class="text-danger">{{$errors->first('due_date.'.($i-1))}}</span>
                                          @endif
                                        </div>
                                  </div>
                                  <div class="col-sm-2">
                                        <div class="form-group ">
                                          <label for="">&nbsp;</label>
                                           @if($i=='1')
                                            <div class="installment_input_add" >
                                              <button type="button"  id="add_installment_button" class="btn btn-success btn-add-speaker">+</button>
                                            </div> 
                                           @else
                                            <div class="installment_input_add" >
                                              <button type="button"  name="remove" id="{{$i}}" class="btn btn-danger btn_installment_remove">X</button>
                                            </div> 
                                           @endif
                                        </div>
                                  </div>
                                </div>
                                @endfor
                          @endif

                          </div> 
      
                          <hr>
                          <div class="row attachment_files_container">
                            <div class="col-sm-12 mb-1">
                              <b>Find already attached files</b>
                            </div>
                            @if(count($files=$contract->contract_attachments))
                              @foreach($files as $file)
                                <div class="col-sm-1 col-xs-1 attachment_files" id="attachment_file_{{$file->id}}" style="height:55px">
                                  <div class="d-flex align-items-start" >
                                   <div>
                                    <a title="Click to download the file" href="{{route('admin.contracts.download_attachment',$file->id)}}">
                                      <i style="color: red;" class="fa-4x far fa-file-pdf"></i>
                                    </a>
                                   </div>
                                   <div class="ml-1">
                                     <a title="Click to delete the file" href="javascript:delete_attach_file('{{route('admin.contracts.delete_attachment_through_ajax',$file->id)}}','{{$file->id}}')"><i style="color: red;" class="fas fa-window-close"></i></a>
                                   </div>
                                  </div>
                                </div>
                              @endforeach
                            @else
                            <div class="col-md-12 text-muted">No files attached to this property</div>
                            @endif
                          </div>
                          <hr>
                          <div class="form-group">
                            <label for="contract_files">Attach Files</label>
                            <input  type="file" multiple class="form-control"
                            name="contract_files[]" id="contract_files" aria-describedby="propertyFilesHelp" >

                            <small id="propertyFilesHelp" class="form-text text-muted">Upload PDF files of max. 1mb</small>
                            @if($errors->get('contract_files.*'))
                            
                             @foreach($errors->get('contract_files.*') as $err)
                              <span class="text-danger">{{$err[0]}}</span><br>
                              @break
                             @endforeach
                           
                            @endif
                          </div>
                          @if(auth()->guard('admin')->user()->hasAllPermission(['contract-status-change']))
                          <div class="form-group required">
                             <label for="contract_status_id">Contract Status<span class="error">*</span></label>
                              <select class="form-control " id="contract_status_id" name="contract_status_id" style="width: 100%;">
                                <option value="">Select status</option>
                                @forelse($contract_statuses as $contract_status)
                                   <option value="{{$contract_status->id}}" {{($contract->contract_status_id==$contract_status->id)?'selected':''}}>{{$contract_status->status_name}} </option>
                                @empty
                                <option value="">No Status Found</option>
                                @endforelse 
                              </select>
                          </div>
                          @endif
                          <input type="hidden" id="property_create_url" value="{{route('admin.properties.create')}}">

                          <input type="hidden" id="service_provider_create_url" value="{{route('admin.service_providers.create')}}">

                          <input type="hidden" id="property_owner_create_url" value="{{route('admin.property_owners.create')}}">
                        </div>
                        <div>
                           <a href="{{route('admin.contracts.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/contracts/edit.js')}}"></script>
@endpush
