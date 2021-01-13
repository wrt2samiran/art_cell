@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Message Details</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.messages.list')}}">Messages</a</li>
                  <li class="breadcrumb-item active">Details</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

          @include('admin.messages.partials.left-navbar')
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Read Message</h3>

                <div class="card-tools">

                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="mailbox-read-info">
                  <h6>Subject : {{$message->subject}}</h6>
                  <h6>
                @if($message->message_from==auth()->guard('admin')->id())
                  To: {{$message->to_user->name}}-{{$message->to_user->email}}
                @else
                  From: {{$message->from_user->name}}-{{$message->from_user->email}}
                @endif
                    
                  <span class="mailbox-read-time float-right">{{$message->created_at->format('d/m/Y g:i A')}}</span></h6>
                </div>

                <div class="mailbox-read-message">
                  {!!$message->message!!}
                </div>
              
              </div>
              @if(count($message->message_attachments))
              <div class="card-footer bg-white">
                <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                  @foreach($message->message_attachments as $attachment)

                  @if($attachment->file_type=='pdf')
                  <li>
                    <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

                    <div class="mailbox-attachment-info">
                      <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i>{{$attachment->file_name}}</a>
                          <span class="mailbox-attachment-size clearfix mt-1">
                            <!-- <span>1,245 KB</span> -->
                            <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                          </span>
                    </div>
                  </li>
                  @elseif($attachment->file_type=='doc')

                  <li>
                    <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>

                    <div class="mailbox-attachment-info">
                      <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i>{{$attachment->file_name}}</a>
                          <span class="mailbox-attachment-size clearfix mt-1">
                            <!-- <span>1,245 KB</span> -->
                            <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                          </span>
                    </div>
                  </li>
                  @elseif($attachment->file_type=='text')
                  <li>
                    <span class="mailbox-attachment-icon"><i class="far fa-file-alt"></i></span>

                    <div class="mailbox-attachment-info">
                      <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i>{{$attachment->file_name}}</a>
                          <span class="mailbox-attachment-size clearfix mt-1">
                            <!-- <span>1,245 KB</span> -->
                            <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                          </span>
                    </div>
                  </li>
                  @elseif($attachment->file_type=='image')
                  <li>
                    <span class="mailbox-attachment-icon has-img"><img src="{{asset('uploads/message_attachments/'.$attachment->file_name)}}" alt="Attachment"></span>

                    <div class="mailbox-attachment-info">
                      <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="mailbox-attachment-name"><i class="fas fa-camera"></i>{{$attachment->file_name}}</a>
                          <span class="mailbox-attachment-size clearfix mt-1">
                            <!-- <span>2.67 MB</span> -->
                            <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                          </span>
                    </div>
                  </li>
                  @else
                  <li>
                    <span class="mailbox-attachment-icon"><i class="fas fa-file"></i></span>

                    <div class="mailbox-attachment-info">
                      <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> {{$attachment->file_name}}</a>
                          <span class="mailbox-attachment-size clearfix mt-1">
                            <!-- <span>1,245 KB</span> -->
                            <a href="{{route('admin.messages.download_attachment',$attachment->id)}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                          </span>
                    </div>
                  </li>
                  @endif


                  @endforeach

                </ul>
              </div>
              @endif
              <!-- /.card-footer -->
              <div class="card-footer">
                @if($message->message_from==auth()->guard('admin')->id())
                <a class="btn btn-primary" href="{{route('admin.messages.sent')}}"><i class="fas fa-backward"></i>&nbsp;Back</a>
                @else
                <a class="btn btn-primary" href="{{route('admin.messages.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a>
                @endif
                
              </div>
              <!-- /.card-footer -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
    </section>
        <!-- /.content -->
    </div>
@endsection


