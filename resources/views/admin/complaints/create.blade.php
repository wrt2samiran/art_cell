@extends('admin.layouts.after-login-layout')
@section('unique-content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('complaint_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.complaints.list')}}">{{__('general_sentence.breadcrumbs.complaints')}}</a></li>
              <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.create')}}</li>
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
                <h3 class="card-title">{{__('complaint_module.create_complaint')}}</h3>
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
                             <label for="contract_id">{{__('complaint_module.labels.contract')}}<span class="error">*</span></label>
                              <select class="form-control " id="contract_id" name="contract_id" style="width: 100%;">
                                <option value="">{{__('complaint_module.placeholders.contract')}}</option>
                                @forelse($contracts as $contract)
                                   <option data-work_orders="{{json_encode($contract->work_orders)}}" value="{{$contract->id}}" {{($work_order_contract && $work_order_contract->id==$contract->id)?'selected':''}} >{{$contract->title}}({{$contract->code}})</option>
                                @empty
                                <option value="">No Contract Found</option>
                                @endforelse
                              </select>
                            @if($errors->has('contract_id'))
                            <span class="text-danger">{{$errors->first('contract_id')}}</span>
                            @endif
                          </div>
                          <div class="form-group required" id="work_order_select_container" style="display: none;">
                             <label for="work_order_id">{{__('complaint_module.labels.work_order')}}</label>
                              <select class="form-control " id="work_order_id" name="work_order_id" style="width: 100%;">
                                <option value="">{{__('complaint_module.placeholders.work_order')}}</option>
                              </select>
                            @if($errors->has('work_order_id'))
                            <span class="text-danger">{{$errors->first('work_order_id')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="subject">{{__('complaint_module.labels.subject')}} <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('subject')?old('subject'):''}}" name="subject" id="subject"  placeholder="{{__('complaint_module.placeholders.subject')}}">
                            @if($errors->has('subject'))
                            <span class="text-danger">{{$errors->first('subject')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="details">{{__('complaint_module.labels.complaint')}} <span class="error">*</span></label>
                            <textarea class="form-control" name="details" id="details"  placeholder="{{__('complaint_module.placeholders.complaint')}}">{!!old('details')?old('details'):''!!}</textarea>
                            @if($errors->has('details'))
                            <span class="text-danger">{{$errors->first('details')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="file">{{__('complaint_module.labels.attach_file')}}</label>
                            <input type="file" class="form-control" id="file" name="file">
                            @if($errors->has('file'))
                            <span class="text-danger">{{$errors->first('file')}}</span>
                            @endif
                          </div>
                        </div>
                        <div>
                           <a href="{{route('admin.complaints.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a>
                           <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.submit')}}</button> 
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

<script type="text/javascript">

$(function() {
      var contract_id=$('#contract_id').find(":selected").val();
  if(contract_id){
     var work_orders=$('#contract_id').find(":selected").data("work_orders");
     var work_order_id="{{($work_order)?$work_order->id:''}}";
      if(work_orders.length>0){
        var options=`<option value="">Select Order Order</option>`;
        for (var i = 0; i <work_orders.length; i++) {
          var is_selected=(work_order_id==work_orders[i].id)?'selected':'';
          options+=`<option  value="`+work_orders[i].id+`" `+is_selected+`>`+work_orders[i].task_title+`(WO ID-`+work_orders[i].id+`)`+`</option>`;
        }
        $('#work_order_id').html(options);
      }else{
        var options=`<option value="">Select Order Order</option>`;
        $('#work_order_id').html(options);
      }

      $('#work_order_select_container').show();
      $('#work_order_id').select2({
          theme: 'bootstrap4',
          placeholder:translations.complaint_module.placeholders.work_order,
          "language": {
              locale: current_locale,
             "noResults": function(){
                 if(current_locale=='ar'){
                  return "لم يتم العثور على أمر عمل";
                 }else{
                   return "No Work Order Found";
                 }
                
             }
          },
          escapeMarkup: function(markup) {
            return markup;
          },
      });
  }
});


  
  


</script>


<script type="text/javascript" src="{{asset('js/admin/complaints/create.js')}}"></script>
@endpush
