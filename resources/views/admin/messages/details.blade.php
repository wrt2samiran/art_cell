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
                  
                <span class="mailbox-read-time float-right">{{$message->created_at->format('d/m/Y')}}</span></h6>
              </div>

              <div class="mailbox-read-message">
                {!!$message->message!!}
              </div>
            
            </div>

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
    </section>
        <!-- /.content -->
    </div>
@endsection


