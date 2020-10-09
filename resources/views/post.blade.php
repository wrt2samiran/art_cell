<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap 4 Website Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
  .fakeimg {
    height: 200px;
    background: #aaa;
  }
  input.error {
    border-color: #f00 !important;
  }
textarea.error{
  border-color: #f00 !important;
}

small.required {
    color:#f00;
}
  </style>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/b-1.6.4/datatables.min.css"/>
 
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/b-1.6.4/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0">

  <div class="form-group col-sm-4 offset-md-4">
  <label for="sel1">Select language</label>
  <select class="form-control" id="sel1" onchange="onChange(this.value)">
    <option value="en" {{(App::getLocale()=='en')?'selected':''}}>English</option>
    <option value="ar" {{(App::getLocale()=='ar')?'selected':''}}>Arabic</option>
  </select>
  </div>
  <div></div>
</div>

<!-- <div class="container" style="margin-top:30px">
  <div class="row">
    <form method="post" style="width: 100%" action="{{route('storePost')}}">
      @csrf
       <div class="col-sm-12">
      <ul class="nav nav-tabs">
         <li class="nav-item">
             <a class="nav-link bg-aqua-active" href="#" id="english-link">ENGLISH</a>
         </li>
         <li class="nav-item">
             <a class="nav-link" href="#" id="spanish-link">ARABIC</a>
         </li>
      </ul>
      <div class="card-body" id="english-form">
        <div class="form-group">
            <label class="required" for="en_title">Title (ENGLISH)</label>
            <input class="form-control {{ $errors->has('en_title') ? 'is-invalid' : '' }}" type="text" name="en_title" id="en_title" value="{{ old('en_title', '') }}" required>
            @if($errors->has('en_title'))
                <div class="invalid-feedback">
                    {{ $errors->first('en_title') }}
                </div>
            @endif
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <label for="en_description">Description (ENLISH)</label>
            <textarea class="form-control {{ $errors->has('en_description') ? 'is-invalid' : '' }}" name="en_description" id="en_description">{{ old('en_description') }}</textarea>
            @if($errors->has('en_description'))
                <div class="invalid-feedback">
                    {{ $errors->first('en_description') }}
                </div>
            @endif
            <span class="help-block"></span>
        </div>
      </div>

      <div class="card-body d-none" id="spanish-form">
          <div class="form-group">
              <label class="required" for="title">Title (ARABIC)</label>
              <input class="form-control {{ $errors->has('ar_title') ? 'is-invalid' : '' }}" type="text" name="ar_title" id="ar_title" value="{{ old('ar_title', '') }}" required>
              @if($errors->has('ar_title'))
                  <div class="invalid-feedback">
                      {{ $errors->first('ar_title') }}
                  </div>
              @endif
              <span class="help-block"></span>
          </div>
          <div class="form-group">
              <label for="ar_description">Description (ARABIC)</label>
              <textarea class="form-control {{ $errors->has('ar_description') ? 'is-invalid' : '' }}" name="ar_description" id="ar_description">{{ old('ar_description') }}</textarea>
              @if($errors->has('ar_description'))
                  <div class="invalid-feedback">
                      {{ $errors->first('ar_description') }}
                  </div>
              @endif
              <span class="help-block"></span>
          </div>
      </div>
      <div class="ml-4">
        English <input type="checkbox" id="post_in_english" name="post_in_english">
        Arabic  <input type="checkbox" id="post_in_arabic" name="post_in_arabic"><br>
        <button class="btn btn-success">Submit</button>
      </div>
    </div>
    </form>
   
  </div>
</div>
 -->
