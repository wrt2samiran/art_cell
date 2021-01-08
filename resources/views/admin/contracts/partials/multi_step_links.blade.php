@php
    $current_route_name=request()->route()->getName();
@endphp
<div class="col-md-11 col-sm-12 mb-3">
  <div class="row multistep-row">
    <div class="col-md-3 bg-success multistep-div {{(in_array($current_route_name,['admin.contracts.create','admin.contracts.edit']))?'active':''}}">
        @if($current_route_name=='admin.contracts.create')
        <a href="{{route('admin.contracts.create')}}">{{__('contract_manage_module.contract_info')}}</a>
        @else
        <a href="{{route('admin.contracts.edit',$contract->id)}}">{{__('contract_manage_module.contract_info')}}</a>
        @endif
    </div>

    <div class="col-md-3 bg-success multistep-div {{($current_route_name=='admin.contracts.services')?'active':''}}">
        @if($current_route_name=='admin.contracts.create')
        <a href="javascript:void(0)">{{__('contract_manage_module.services')}}</a>
        @else
            @if($contract->creation_complete || $current_route_name=='admin.contracts.edit')
            <a href="{{route('admin.contracts.services',$contract->id)}}">{{__('contract_manage_module.services')}}</a>
            @elseif(in_array($current_route_name,['admin.contracts.services','admin.contracts.payment_info']))
            <a href="{{route('admin.contracts.services',$contract->id)}}">{{__('contract_manage_module.services')}}</a>
            @else
            <a href="javascript:void(0)">{{__('contract_manage_module.services')}}</a>
            @endif
        @endif
    </div>
    <div class="col-md-3 bg-success multistep-div {{($current_route_name=='admin.contracts.payment_info')?'active':''}}">
        @if($current_route_name=='admin.contracts.create')
         <a href="javascript:void(0)">{{__('contract_manage_module.payment_info')}}</a>
        @else
            @if($contract->creation_complete)
            <a href="{{route('admin.contracts.payment_info',$contract->id)}}">{{__('contract_manage_module.payment_info')}}</a>
            @elseif(in_array($current_route_name,['admin.contracts.payment_infor']))
            <a href="{{route('admin.contracts.payment_info',$contract->id)}}">{{__('contract_manage_module.payment_info')}}</a>
            @else
            <a href="javascript:void(0)">{{__('contract_manage_module.payment_info')}}</a>
            @endif
        @endif
    </div>
    <div class="col-md-3 bg-success multistep-div {{($current_route_name=='admin.contracts.files')?'active':''}}">
        @if($current_route_name=='admin.contracts.create')
         <a href="javascript:void(0)">{{__('contract_manage_module.files')}}</a>
        @else
            @if($contract->creation_complete)
            <a href="{{route('admin.contracts.files',$contract->id)}}">{{__('contract_manage_module.files')}}</a>
            @elseif(in_array($current_route_name,['admin.contracts.files']))
            <a href="{{route('admin.contracts.files',$contract->id)}}">{{__('contract_manage_module.files')}}</a>
            @else
            <a href="javascript:void(0)">{{__('contract_manage_module.files')}}</a>
            @endif
        @endif
    </div>
  </div>
</div>