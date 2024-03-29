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
    $('#toggle-sidenav').on('click', function(){
      $('.sidenav-holder').toggle();
      $('.content-holder').toggleClass('col-lg-10').toggleClass('col-lg-12');
    });
  });
    </script>
</head>
<body>

<div id="app">
    <div class="container-fluid" style="text-align: left; color: #000;">
      <div class="row no-gutters">
        <div class="sidenav-holder col-12 col-lg-2">
          @include('inc.admin-sidenav')
        </div>
        <div class="content-holder col-12 col-lg-10">
          @include('inc.admin-nav')
          <div id="policy">
            @include('partials.alerts')
          <h1>Current PCA Policy Documents</h1>
          <div class="row">
        </div>
        @if(count($policies) > 0)
        <div class="card-deck">
        @foreach($policies as $p)
        <div class="col-12 col-md-6 col-xl-3 mb-4">
        <div class="card">
          <img class="card-img-top" src="/img/833px-PDF_file_icon.svg.png" alt="Card image cap">
          <div class="card-body">
            <h4 class="card-title">{{$p->name}}</h4>
            <form action="/policy/download/{{$p->name}}" method="POST">
              @csrf
            <input type="hidden" value="{{$p->name}}">
            <button type="submit" class="col-12 btn btn-primary">Download</button>
          </form>
          </div>
        </div>
        </div>
        @endforeach
      </div>
        @else
          <p>There are currently no policy documents uploaded</p>
          @endif
        </div>
      </div>
    </div>
</div>
</div>
</body>
</html>
