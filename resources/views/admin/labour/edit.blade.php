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
              <li class="breadcrumb-item"><a href="{{route('admin.labour.list')}}">Labour</a></li>
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
                <h3 class="card-title">Edit labour</h3>
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
                      <form  method="post" id="labour_edit_form" action="{{route('admin.labour.update',$user->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="first_name">First Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('first_name')?old('first_name'):$user->first_name}}" name="first_name" id="first_name"  placeholder="First Name">
                            @if($errors->has('first_name'))
                            <span class="text-danger">{{$errors->first('first_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="last_name">Last Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('last_name')?old('last_name'):$user->last_name}}" name="last_name" id="last_name"  placeholder="Last Name">
                            @if($errors->has('last_name'))
                            <span class="text-danger">{{$errors->first('last_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="email">Email <span class="error">*</span></label>
                            <input type="email" class="form-control" value="{{old('email')?old('email'):$user->email}}" name="email" id="email"  placeholder="Last Name">
                            @if($errors->has('email'))
                            <span class="text-danger">{{$errors->first('email')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="password">Password</label>
                            <input type="password" aria-describedby="passwordHelp" class="form-control" value="{{old('password')?old('password'):''}}" name="password" id="password"  placeholder="Password">
                            
                            <small id="passwordHelp" class="form-text text-muted">Leave blank if you do not want to update password.</small>
                            @if($errors->has('password'))
                            <span class="text-danger">{{$errors->first('password')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="phone">Phone/Contact Number <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('phone')?old('phone'):$user->phone}}" name="phone" id="phone"  placeholder="Phone/Contact Number">
                            @if($errors->has('phone'))
                            <span class="text-danger">{{$errors->first('phone')}}</span>
                            @endif
                          </div>
                          <div class="tab-pane active" id="english" role="tabpanel">
                            <div class="form-group required">
                              <label for="country_id">Country <span class="error">*</span></label>
                                <select class="form-control parent_role_select2" onchange='onCountryChange(this.value)' style="width: 100%;" name="country_id" id="country_id ">
                                    <option value="">Select a Country</option>
                                    @forelse($country_list as $country_data)
                                       <option value="{{$country_data->id}}" {{($user->country_id == $country_data->id)? 'selected':''}}>{{$country_data->name}}</option>
                                    @empty
                                   <option value="">No Country Found</option>
                                    @endforelse
                
                                </select>
                                @if($errors->has('country_id'))
                                <span class="text-danger">{{$errors->first('country_id')}}</span>
                                @endif
                            </div>

                            <div class="form-group required">
                                <label for="state_id">State <span class="error">*</span></label>
                                 <select name="state_id" id="state_id" class="form-control" onchange='onStateChange(this.value)'>
                                    <option value=""> Select State</option>
                                    @forelse($state_list as $state_data)
                                       <option value="{{$state_data->id}}" {{($user->state_id == $state_data->id)? 'selected':''}}>{{$state_data->name}}</option>
                                    @empty
                                    <option value="">No State Found</option>
                                    @endforelse
                                 </select>
                              @if($errors->has('state_id'))
                              <span class="text-danger">{{$errors->first('state_id')}}</span>
                              @endif
                            </div>

                            <div class="form-group required">
                                <label for="city_id">City <span class="error">*</span></label>
                                 <select name="city_id" id="city_id" class="form-control">
                                    <option value=""> Select City</option>
                                    @forelse($city_list as $city_data)
                                       <option value="{{$city_data->id}}" {{($user->country_id == $city_data->id)? 'selected':''}}>{{$city_data->name}}</option>
                                    @empty
                                    <option value="">No City Found</option>
                                    @endforelse
                                 </select>
                                @if($errors->has('city_id'))
                                <span class="text-danger">{{$errors->first('city_id')}}</span>
                                @endif
                            </div>
                          </div>
                          <?php //dd($user_skill_list); ?>
                          <div class="form-group required">
                            <label for="service_id">Skills </label>
                            <select class="form-control" multiple="multiple" searchable="Search for..."  name="skills[]" id="skills">  
                               
                               @forelse(@$skill_list as $key=> $skill_data)
                                     <option value="{{@$skill_data->id}}" @if(in_array(@$skill_data->id, @$user_skill_list)){{'selected'}}@endif>{{@$skill_data->skill_title}}</option>
                                @empty
                                <option value="">No Skill Found</option>
                                @endforelse     
                                                         
                              </select>
                            @if($errors->has('skills'))
                            <span class="text-danger">{{$errors->first('skills')}}</span>
                            @endif
                          </div>

                          <div class="form-group">
                            <label for="weekly_off">Select Weekly Off Day</label>
                             <select class="form-control " id="weekly_off" name="weekly_off" style="width: 100%;">
                               <option value="">Select Working Day</option>
                               <option {{old('weekly_off',$user->weekly_off)=="monday"? 'selected':''}} value="monday">Monday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="tuesday"? 'selected':''}} value="tuesday">Tuesday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="wednesday"? 'selected':''}} value="wednesday">Wednesday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="thursday"? 'selected':''}} value="thursday">Thursday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="friday"? 'selected':''}} value="friday">Friday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="saturday"? 'selected':''}} value="saturday">Saturday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="sunday"? 'selected':''}} value="sunday">Sunday</option>
                             </select>
                         </div>
                         
                          <!-- <div class="form-group">
                            <label for="start_time">Select a Start time:</label>
                            <input class="form-control" type="time" id="start_time" name="start_time" value="{{old('start_time')?old('start_time'):$user->start_time}}">
                          </div>
                          <div class="form-group">
                            <label for="end_time">Select a End time:</label>
                            <input class="form-control" type="time" id="end_time" name="end_time" value="{{old('end_time')?old('end_time'):$user->end_time}}">
                          </div> -->
                          
                        </div>
                        <!--  this the url for remote validattion rule for user email -->
                        <input type="hidden" id="ajax_check_user_email_unique" value="{{route('ajax.check_user_email_unique',$user->id)}}">
                        <div>
                           <a href="{{route('admin.labour.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
       
        url: "{{route('admin.labour.getStateList')}}",
        type:'get',
        dataType: "json",
        data:{country_id:country_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allStates);
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

    
    function onStateChange(state_id){
     $.ajax({
       
        url: "{{route('admin.labour.getCityList')}}",
        type:'get',
        dataType: "json",
        data:{state_id:state_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allCity);
             var stringified = JSON.stringify(response.allCity);
            var citydata = JSON.parse(stringified);
             var city_list = '<option value=""> Select City</option>';
             $.each(citydata,function(index, city_id){
                    city_list += '<option value="'+city_id.id+'">'+ city_id.name +'</option>';
             });
                $("#city_id").html(city_list);
            }
        });
    }

    $('#skills').multiselect({
    columns: 1,
    placeholder: 'Select Skill',
    search: true,
    selectAll: true
    });
</script>
<script type="text/javascript" src="{{asset('js/admin/labour/edit.js')}}"></script>
@endpush
