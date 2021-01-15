@extends('admin.layouts.after-login-layout')


@section('unique-content')
<style type="text/css">
  .table-fixed{
  width: 100%;
  background-color: #f3f3f3;
  }

  .table-fixed tbody{
    max-height:247;
    overflow-y:auto;
    display: block;
  }
   .table-fixed thead{
    width: 100%;
    display: block;
   }
  .table-fixed tbody td{
       
  }
  .table-fixed thead tr th {
/*    background-color: #f39c12;
    border-color:#e67e22;*/
  }

</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('complaint_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.complaints.list')}}">{{__('general_sentence.breadcrumbs.complaints')}}</a></li>
              <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.details')}}</li>
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
                  {{__('complaint_module.complaint_details')}}
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
                    <div class="col-sm-7">
                      <table class="table table-bordered table-hover record-details-table" id="property-details-table">
                        <tbody>
                          <tr>
                            <td>{{__('complaint_module.labels.contract')}}</td>
                            <td >{{$complaint->contract->title}} ({{$complaint->contract->code}})</td>
                          </tr>
                          <tr>
                            <td>{{__('complaint_module.labels.work_order')}}</td>
                            <td>
                              @if($complaint->work_order)
                                {{$complaint->work_order->task_title}}
                              @else
                              N/A
                              @endif
                            </td>
                          </tr>
                          <tr>
                            <td>{{__('complaint_module.labels.subject')}}</td>
                            <td >{{$complaint->subject}}</td>
                          </tr>
                          <tr>
                            <td>{{__('complaint_module.labels.complaint')}}</td>
                            <td >{{$complaint->details}}</td>
                          </tr>
                          <tr>
                            <td>{{__('complaint_module.labels.complaint_by')}}</td>
                            <td >{{$complaint->user_display_title()}}</td>
                          </tr>
                          <tr>
                            <td>{{__('complaint_module.labels.created_at')}}</td>
                            <td>{{$complaint->created_at->format('d/m/Y g:i A')}}</td>
                          </tr>
                        </tbody>
    
                    </table>
                    </div>
                    <div class="col-sm-5">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>{{__('complaint_module.update_complaint_status')}}</h5>
                                <form class="form-inline" action="{{route('admin.complaints.update_status',$complaint->id)}}" method="post">
                                    @csrf
                                    @method("PUT")
                                  <label for="email" class="mr-sm-2">{{__('complaint_module.labels.status')}}:</label>
                                    <select class="form-control mb-2 mr-sm-2" name="status" id="status">
                                        @if(count($complaint_statusses))
                                          @foreach($complaint_statusses as $complaint_status)
                                            <option {{($complaint_status->id==$complaint->complaint_status_id)?'selected':''}} value="{{$complaint_status->id}}">{{$complaint_status->status_name}}</option>
                                          @endforeach
                                        @else
                                          <option value="">No complaint statusses</option>
                                        @endif
                                    </select>
                                  @if(auth()->guard('admin')->user()->hasAllPermission(['complaint-status-change']))
                                  <button type="submit" class="btn btn-success mb-2">Update</button>
                                  @endif
                                </form>
                            </div>
                            <div class="col-md-12">
                            <div >
                              <table class="table table-fixed">
                                <thead>
                                  <tr class="row p-0 m-0">
                                    <th class="col">{{__('complaint_module.labels.updated_by')}}</th>
                                    <th class="col">{{__('complaint_module.labels.status')}}</th>
                                    <th class="col">{{__('complaint_module.labels.updated_at')}}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @forelse($complaint_status_updates as $update)
                                  <tr class="row p-0 m-0">
                                    <td class="col">{{$update->user_display_title()}}</td>
                                    <td class="col">{{$update->status_from}}-{{$update->status_to}}</td>
                                    <td class="col">{{$update->created_at->format('d/m/Y g:i A')}}</td>
                                  </tr>
                                  @empty
                                  <tr class="row p-0 m-0">
                                    <td class="col">{{__('complaint_module.no_updates')}}</td>
                                  </tr>
                                  @endforelse
                                </tbody>
                              </table>
                            </div>
                            </div>
                        </div>
                    </div>
                  </div>

                  <div class="row d-flex justify-content-center mt-100 mb-100">
                      <div class="col-lg-12">
                          <div class="card">
                              <div class="card-body text-center">
                                  <h4 class="card-title">{{__('complaint_module.latest_notes')}} 
                                    @if(auth()->guard('admin')->user()->hasAllPermission(['complaint-add-note']))
                                    <a href="javascript:add_new_note()" class="btn btn-success">{{__('complaint_module.add_note_button')}}</a>
                                    @endif
                                  </h4>
                              </div>
                              <div class="comment-widgets">

                                @if(count($notes))
                                @foreach($notes as $note)
                                <!-- Comment Row -->
                                <div class="d-flex flex-row comment-row m-t-0">
                                    <div class="p-2"><img src="{{$note->user->profile_image_url()}}" alt="user" width="50" class="rounded-circle"></div>
                                    <div class="comment-text w-100">
                                        <h6 class="font-medium">
                                          {{$note->user_display_title()}}
                                        </h6>
                                         <span class=" d-block">{{$note->note}}</span>
                                         <span class="text-muted m-b-15 d-block"><i class="far fa-clock"></i> {{$note->created_at->format('d/m/Y g:i A')}}</span>
                 
                                         @if($note->file)
                                         <span class="m-b-15 d-block"><a href="{{asset('uploads/complaint_files/'.$note->file)}}">{{__('complaint_module.download_file')}}</a></span>
                                         @endif

                                        <div class="comment-footer">
                                        
                                        @if($note->user_id==auth()->guard('admin')->id())
                                        
                                        @php
                                          if($note->file){
                                            $file_url=asset('uploads/complaint_files/'.$note->file);
                                          }else{
                                            $file_url='';
                                          }
                                        @endphp

                                          @if(auth()->guard('admin')->user()->hasAllPermission(['complaint-edit-note']))
                                          <button type="button" data-file_url="{{$file_url}}" data-edit_url="{{route('admin.complaints.update_note',['complaint_id'=>$complaint->id,'note_id'=>$note->id])}}" data-note_data="{{json_encode($note)}}" class="btn btn-success btn-sm edit_note_button" >{{__('general_sentence.button_and_links.edit')}}</button>
                                          @endif
                                          @if(auth()->guard('admin')->user()->hasAllPermission(['complaint-delete-note']))
                                          <button type="button" data-delete_url="{{route('admin.complaints.delete_note',['complaint_id'=>$complaint->id,'note_id'=>$note->id])}}" class="btn btn-danger btn-sm delete_note_button">{{__('general_sentence.button_and_links.delete')}}</button>
                                          @endif
                                        @endif
                                        </div>
                                        
                                    </div>
                                </div> <!-- Comment Row -->
                                @endforeach
                                @else
                                <div class="d-flex flex-row comment-row m-t-0">
                                  <p>{{__('complaint_module.no_notes')}}</p>
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
                  <a class="btn btn-primary" href="{{route('admin.complaints.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a>
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

