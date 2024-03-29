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
    <script src="https://cdn.tiny.cloud/1/9qolqe06kbfpdnul0hbz5re9tw7ajdmp5prm43f248wbh0nh/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
          selector: '#programme',
          mobile: {
            theme: 'mobile',
    maxWidth: 425,
  },
  width: 700,
          plugins: 'autoresize',
          autoresize_bottom_margin: 50
        });
      </script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script>
        $(document).ready(function() {
          $('#toggle-sidenav').on('click', function(){
            $('.sidenav-holder').toggle();
            $('.content-holder').toggleClass('col-lg-10').toggleClass('col-lg-12');
          });
        });
          </script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<div id="app">
    <div class="container-fluid" style="text-align: left; color: #000;">
      <div class="row no-gutters">
        <div class="sidenav-holder col-12 col-lg-2">
          @include('inc.admin-sidenav')
        </div>
        <div class="content-holder col-12 col-lg-10">
          @include('inc.admin-nav')
<div class="container">
    @include('partials.alerts')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="text-right mt-2">
            <a href="/events/red-sails">
                <button type="button" class="btn btn-secondary col-4 col-md-3 col-lg-2">Go Back</button>
            </a>
            </div>
            <div class="card mt-3">
                @foreach($getFestivalProgramme as $gfp)
                <div class="card-header">Edit Festival Programme - {{ \Carbon\Carbon::parse($gfp->festival_date)->format('D jS M Y')}}</div>
                @endforeach
                <div class="card-body">

                  <form action="/events/red-sails/programme/edit/{{$gfp->programme_id}}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label for="festivalDay" class="col-12 col-md-3 col-form-label text-md-right">Day of Festival: <span class="asterisk">*</span></label>

                        <div class="col-12 col-lg-8">
                            <select id="festivalDay" class="form-control @error('festivalDay') is-invalid @enderror" name="festivalDay" required>
                                @foreach($festivalDates as $date)
                                <option value="{{$date->format('Y-m-d')}}" @if($gfp->festival_date === $date->format('Y-m-d')) selected @endif>{{$date->format('D jS M, Y')}}</option>
                                @endforeach
                            </select>
                            @error('festivalDay')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="programme" class="col-12 col-md-3 col-form-label text-md-right">Programme: <span class="asterisk">*</span></label>

                    <div class="col-12 col-md-9">
                    <textarea id="programme" name="programme" cols="40" rows="20" class="@error('programme') is-invalid @enderror">
                        {{$gfp->programme}}
          </textarea>
                        @error('programme')
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                    </span>
    @enderror
</div>
</div>
                    <div class="text-right mt-md-5 mr-md-5">
                    <button type="submit" class="btn btn-primary col-12 col-md-4 col-lg-3">Update Programme</button>
                    </div>
                </div>
                </div>
            </div>
    </div>
</div>
