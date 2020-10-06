@extends('admin.layouts.app', ['title' =>$panel_title])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $panel_title }}</h3>
                </div>

                <div class="box-header">
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
                    </div>

                {{ Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.state.addSubmit'],
                                    'name'  => 'addProjectTypeForm',
                                    'id'    => 'addProjectTypeForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Name">Name<span class="red_star">*</span></label>
                                    {{ Form::text('name', null, array(
                                                                'id' => 'name',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Name',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.state.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

@endsection