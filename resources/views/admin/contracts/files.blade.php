@extends('admin.layouts.after-login-layout')


@section('unique-content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Contract Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.contracts.list')}}">Contracts</a></li>
              <li class="breadcrumb-item active">{{($contract->creation_complete)?'Edit':'Create'}}</li>
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
                <h3 class="card-title">{{($contract->creation_complete)?'Edit':'Create'}} Contract</h3>
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
                    @include('admin.contracts.partials.multi_step_links')
                    <div class="col-md-11 col-sm-12">
                      <form id="contract_files_form" action="{{route('admin.contracts.store_files',$contract->id)}}"  method="post" enctype="multipart/form-data">
                        @csrf
                        <!-- setting data shared from app service provider -->
                        @php
                        $max_filesize=(isset($setting['contract-and-property-max-filesize']))?$setting['contract-and-property-max-filesize']:'1';
                        @endphp
                        <input type="hidden" value="{{$max_filesize}}"  id="max_filesize">
                        <div>
                          <div class="form-group required">
                            <label>Attach Files</label>
                            <div>
                              <button type="button" id="add_new_file" class="btn btn-outline-success"><i class="fa fa-plus"></i>&nbsp;Add File</button>
                            </div>
                          </div>
                          <div id="files_container">
                            @if(count($files=$contract->contract_attachments))
                              @foreach($files as $file)
                                <div class="row mt-1 files_row">
                                  <div class="col-md-6">
                                    <input placeholder="Title" class="form-control file_title_list"  id="title_{{$file->id}}" value="{{$file->title}}" name="title[]" type="text">
                                  </div>
                                  <div class="col-md-5">
                                    <input data-is_required="no" placeholder="File" class="form-control file_list"  id="contract_files_{{$file->id}}" name="contract_files[]" type="file" aria-describedby="imageHelp{{$file->id}}">
                                    <small class="form-text text-muted">
                                      Upload PDF/DOC/JPEG/PNG/TEXT files of max. {{$max_filesize}}mb
                                    </small>
                                    <small id="imageHelp{{$file->id}}" class="form-text text-muted"><b>Leave blank if you do not want to update image.(<a href="{{route('admin.contracts.download_attachment',$file->id)}}">download file</a>)</b></small>
                                  </div>
                                  <div class="col-md-1">
                                    <button type="button" data-delete_url="{{route('admin.contracts.delete_attachment_through_ajax',$file->id)}}" class="btn btn-danger files_row_del_btn"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  </div>
                                  <input type="hidden" name="file_id[]" value="{{$file->id}}">
                                </div>
                              @endforeach
                            @endif
                          </div>
                        </div>
                        <hr class="mt-3 mb-3">
                        <div>
                           <a href="{{route('admin.contracts.payment_info',$contract->id)}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Previous</a>
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
<script type="text/javascript" src="{{asset('js/admin/contracts/files.js')}}"></script>
@endpush
