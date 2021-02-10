<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ART CELL | {{ (isset($page_title))?$page_title:'' }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- *****************for multiselect with search option, select all and scrollbar *************-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery.multiselect.css')}}">
    <!-- *****************for multiselect with search option, select all and scrollbar End*************-->
    <!-- fullCalendar -->
      <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/main.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar-daygrid/main.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar-timegrid/main.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar-bootstrap/main.min.css')}}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
          href="{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('assets/plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
  
    <link rel="stylesheet" href="{{asset('assets/plugins/toastr/toastr.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote-bs4.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">

    <!--jquery-ui css datepicker (needed for datepicker)-->
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <!------>

    <link rel="stylesheet" href="{{asset('assets/plugins/fancybox/fancybox.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- <link rel="stylesheet" href="{{asset('assets/css/styles.css')}}"> -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/admin/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dist/css/bootstrap-clockpicker.min.css')}}">
    
    <!-- *****************for chosen multiselect with search option, select all and scrollbar End *************-->
    <link rel="stylesheet" href="{{asset('assets/dist/css/chosen-multi-select/docsupport/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dist/css/chosen-multi-select/chosen.css')}}">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js" type="text/javascript"></script>

    @php
    if(App::getLocale()=='en'){
        $lang_css_path=asset('css/admin/language_en.css');
    }else if(App::getLocale()=='ar'){
        $lang_css_path=asset('css/admin/language_ar.css');
    }
    @endphp
    <link rel="stylesheet" type="text/css" href="{{$lang_css_path}}">
   
</head>
<body class="hold-transition sidebar-mini layout-fixed text-sm lang_{{App::getLocale()}}">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>


        <!-- Right navbar links -->

        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              @php
              $notifications_count=Helper::notifications_count();
              @endphp
              @if($notifications_count>0)
              <span class="badge badge-danger navbar-badge">{{$notifications_count}}</span>
              @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">{{$notifications_count}} Unread Notifications</span>
              @forelse(Helper::latest_three_notifications() as $notification)
              <div class="dropdown-divider"></div>
              <a href="{{route('admin.notifications.details',$notification->id)}}" class="dropdown-item">
                <div class="media">
                  <div class="media-body">
                    <p class="text-sm">{{Str::limit($notification->message,100)}}</p>
                    <p class="text-sm text-muted float-right"><i class="far fa-clock mr-1"></i>{{$notification->created_at->diffForHumans()}}</p>
                  </div>
                </div>
              </a>
              @empty

              @endforelse
              <div class="dropdown-divider"></div>
              <a href="{{route('admin.notifications.list')}}" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
            </li>
            <!-- Notifications Dropdown Menu End -->
            <li class="nav-item">
              <select class="form-control" id="language" onchange="onLanguageChange(this.value)">
                <option value="en" {{(App::getLocale()=='en')?'selected':''}}>English</option>
                <option value="ar" {{(App::getLocale()=='ar')?'selected':''}}>Arabic</option>
              </select>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fa fa-caret-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                    <a href="{{route('admin.profile.change_password')}}" class="dropdown-item btn btn-succes btn-sms">
                        <span class="float-right text-muted text-sm">{{__('nav_link_text.change_password')}}</span>
                    </a>
                    <a href="{{route('admin.profile.edit_profile')}}" class="dropdown-item btn btn-succes btn-sms">
                        <span class="float-right text-muted text-sm">Edit Profile</span>
                    </a>
                    <a href="{{route('admin.logout')}}" class="dropdown-item btn btn-primary btn-sms">
                        <span class="float-right text-muted text-sm">{{__('nav_link_text.sign_out')}}</span>
                    </a>

                </div>
            </li>

           

            <!-- <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li> -->
        </ul>

    </nav>
