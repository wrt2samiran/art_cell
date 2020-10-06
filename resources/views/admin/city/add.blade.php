@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.city.list')}}"><i class="fa fa-city" aria-hidden="true"></i> City List</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                @include('admin.elements.notification')
                
                {{ Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.city.addSubmit'],
                                    'name'  => 'addCityForm',
                                    'id'    => 'addCityForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">State Name<span class="red_star">*</span></label>
                                    <select name="state_id" id="state_id" class="form-control" value="{{old('state_id')}}">
                                        <option value="">-Select-</option>
                                @if (count($stateList))
                                    @foreach ($stateList as $state)
                                        <option value="{{$state->id}}" @if($state->id == old('state_id') ) selected="selected" @endif>{{$state->name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">CityName<span class="red_star">*</span></label>
                                    {{ Form::text('name', null, array(
                                                                'id' => 'name',
                                                                'placeholder' => 'Name',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                            
                        </div>
                    </div>                        
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.city.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
@endsection