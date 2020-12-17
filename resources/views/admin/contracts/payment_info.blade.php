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
              <li class="breadcrumb-item active">{{($contract->creation_complete)?'Edit':'Create'}}
              </li>
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
                <h3 class="card-title">{{($contract->creation_complete)?'Edit':'Create'}} Contract</h3>
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
                    @include('admin.contracts.partials.multi_step_links')
                    <div class="col-md-11 col-sm-12">
                      <form id="payment_info_form" action="{{route('admin.contracts.store_payment_info',$contract->id)}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div>
                          <input type="hidden" value="{{$services_price_total}}" id="services_price_total">
                          <div class="form-group required">
                            <label for="contract_price">Contract Price<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('contract_price')?old('contract_price'):$contract->contract_price}}" name="contract_price" id="contract_price"  placeholder="Contract Price">
                            @if($errors->has('contract_price'))
                            <span class="text-danger">{{$errors->first('contract_price')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="contract_price">Osool Profit (in %)<span class="error">*</span></label>
                            <input type="number" min="0" max="100" class="form-control" value="{{old('profit_in_percentage')?old('profit_in_percentage'):$contract->profit_in_percentage}}" name="profit_in_percentage" id="profit_in_percentage"  placeholder="Osool Profit (in %)">
                            <span id="profit_in_amount_text">
                              @if($contract->profit_in_percentage && $contract->profit_in_amount)

                              ( Amount = {{number_format($contract->profit_in_amount, 2, '.', '')}} )
                              @endif
                            </span>
                            @if($errors->has('profit_in_percentage'))
                            <span class="text-danger">{{$errors->first('profit_in_percentage')}}</span>
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

                              <div class="col-sm-12">
                                <div class="form-group required">
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" checked class="form-check-input absolute_or_percentage" value="absolute" name="absolute_or_percentage"

                                        @if(!$contract->absolute_or_percentage)
                                        checked
                                        @elseif($contract->absolute_or_percentage && $contract->absolute_or_percentage=='absolute')
                                        checked
                                        @endif

                                        >Absolute Amount
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" value="percentage" class="form-check-input absolute_or_percentage" name="absolute_or_percentage"
                                        {{($contract->absolute_or_percentage && $contract->absolute_or_percentage=='percentage')?'checked':''}}
                                        >Percentage
                                      </label>
                                    </div>
                                </div>
                              </div>
                            </div>
           
                          @if(count($contract->contract_installments))
                            @foreach($contract->contract_installments as $key=> $installment)
                              <input type="hidden" name="installment_id[]" value="{{$installment->id}}">
                              <div class="row" id="row{{$key}}">
                                  <div class="col-sm-5">
                                        <div class="form-group required">

                                          <label class="absolute_or_percentage_label" for="amount_{{$key}}">{{($installment->percentage)?'Percentage':'Amount'}}<span class="error">*</span></label>

                                          <input type="number" min="1" name="amount[]" class="form-control amount_input_list" value="{{($installment->percentage)?$installment->percentage:$installment->price}}" data-id="{{$key}}" id="amount_{{$key}}"  placeholder="{{($installment->percentage)?'Percentage':'Amount'}}"

                                          >

                                          <span id="amount_text_{{$key}}" class="amount_text">{{($installment->percentage)?'Amount='.$installment->price:''}}</span>

                                          @if($errors->has('amount.'.$key))
                                              <span class="text-danger">{{$errors->first('amount.'.$key)}}</span>
                                          @endif
                                        </div>
                                  </div>
                                  <div class="col-sm-5">
                                        <div class="form-group required">
                                          <label for="contract_price">Due Date<span class="error">*</span></label>
                                          <input autocomplete="off" readonly="readonly" type="text" name="due_date[]" class="form-control due_date_input_list datepicker" value="{{Carbon\Carbon::createFromFormat('Y-m-d', $installment->due_date)->format('d/m/Y')}}" id="due_date_{{$key}}"  placeholder="Due Date">

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
                              <input type="hidden" name="installment_id[]" value="">
                              <div class="row" id="row_1">
                                  <div class="col-sm-5">
                                        <div class="form-group required">

                                          <label class="absolute_or_percentage_label" for="amount_1">Amount<span class="error">*</span></label>

                                          <input type="number" min="1" name="amount[]" class="form-control amount_input_list" value="" data-id="1" id="amount_1"  placeholder="Amount"
                                          >

                                          <span id="amount_text_1" class="amount_text"></span>

                                        </div>
                                  </div>
                                  <div class="col-sm-5">
                                        <div class="form-group required">
                                          <label for="contract_price">Due Date<span class="error">*</span></label>
                                          <input autocomplete="off" readonly="readonly" type="text" name="due_date[]" class="form-control due_date_input_list datepicker" value="" id="due_date_1"  placeholder="Due Date">

                                        </div>
                                  </div>
                                  <div class="col-sm-2">
                                        <div class="form-group ">
                                          <label for="">&nbsp;</label>
                                        
                                            <div class="installment_input_add" >
                                              <button type="button"  id="add_installment_button" class="btn btn-success btn-add-speaker">+</button>
                                            </div> 
                         
                                        </div>
                                  </div>
                                </div>                         
                          @endif

                          </div> 
                        </div>
                        <hr class="mt-3 mb-3">
                        <div>
                           <a href="{{route('admin.contracts.services',$contract->id)}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Previous</a>
                           <button type="submit" class="btn btn-success">Submit & Next&nbsp;<i class="fas fa-forward"></i></button> 
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
<script type="text/javascript" src="{{asset('js/admin/contracts/payment_info.js')}}"></script>


@endpush
