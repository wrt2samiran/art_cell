@extends('admin.layouts.after-login-layout')


@section('unique-content')
@php $current_user=auth()->guard('admin')->user(); @endphp

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
              <li class="breadcrumb-item active">Create</li>
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
                <h3 class="card-title">Create Message</h3>
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
                      <form  method="post" id="admin_message_add_form" action="{{route('admin.message.add')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                          <div class="row">
                            @if ($current_user->role->user_type->slug !='super-admin')
                            <div class="col-md-12" id="contract">
                              <div class="form-group required">
                                <label for="contract_id">Select Contract<span class="error">*</span></label>
                                 <select class="form-control " id="contract_id" name="contract_id" style="width: 100%;">
                                   <option value="">Select Contract</option>
                                   @forelse($contract as $property)
                                      <option value="{{$property->id}}" data-userId="{{ $property->property_manager_id }} {{ $property->service_provider_id }}">{{$property->title}}({{$property->code}})</option>
                                   @empty
                                   <option value="">No Property Found</option>
                                   @endforelse
                                 </select>
                             </div>
                            </div>
                            @endif

                            <div class="col-md-12" id="user" @if ($current_user->role->user_type->slug =='super-admin') style="display:block" @else style="display:none" @endif>
                              <div class="form-group required">
                                <label for="user_id">Select User<span class="error">*</span></label>
                                 <select class="form-control " id="user_id" name="user_id" style="width: 100%;">
                                  <option value="">--Select User--</option>
                                   @forelse($userData as $data)
                                      <option value="{{$data->id}}" >{{$data->name}} ({{$data->role->role_name}})</option>
                                   @empty
                                   <option value="">No User Found</option>
                                   @endforelse
                                 </select>
                             </div>
                            </div>
                               
                        </div>
                          <div class="form-group required">
                            <label for="name">Subject<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):''}}" name="name" id="name"  placeholder="Please Enter Country Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="description">Description</label>
                           <textarea rows="5" class="form-control"  name="description" id="description"  placeholder="Description">
                           {{old('description')?old('description'):''}}</textarea>

                            @if($errors->has('description'))
                            <span class="text-danger">{{$errors->first('description')}}</span>
                            @endif
                          </div>
                          
                        </div>
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
    // console.log(userId);
    // $('#user_id').val(userId).trigger("change");
    getUser(userId);
  })
  
  $('#contract').on('change', function() {
    $('#user').show();
  })

  function getUser(user_id){
    console.log(user_id);
    // return false;

$.ajax({
  
   url: "{{route('admin.message.get-user')}}",
   type:'get',
   dataType: "json",
   data:{userId:user_id,_token:"{{ csrf_token() }}"}
  // data:{bank_id:bank_id}
   }).done(function(response) {
      
      console.log(response.status);
       if(response.status){
        // console.log(response.contractUser);
        var stringified = JSON.stringify(response.contractUser);
       var contractUserData = JSON.parse(stringified);
        var user_list = '<option value=""> Select User</option>';
        $.each(contractUserData,function(index, user_rec){
          console.log(user_rec);
               user_list += '<option value="'+user_rec.id+'">'+ user_rec.name + ' ('+user_rec.role.role_name+')</option>';
        });
           $("#user_id").html(user_list);
       }
   });
}


</script>
<!-- *********Used for CK Editor ***************-->
<script type="text/javascript" src="{{asset('js/admin/message/create.js')}}"></script>
@endpush
