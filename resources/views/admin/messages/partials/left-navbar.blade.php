

@if(request()->route()->getName()=='admin.messages.compose')
<a href="{{route('admin.messages.list')}}" class="btn btn-primary btn-block mb-3">{{__('general_sentence.button_and_links.back')}}</a>
@else
  @if(auth()->guard('admin')->user()->hasAllPermission(['send-message']))
  <a href="{{route('admin.messages.compose')}}" class="btn btn-primary btn-block mb-3">{{__('general_sentence.button_and_links.compose')}}</a>
  @endif
@endif
<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{__('message_module.folders')}}</h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body p-0">
    <ul class="nav nav-pills flex-column">
      @if(auth()->guard('admin')->user()->hasAllPermission(['view-messages']))
      <li class="nav-item active">
        <a href="{{route('admin.messages.list')}}" class="nav-link {{(request()->route()->getName()=='admin.messages.list')?'active':''}}">
          <i class="fas fa-inbox"></i> {{__('general_sentence.button_and_links.inbox')}}
          @if($message_count=Helper::get_unread_message_count()>0)
            <span class="badge bg-primary float-right">{{$message_count}}</span>
          @endif
          
        </a>
      </li>
      @endif
      <li class="nav-item">
        <a href="{{route('admin.messages.sent')}}" class="nav-link {{(request()->route()->getName()=='admin.messages.sent')?'active':''}}">
          <i class="far fa-envelope"></i> {{__('general_sentence.button_and_links.sent')}}
        </a>
      </li>
<!--       <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="far fa-trash-alt"></i> Trash
        </a>
      </li> -->
    </ul>
  </div>
  <!-- /.card-body -->
</div>