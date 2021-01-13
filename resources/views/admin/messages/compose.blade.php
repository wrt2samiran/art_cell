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
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            
          @include('admin.messages.partials.left-navbar')
          
          </div>
          <!-- /.col -->
            <div class="col-md-9">
              <div class="card card-primary card-outline">

                <form method="post" action="{{route('admin.messages.store')}}" id="message_compose_form" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="upload_unique_key" value="{{$upload_unique_key}}">
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
                  <div class="form-group">
                    <div class="btn btn-default btn-file" id="attachment_block">
                      <i class="fas fa-paperclip"></i> Attachment
                      <input multiple type="file" id="attachment">
                    </div>

                    <!-- <p class="help-block">Max. 32MB</p> -->

                    <div class="progress" style="display: none;">
                      <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" id="progress_bar"></div>
                    </div>

                    <div id="output" class="container-fluid">
                      
                    </div>
                    <div id="uploaded_files" class="container-fluid">
                      
                    </div>

                  </div>
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
      </div>
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
  });


$(document).on('click', '.attachment_remove_btn', function(){  
    
    var delete_url=$(this).data('delete_url');
    var element_to_remove=$(this).closest(".attachment_row");

    swal({
    title: "Are you sure?",
    text: "Once removed, you will not be able to recover this file!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
          
        $.LoadingOverlay("show");
        $.ajax({
          url: delete_url,
          type: "DELETE",
          data:{ 
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "upload_unique_key":"{{$upload_unique_key}}"
          },
          success: function (data) {
            element_to_remove.remove();
            $.LoadingOverlay("hide");
            toastr.success('File successfully removed.', 'Success', {timeOut: 5000});
          },
          error: function(jqXHR, textStatus, errorThrown) {
             $.LoadingOverlay("hide");
             var response=jqXHR.responseJSON;
             var status=jqXHR.status;
             if(status=='404'){
              toastr.error('Invalid URL', 'Error', {timeOut: 5000});
             }
             else if(status=='400'){
                toastr.error(response.message, 'Error', {timeOut: 5000});
             }
             else{
               toastr.error('Internal server error.', 'Error', {timeOut: 5000});
             }
          }
       });

       
      } 
    });

});




$('#attachment').on('change',function(){
    
    var files = document.getElementById("attachment").files;

        var file_size_error=false;
        var file_type_error=false;
        for (var i = 0; i < files.length; i++)
        {
            var file_size_in_kb=(files[i].size/1024);
            var file_type= files[i].type;

            if(file_size_in_kb>2048){
               file_size_error=true; 
            }

            var allowed_file_types=['application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'text/plain'
            ];

            if(!allowed_file_types.includes(file_type)){
                file_type_error=true;
            }

        }

        if(file_size_error==true || file_type_error==true){
            reset($('#attachment'));

            var error_message='';

            if(file_size_error==true && file_type_error==true){
                error_message="Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files of max size 2mb";
            }else if(file_size_error==true && file_type_error==false){
                error_message="File size should not be more than 2 mb";
            }else{
                error_message="Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files";
            }

            swal(error_message);

        }else{

          var data = new FormData();
          data.append('upload_unique_key', '{{$upload_unique_key}}');
          

          var filesLength=document.getElementById('attachment').files.length;
          for(var i=0;i<filesLength;i++){
            data.append("attachments[]", document.getElementById('attachment').files[i]);
          }

          $('#attachment_block').hide();
          $('.progress').show();

          var config = {
            onUploadProgress: function(progressEvent) {
              var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );

              var width=percentCompleted+'%';
              $('#progress_bar').css({"width":width});
              //console.log(percentCompleted);
            }
          };

          axios.post('{{route("admin.messages.upload_attachments")}}', data, config)
            .then(function (res) {
              reset($('#attachment'));
              output.className = 'container-fluid';
              output.innerHTML = '';
              $('#progress_bar').css({"width":"0%"});
              $('#attachment_block').show();
              $('.progress').hide();
              //uploaded_files
              var uploaded_files=res.data;
               

              if(uploaded_files.length){
                uploaded_files.forEach(function(file){

                  var url = '{{ route("admin.messages.remove_attachment", ":id") }}';
                  url = url.replace(':id', file.id);
                  var single_file=`<div  class="attachment_row row mt-1 mb-1">
                  <div class="col-sm-12 bg-success">
                  <div class="d-flex justify-content-between  p-1">
                  <div>${file.file_name}</div>
                  <div>
                  <a class="attachment_remove_btn" href="javascript:void(0)" data-id=`+file.id+` data-delete_url=`+url+` style="color:red;fon">Remove</a>
                  </div>
                  </div>
                  
                  </div>
                  </div>`;
                  $('#uploaded_files').append(single_file);
                });
              }
              //console.log(uploaded_files);

            })
            .catch(function (err) {
              reset($('#attachment'));
              $('#progress_bar').css({"width":"0%"});
              $('#attachment_block').show();
              $('.progress').hide();
              output.className = 'container-fluid text-danger';
              output.innerHTML = err.message;
          });




        }
   
});


/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}





</script>
@endpush


