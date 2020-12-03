

@if(request()->route()->getName()=='admin.messages.compose')
<a href="{{route('admin.messages.list')}}" class="btn btn-primary btn-block mb-3">Back to Inbox</a>
@else
<a href="{{route('admin.messages.compose')}}" class="btn btn-primary btn-block mb-3">Compose</a>
@endif
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Folders</h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body p-0">
    <ul class="nav nav-pills flex-column">
      <li class="nav-item active">
        <a href="{{route('admin.messages.list')}}" class="nav-link {{(request()->route()->getName()=='admin.messages.list')?'active':''}}">
          <i class="fas fa-inbox"></i> Inbox
          @if($message_count=Helper::get_unread_message_count()>0)
            <span class="badge bg-primary float-right">{{$message_count}}</span>
          @endif
          
        </a>
      </li>
      <li class="nav-item">
        <a href="{{route('admin.messages.sent')}}" class="nav-link {{(request()->route()->getName()=='admin.messages.sent')?'active':''}}">
          <i class="far fa-envelope"></i> Sent
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