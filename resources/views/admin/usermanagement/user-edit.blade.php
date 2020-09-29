@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.user-management.user.list')}}">User List</a></li>
              <li class="breadcrumb-item active">User Edit</li>
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
                    <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">{{$panel_title}}</h3>
                                </div>
                            <!-- /.card-header -->
                        <div class="card-body">
        
                    
                            @if(count($errors) > 0)
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    @foreach ($errors->all() as $error)
                                        <span>{{ $error }}</span><br/>
                                    @endforeach
                                </div>
                            @endif

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

                            @php
                                $settingsDetails= (object)json_decode($details->setting_json);                                   
                            @endphp

                            <form action="{{route('admin.user-management.user-editSubmit',[$encryptCode])}}" method="POST"  id="user_edit">
                                    {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-10">
                                    
                                        <div class="form-group row">
                                            <label for="Title" class="col-sm-2 col-form-label">Name : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="name" id="name"  value="{{$details->name}}" class="form-control" placeholder=" Name" title="Name">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label for="Title" class="col-sm-2 col-form-label">Email : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="email" id="email" value="{{$details->email}}" class="form-control" placeholder=" Email" title="Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Title" class="col-sm-2 col-form-label">Phone : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="phone" id="phone" value="{{$details->phone}}" class="form-control" placeholder=" Phone" title="Phone">
                                            </div>
                                        </div>
                                           
                                            
                                            @if($details->usertype == 'FU')
                                            
                                        <div class="form-group row">
                                            <label for="Title" class="col-sm-2 col-form-label">Website : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="url" name="website" id="website"  value="{{$settingsDetails->website}}" class="form-control" placeholder=" website" title="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Title" class="col-sm-2 col-form-label">Facebook : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="url" name="facebook_url" id="facebook_url"  value="{{$settingsDetails->facebook_url}}" class="form-control" placeholder=" Facebook" title="Facebook">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Title" class="col-sm-2 col-form-label">Twitter : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="url" name="twitter_url" id="twitter_url"  value="{{$settingsDetails->twitter_url}}" class="form-control" placeholder=" Twitter" title="Twitter">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Title" class="col-sm-2 col-form-label">Linkedin : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="url" name="linkedin_url" id="linkedin_url"  value="{{$settingsDetails->linkedin_url}}" class="form-control" placeholder="Linkedin" title="Linkedin">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label  class="col-sm-2 col-form-label">Additional Information : <span class="error">*</span></label>
                                                
                                            <div class="col-sm-10">
                                                <textarea class="textarea form-control" name="additional_info" id="additional_info" placeholder="Place some text here"
                                                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$settingsDetails->additional_info}}</textarea>
                                            </div>
                                        </div>
                                            @endif

                                            @if($details->usertype != 'FU')
                                        <div class="form-group row">
                                                <label for="Title" class="col-sm-2 col-form-label">Roles : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                                    <select name='role_id' id='role_id' class="form-control">
                                                            <option value="">Select</option>
                                                            @foreach ($allRole as $key => $row)
                                                            <option value="{{  $row->id  }}" @if($row->id == $details->role_id ){{'selected'}} @endif>{{ $row->role_name }}</option>
                                                            @endforeach  
                                                    </select>
                                            </div>
                                        </div>
                                            @endif



                                        <div class="form-group row">
                                            <label for="Title" class="col-sm-2 col-form-label">Status : <span class="error">*</span></label>
                                            <div class="col-sm-10">
                                            <select class="form-control select2 select2-danger" name="status" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                    <option value="">Choose option</option>
                                                    <option value="A" {{ $details->status == 'A' ? 'selected' : '' }}>Active</option>
                                                    <option value="I" {{ $details->status == 'I' ? 'selected' : '' }} >Inactive</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                                <div class="">
                                                    <a class="btn btn-primary back_new" href="{{route('admin.user-management.user.list')}}">Back</a>
                                                    <button id="" type="submit" class="btn btn-success submit_new">Update</button>
                                                </div>
                                        </div>
                                            
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
  </div>
  @endsection
      

         
         
              
             

                

      