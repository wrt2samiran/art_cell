@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Email Template Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.email.list')}}">Email</a></li>
              <li class="breadcrumb-item active">Edit</li>
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
                <h3 class="card-title">Edit Email Template</h3>
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
                      <form  method="post" id="admin_email_edit_form" action="{{route('admin.email.edit',$details->id)}}" method="post" >
                        @csrf
                        
                        <div>
                          <div class="form-group required">
                            <label for="name">Teplate Name<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('template_name')?old('template_name'):$details->template_name}}" name="template_name" id="template_name"  placeholder="Template Name">
                            @if($errors->has('template_name'))
                            <span class="text-danger">{{$errors->first('template_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="name">Variables Name Comma Separator<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('variable_name')?old('variable_name'):$details->variable_name}}" name="variable_name" id="variable_name"  placeholder="Template Name">
                            @if($errors->has('variable_name'))
                            <span class="text-danger">{{$errors->first('variable_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="description">Description <span class="error">*</span></label>
                             <textarea rows="5" class="form-control"  name="content" id="content"  placeholder="Content">{{old('content')?old('content'):$details->content}}</textarea>
                             @if($errors->has('content'))
                              <span class="text-danger">{{$errors->first('content')}}</span>
                             @endif
                          </div>

                          

                        </div>
                        <input type="hidden" name="email_id" id="email_id" value="{{$details->id}}">
                        <div>
                           <a href="{{route('admin.email.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
                           <button type="submit" class="btn btn-success">Submit</button> 
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
<!-- *********Used for CK Editor ***************-->
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
CKEDITOR.replace( 'content' );
</script>
<!-- *********Used for CK Editor ***************-->
<script type="text/javascript" src="{{asset('js/admin/email_template/edit.js')}}"></script>
@endpush
