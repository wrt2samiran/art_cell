
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $page_title }}</title>

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet"
          href="{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
   @php
    if(App::getLocale()=='en'){
        $lang_css_path=asset('css/admin/language_en.css');
    }else if(App::getLocale()=='ar'){
        $lang_css_path=asset('css/admin/language_ar.css');
    }
    @endphp
    <link rel="stylesheet" type="text/css" href="{{$lang_css_path}}">
</head>
<body class="hold-transition">

@yield('content')

<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js')  }}"></script>
<script src="{{asset('assets//plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Jquery form-validate -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script src="{{asset('js/jquery.validate.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/development-admin.js')}}"></script>
<script type="text/javascript">
  window.baseUrl="{{URL::to('/')}}";
  //this function will call when language will change from header language dropdown
function onLanguageChange(lang){
  window.location.href=baseUrl+'/language/'+lang;
}
</script>

@stack('custom-scripts')
</body>
</html>