@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Complaint Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.complaints.list')}}">Complaints</a></li>
              <li class="breadcrumb-item active">Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card card-success">
                <div class="card-header">
                  Complaint Details
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
                  <div class="row">
                    <div class="col-sm-8">
                      <table class="table table-bordered table-hover record-details-table" id="property-details-table">
                        <tbody>
                          <tr>
                            <td>Complaint against contract</td>
                            <td >{{$complaint->contract->title}} ({{$complaint->contract->code}})</td>
                          </tr>
                          <tr>
                            <td>Complaint agains work order</td>
                            <td>
                              @if($complaint->work_order)
                                {{$complaint->work_order->task_title}}
                              @else
                              N/A
                              @endif
                            </td>
                          </tr>
                          <tr>
                            <td>Subject</td>
                            <td >{{$complaint->subject}}</td>
                          </tr>
                          <tr>
                            <td>Complaint Details</td>
                            <td >{{$complaint->details}}</td>
                          </tr>
                          <tr>
                            <td>Created At</td>
                            <td>{{$complaint->created_at->format('d/m/Y')}}</td>
                          </tr>
                        </tbody>
    
                    </table>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Update Complaint Status</h5>
                                <form class="form-inline" action="{{route('admin.complaints.update_status',$complaint->id)}}" method="post">
                                    @csrf
                                    @method("PUT")
                                  <label for="email" class="mr-sm-2">Status:</label>
                              
                                    <select class="form-control mb-2 mr-sm-2" name="status" id="status">
                                        @if(count($complaint_statusses))
                                          @foreach($complaint_statusses as $complaint_status)
                                            <option {{($complaint_status->id==$complaint->complaint_status_id)?'selected':''}} value="{{$complaint_status->id}}">{{$complaint_status->status_name}}</option>
                                          @endforeach
                                        @else
                                          <option value="">No complaint statusses</option>
                                        @endif
                                    </select>
                                  <button type="submit" class="btn btn-success mb-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                  </div>


                  <div class="row d-flex justify-content-center mt-100 mb-100">
                      <div class="col-lg-12">
                          <div class="card">
                              <div class="card-body text-center">
                                  <h4 class="card-title">Latest Notes <a href="javascript:add_new_note()" class="btn btn-success">Add New Note</a></h4>
                              </div>
                              <div class="comment-widgets">

                                @if(count($notes))
                                @foreach($notes as $note)
                                <!-- Comment Row -->
                                <div class="d-flex flex-row comment-row m-t-0">
                                    <div class="p-2"><img src="{{$note->user->profile_image_url()}}" alt="user" width="50" class="rounded-circle"></div>
                                    <div class="comment-text w-100">
                                        <h6 class="font-medium">{{$note->user->name}} ({{$note->user->role->role_name}})</h6>
                                         <span class="m-b-15 d-block">{{$note->note}}</span>
                                         @if($note->file)
                                         <span class="m-b-15 d-block"><a href="{{asset('uploads/complaint_files/'.$note->file)}}">View/Download file</a></span>
                                         @endif
                                        <div class="comment-footer">
                                        <span class="text-muted float-right">{{$note->created_at->format('d/m/Y')}}</span>
                                        @if($note->user_id==auth()->guard('admin')->id())
                                        
                                        @php
                                          if($note->file){
                                            $file_url=asset('uploads/complaint_files/'.$note->file);
                                          }else{
                                            $file_url='';
                                          }
                                       
                                        @endphp

                                        <button type="button" data-file_url="{{$file_url}}" data-edit_url="{{route('admin.complaints.update_note',['complaint_id'=>$complaint->id,'note_id'=>$note->id])}}" data-note_data="{{json_encode($note)}}" class="btn btn-success btn-sm edit_note_button" >Edit</button>
                                        <button type="button" data-delete_url="{{route('admin.complaints.delete_note',['complaint_id'=>$complaint->id,'note_id'=>$note->id])}}" class="btn btn-danger btn-sm delete_note_button">Delete</button></div>
                                        @endif
                                        
                                    </div>
                                </div> <!-- Comment Row -->
                                @endforeach
                                @else
                                <div class="d-flex flex-row comment-row m-t-0">
                                  <p>No Notes</p>
                                </div> <!-- Comment Row -->
                                @endif

                              </div> <!-- Card -->

                              <div class="ml-2 mr-2 mt-2">
                                {{$notes->links()}}
                              </div>
                          </div>
                      </div>
                  </div>


                </div>
                <div class="card-footer">
                  <a class="btn btn-primary" href="{{route('admin.complaints.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a>
                </div> 
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
</div>
@include('admin.complaints.modals.add_note_modal')
@include('admin.complaints.modals.edit_note_modal')
@endsection 
@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/complaints/show.js')}}"></script>
@endpush
