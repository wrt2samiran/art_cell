@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>City Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.cities.list')}}">City</a></li>
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
                <h3 class="card-title">Create City</h3>
              </div>
              <div class="card-body">
              @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-dismissable alert-{{ $msg }}">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <span>{{ Session::get('alert-' . $msg) }}</span><br/>
                            </div>
                        @endif
                    @endforeach

                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            @foreach ($errors->all() as $error)
                                <span>{{ $error }}</span><br/>
                            @endforeach
                        </div>
                    @endif
                  <div class="row justify-content-center">
                    <div class="col-md-10 col-sm-12">
                    <form  method="post" id="admin_city_add_form" action="{{route('admin.cities.add')}}" method="post" enctype="multipart/form-data">  
                      @csrf
                        <div class="tab-content tab-validate pt-3">
                          <div class="tab-pane active" id="english" role="tabpanel">
                            <div class="form-group required">
                            <label for="country_id">Country <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" onchange='onCountryChange(this.value)' style="width: 100%;" name="country_id" id="country_id">
                                <option value="">Select a Country</option>
                                @forelse($country_list as $country_data)
                                   <option value="{{$country_data->id}}" {{(old('country_id')== $country_data->id)? 'selected':''}}>{{$country_data->name}}</option>
                                @empty
                               <option value="">No Country Found</option>
                                @endforelse
            
                              </select>
                            @if($errors->has('country_id'))
                            <span class="text-danger">{{$errors->first('country_id')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="country_id">State <span class="error">*</span></label>
                             <select name="state_id" id="state_id" class="form-control">
                                    <option value=""> Select State</option>
                                   
                             </select>
                            @if($errors->has('state_id'))
                            <span class="text-danger">{{$errors->first('state_id')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="name">City Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):''}}" name="name" id="name"  placeholder="Please Enter Country Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                          </div>
                      </div>
                  
                        </div>
                        <div>
                           <a href="{{route('admin.cities.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript">

  function onCountryChange(country_id){
     $.ajax({
       
        url: "{{route('admin.cities.getStates')}}",
        type:'post',
        dataType: "json",
        data:{country_id:country_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allState);
             var stringified = JSON.stringify(response.allStates);
            var statedata = JSON.parse(stringified);
             var state_list = '<option value=""> Select State</option>';
             $.each(statedata,function(index, state_id){
                    state_list += '<option value="'+state_id.id+'">'+ state_id.name +'</option>';
             });
                $("#state_id").html(state_list);
            }
        });
    }

</script>

<script type="text/javascript" src="{{asset('js/admin/city/create.js')}}">

  
</script>
@endpush
