
  <!-- /.navbar -->
@extends('admin.layouts.after-login-layout')

@section('unique-content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{__('general_sentence.dashboard')}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">{{__('general_sentence.dashboard')}}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
      <div class="container-fluid">
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
      </div>
    </section>
   
    @yield('dashboard-content')

@endsection
