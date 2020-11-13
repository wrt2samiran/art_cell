@php
    $current_route_name=request()->route()->getName();
@endphp
<div class="col-md-11 col-sm-12 mb-3">
  <div class="row multistep-row">
    <div class="col-md-3 bg-success multistep-div {{(in_array($current_route_name,['admin.contracts.create','admin.contracts.edit']))?'active':''}}">
        @if($current_route_name=='admin.contracts.create')
        <a href="{{route('admin.contracts.create')}}">Contract Info</a>
        @else
        <a href="{{route('admin.contracts.edit',$contract->id)}}">Contract Info</a>
        @endif
    </div>

    <div class="col-md-3 bg-success multistep-div {{($current_route_name=='admin.contracts.services')?'active':''}}">
        @if($current_route_name=='admin.contracts.create')
        <a href="javascript:void(0)">Services</a>
        @else
            @if($contract->creation_complete || $current_route_name=='admin.contracts.edit')
            <a href="{{route('admin.contracts.services',$contract->id)}}">Services</a>
            @elseif(in_array($current_route_name,['admin.contracts.services','admin.contracts.payment_info']))
            <a href="{{route('admin.contracts.services',$contract->id)}}">Services</a>
            @else
            <a href="javascript:void(0)">Services</a>
            @endif
        @endif
    </div>
    <div class="col-md-3 bg-success multistep-div {{($current_route_name=='admin.contracts.payment_info')?'active':''}}">
        @if($current_route_name=='admin.contracts.create')
         <a href="javascript:void(0)">Payment Info</a>
        @else
            @if($contract->creation_complete)
            <a href="{{route('admin.contracts.payment_info',$contract->id)}}">Payment Info</a>
            @elseif(in_array($current_route_name,['admin.contracts.payment_infor']))
            <a href="{{route('admin.contracts.payment_info',$contract->id)}}">Payment Info</a>
            @else
            <a href="javascript:void(0)">Payment Info</a>
            @endif
        @endif
    </div>
    <div class="col-md-3 bg-success multistep-div {{($current_route_name=='admin.contracts.files')?'active':''}}">
        @if($current_route_name=='admin.contracts.create')
         <a href="javascript:void(0)">Files</a>
        @else
            @if($contract->creation_complete)
            <a href="{{route('admin.contracts.files',$contract->id)}}">Files</a>
            @elseif(in_array($current_route_name,['admin.contracts.files']))
            <a href="{{route('admin.contracts.files',$contract->id)}}">Files</a>
            @else
            <a href="javascript:void(0)">Files</a>
            @endif
        @endif
    </div>
  </div>
</div>