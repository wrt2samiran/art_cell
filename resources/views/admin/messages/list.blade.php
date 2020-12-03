@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Messages</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Messages</li>
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
              <h3 class="card-title">Inbox</h3>

              <div class="card-tools">
                 <form action="{{route('admin.messages.list')}}">
                <div class="input-group input-group-sm">
                 
                    <input value="{{request()->keyword}}" type="text" name="keyword" class="form-control" placeholder="Search Mail">
                    <div class="input-group-append">
                      @if(request()->keyword)
                      <a href="{{route('admin.messages.list')}}" class="btn btn-danger">
                        <i class="fas fa-times-circle"></i>
                      </a>
                      @else
                      <button class="btn btn-primary">
                        <i class="fas fa-search"></i>
                      </button>
                      @endif

                    </div>
                  
                </div>
                </form>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
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
              <div class="mailbox-controls">
               
<!--                 <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                </div>
                
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
                <div class="float-right">
                  
                  <div class="btn-group">

                  </div>
                  
                </div> -->
                
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover">
                  <tbody>
                  @forelse($messages as $message)
                  <tr class="{{(!$message->is_read)?'message-unread':'message-read'}}">
<!--                     <td>
                      <div class="icheck-primary">
                        <input type="checkbox" value="" id="check_{{$message->id}}">
                        <label for="check_{{$message->id}}"></label>
                      </div>
                    </td> -->
                    
                    <td class="mailbox-name"><a href="{{route('admin.messages.details',$message->id)}}">{{$message->to_user->name}}</a></td>
                    <td class="mailbox-subject">
                      <a href="{{route('admin.messages.details',$message->id)}}" style="color: black">
                      <div >
                        <div style="display: inline-block;"><b>{{Str::limit($message->subject,10,'...')}}- </b></div>
                        <div style="display: inline-block;">
                          
                          {{ Str::limit(strip_tags($message->message), $limit = 50, $end = '...') }}
                        </div>
                      </div>
                      </a>
                      
                    </td>
                    <!-- <td class="mailbox-attachment"></td> -->
                    <td class="mailbox-date">{{$message->created_at->diffForHumans()}}</td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="3">No messages</td>
                  </tr>
                  @endforelse
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div class="mailbox-controls">

                <div class="float-right">

                  {{$messages->appends(request()->query())->links()}}
                </div>
                <!-- /.float-right -->
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
        <!-- /.content -->
    </div>
@endsection
@push('custom-scripts')
<!-- <script type="text/javascript" src="{{asset('js/admin/notifications/list.js')}}"></script> -->
<script>
  $(function () {
    //Enable check and uncheck all functionality
    $('.checkbox-toggle').click(function () {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      $(this).data('clicks', !clicks)
    })

    //Handle starring for glyphicon and font awesome
    $('.mailbox-star').click(function (e) {
      e.preventDefault()
      //detect type
      var $this = $(this).find('a > i')
      var glyph = $this.hasClass('glyphicon')
      var fa    = $this.hasClass('fa')

      //Switch states
      if (glyph) {
        $this.toggleClass('glyphicon-star')
        $this.toggleClass('glyphicon-star-empty')
      }

      if (fa) {
        $this.toggleClass('fa-star')
        $this.toggleClass('fa-star-o')
      }
    })
  })
</script>
@endpush


