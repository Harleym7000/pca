<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
  
  $('#customFile').on('change',function(){
      //get the file name
      var fileName = $(this).val();
      //replace the "Choose a file" label
      $('.custom-file-label').html(fileName);
  });
});
</script>
</head>
<body>

<div id="app">
    <div class="container-fluid" style="text-align: left; color: #000;">
      <div class="row no-gutters">
        <div class="col-2">
          @include('inc.admin-sidenav')
        </div>
        <div class="col-10">
          @include('inc.admin-nav')
          <div id="policy">
            @include('partials.alerts')
          <h1>Current Policy Documents</h1>
          <div class="row">
        </div>
        @if(count($policies) > 0)
        <div class="row">
        @foreach($policies as $p)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
        <div class="card">
          <img class="card-img-top" src="img/pdf.png" alt="Card image cap">
          <div class="card-body">
            <h4 class="card-title">{{$p->name}}</h4>
            <form action="/policy/delete/{{$p->name}}" method="POST">
              @csrf
            <button type="submit" class="col-12 btn btn-danger" value="{{$p->name}}" name="deletefile">Delete</button>
            </form>
          </div>
        </div>
        </div>
        @endforeach
      </div>
        @else
          <p>There are currently no policy documents uploaded</p>
          @endif
          <br>
          <br>
          <h1>Upload New Policy Document</h1>
          <form action="/policy/upload" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
              <div class="col-10">
              <input type="file" class="custom-file-input" id="customFile" name="file">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
            <br>
            <div class="col-2">
              <button type="submit" class="btn btn-primary">Upload</button>
              </div>
          </div>
          </form>
        </div>
      </div>
    </div>
</div>
</div>
</body>
</html>
