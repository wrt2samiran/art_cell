@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Message Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.message.list')}}">Message</a></li>
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
                <h3 class="card-title">Edit Message</h3>
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
                      <form  method="post" id="admin_message_edit_form" action="{{route('admin.message.edit',$details->id)}}" method="post" >
                        @csrf
                        
                        <div>
                          <div class="row">
                            
                            <div class="col-md-12" id="contract">
                              <div class="form-group required">
                                <label for="contract_id">Select Contract<span class="error">*</span></label>
                                 <select class="form-control " id="contract_id" name="contract_id" style="width: 100%;">
                                   <option value="">Select Contract</option>
                                   @forelse($contract as $property)
                                      <option value="{{$property->id}}" @if($property->id == $details['contract_id']) selected="selected" @endif data-userId="{{ $property->property_manager_id }} {{ $property->service_provider_id }}">{{$property->title}}({{$property->code}})</option>
                                   @empty
                                   <option value="">No Property Found</option>
                                   @endforelse
                                 </select>
                             </div>
                            </div>

                            <div class="col-md-12" id="user" style="display:none">
                              <div class="form-group required">
                                <label for="user_id">Select User<span class="error">*</span></label>
                                 <select class="form-control " id="user_id" name="user_id" style="width: 100%;">
                                   @forelse($userData as $data)
                                      <option value="{{$data->id}}" @if($data->id == $details['user_id']) selected="selected" @endif>{{$data->name}}</option>
                                   @empty
                                   <option value="">No User Found</option>
                                   @endforelse
                                 </select>
                             </div>
                            </div>
                            {{-- <div class="col-md-12" id="service_provider" style="display: none;">
                              <div class="form-group required">
                                <label for="service_id">Select Service Provider<span class="error">*</span></label>
                                 <select class="form-control " id="service_id" name="service_id" style="width: 100%;">
                                   <option value="">Select Service Provider</option>
                                   @forelse($serviceData as $data)
                                      <option value="{{$data->id}}" >{{$data->name}}</option>
                                   @empty
                                   <option value="">No Service Provider Found</option>
                                   @endforelse
                                 </select>
                             </div>
                            </div> --}}
                               
                        </div>
                          <div class="form-group required">
                            <label for="name">Message Title <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):$details->name}}" name="name" id="name"  placeholder="Shared Service Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="description">Description <span class="error">*</span></label>
                             <textarea rows="5" class="form-control"  name="description" id="description"  placeholder="Description">{{old('description')?old('description'):$details->description}}</textarea>
                             @if($errors->has('description'))
                              <span class="text-danger">{{$errors->first('description')}}</span>
                             @endif
                          </div>

                          

                        </div>
                        <input type="hidden" name="message_id" id="message_id" value="{{$details->id}}">
                        <div>
                           <a href="{{route('admin.message.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
CKEDITOR.replace( 'description' );
$(document).on('change','#contract_id',function(e){
    e.preventDefault();
    let userId = $(this).find(':selected').attr('data-userId');
    $('#user_id').val(userId).trigger("change");
  })
  // $(document).on('change','#contract_id',function(e){
  //   e.preventDefault();
  //   let serviceId = $(this).find(':selected').attr('data-serviceId');
  //   $('#service_id').val(serviceId).trigger("change");
  // })
  $('#contract').on('change', function() {
    $('#user').show();
  })
</script>
<!-- *********Used for CK Editor ***************-->
<script type="text/javascript" src="{{asset('js/admin/message/edit.js')}}"></script>
@endpush