<div class="container">
  <div class="container" style="margin-top: 20px;">

    <div class="panel panel-primary">
        <div class="panel-heading">
       
        </div>
        <div class="panel-body">
            <form action="{{route('storePost')}}" method="post" class="form-horizontal" id="validate">


                <ul class="nav nav-tabs nav-justified nav-inline">
                   <li class="active"><a href="#secondary" data-toggle="tab">Arabic</a>&nbsp;&nbsp;&nbsp;</li>|
                    <li >&nbsp;&nbsp;&nbsp;<a href="#primary" data-toggle="tab">English  </a></li>
                   
                </ul>
                  @csrf
                <div class="tab-content tab-validate" style="margin-top:20px;">

                    <div class="tab-pane active" id="secondary">
                           <div class="form-group">
                            <label class="required" for="title">Title (ARABIC)</label>
                            <input class="form-control {{ $errors->has('ar_title') ? 'is-invalid' : '' }}" type="text" name="ar_title" id="ar_title" value="{{ old('ar_title', '') }}" >
                            @if($errors->has('ar_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ar_title') }}
                                </div>
                            @endif
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label for="ar_description">Description (ARABIC)</label>
                            <textarea class="form-control {{ $errors->has('ar_description') ? 'is-invalid' : '' }}" name="ar_description" id="ar_description">{{ old('ar_description') }}</textarea>
                            @if($errors->has('ar_description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ar_description') }}
                                </div>
                            @endif
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="tab-pane " id="primary">
                        <div class="form-group">
                            <label class="required" for="en_title">Title (ENGLISH)</label>
                            <input class="form-control {{ $errors->has('en_title') ? 'is-invalid' : '' }}" type="text" name="en_title" id="en_title" value="{{ old('en_title', '') }}" >
                            @if($errors->has('en_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('en_title') }}
                                </div>
                            @endif
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label for="en_description">Description (ENLISH)</label>
                            <textarea class="form-control {{ $errors->has('en_description') ? 'is-invalid' : '' }}" name="en_description" id="en_description">{{ old('en_description') }}</textarea>
                            @if($errors->has('en_description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('en_description') }}
                                </div>
                            @endif
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>

                <div>     Enter Post in -
        Arabic  <input type="checkbox" checked disabled id="post_in_arabic" name="post_in_arabic">
        English <input   type="checkbox" name="post_in_english" id="post_in_english" name="post_in_english"><br></div>
                <div class="form-group col-md-3">

                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>


</div>
<div class="container mb-5" style="margin-top:30px">
  <div class="row">
    @if(count($posts))
      @foreach($posts as $post)
    <div class="col-sm-6">

      
      <h3>{{$post->title}}</h3>
      <p>{{$post->description}}</p>
      <div class="fakeimg">Fake Image</div>
      <hr class="d-sm-none">
    </div>
      @endforeach
    @else
    @endif

  </div>
</div>

    <section class="content">

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header">
               <div class="d-flex justify-content-between" >
                 <div>Post List</div>
               </div>
              </div>
              <div class="card-body">             
              <table class="table table-bordered" id="post_table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                </table>
              </div>

            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
<div class="jumbotron text-center" style="margin-bottom:0">
  <p>Footer</p>
</div>
<script type="text/javascript">
 window.baseUrl="{{URL::to('/')}}";
 
 
 function onChange(lang){
   
   window.location.href=baseUrl+'/language/'+lang;
 }

   var $englishForm = $('#english-form');
   var $spanishForm = $('#spanish-form');
   var $englishLink = $('#english-link');
   var $spanishLink = $('#spanish-link');

   $englishLink.click(function() {
     $englishLink.toggleClass('bg-aqua-active');
     $englishForm.toggleClass('d-none');
     $spanishLink.toggleClass('bg-aqua-active');
     $spanishForm.toggleClass('d-none');
   });

   $spanishLink.click(function() {
     $englishLink.toggleClass('bg-aqua-active');
     $englishForm.toggleClass('d-none');
     $spanishLink.toggleClass('bg-aqua-active');
     $spanishForm.toggleClass('d-none');
   });

$(document).ready(function() {
      var post_table=$('#post_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: baseUrl+'/datatable-url',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
        ]

    });
});



$(function() {
    
    $('#validate').validate({
        ignore: [],
        errorPlacement: function() {},
        submitHandler: function(form) {
          form.submit();
            //alert('Successfully saved!');
        },
        invalidHandler: function() {
            setTimeout(function() {
                $('.nav-tabs a small.required').remove();
                var validatePane = $('.tab-content.tab-validate .tab-pane:has(input.error)').each(function() {
                    var id = $(this).attr('id');
                    $('.nav-tabs').find('a[href^="#' + id + '"]').append(' <small class="required">***</small>');
                });

                var validatePane = $('.tab-content.tab-validate .tab-pane:has(textarea.error)').each(function() {
                    var id = $(this).attr('id');
                    $('.nav-tabs').find('a[href^="#' + id + '"]').append(' <small class="required">***</small>');
                });
            });            
        },
        rules: {
            ar_title: 'required',
            ar_description: 'required',
        }
    });
    
});

if($("#post_in_english").prop('checked') == true){
    $("#en_title").rules("add", {
       required: true,
    });

    $("#en_description").rules("add", {
       required: true,
    });
}


$('#post_in_english').on('change',function(){
  if($("#post_in_english").prop('checked') == true){
    
    $("#en_title").rules("add", {
       required: true,
    });

    $("#en_description").rules("add", {
       required: true,
    });
  }else{
    $("#en_title").rules('remove', 'required');
    $("#en_description").rules('remove', 'required');
  }
});





</script>
</body>
</html>
