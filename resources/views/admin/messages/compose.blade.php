@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Compose Message</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.messages.list')}}">Messages</a></li>
                  <li class="breadcrumb-item active">Compose</li>
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

              <form method="post" action="{{route('admin.messages.store')}}" id="message_compose_form" enctype="multipart/form-data">
                @csrf
              <div class="card-header">
                <h3 class="card-title">Compose New Message</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                  <select class="form-control " id="message_to" name="message_to" style="width: 100%;">
                    <option value="">Select Reciepent</option>
                    @forelse($users as $user)
                       <option value="{{$user->id}}" >
                        {{$user->name}}-{{$user->email}} 
                        @if($user->role->user_type->slug=='property-owner')

                          @if($user->created_by_admin)
                          (property manager)
                          @else
                          ({{$user->role->user_type->name}})
                          @endif
                        
                        @else
                        ({{$user->role->user_type->name}})
                        @endif
                      </option>
                    @empty
                    <option value="">No users</option>
                    @endforelse
                  </select>
                  <div id="message_to_error"></div>
                </div>
                <div class="form-group">
                  <input class="form-control" name="subject" placeholder="Subject:">
                </div>
                <div class="form-group">
                    <textarea id="message" name="message" class="form-control" style="height: 300px"></textarea>
                    <div id="message_error"></div>
                </div>
<!--                 <div class="form-group">
                  <div class="btn btn-default btn-file">
                    <i class="fas fa-paperclip"></i> Attachment
                    <input type="file" name="attachment">
                  </div>
                  <p class="help-block">Max. 32MB</p>
                </div> -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div class="float-right">
                  <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                </div>
                <a href="{{route('admin.messages.list')}}" class="btn btn-default"><i class="fas fa-times"></i> Discard</a>
              </div>
              <!-- /.card-footer -->
              </form>

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
<script type="text/javascript" src="{{asset('js/admin/messages/compose.js')}}"></script>

<!-- Page Script -->
<script>
  $(function () {
    //Add text editor
    $('#message').summernote()
  })
</script>
@endpush


