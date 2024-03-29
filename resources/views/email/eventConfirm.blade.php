<!DOCTYPE html>
<html lang="en">
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
</head>
<body>
    <div class="template" style="width: 70%; margin:auto; background-color: #e4eeed; font-family:sans-serif; padding:5%;">
        <h1 style="color: #000;">Hello!</h1>
    <p style="font-size: 16px; font-weight: bold; color: #000;">{{$body}}</p>
    <br>
    <br>
    <div class="verify-button" style="text-align: center; background-color: #fff; border: 2px solid #000;">
    <h3 style="font-size: 28px;">{{$token}}</h3>
</div>
    <br>
    <p style="font-size: 16px; font-weight: bold; color: #000">If you were not taken to the next page to enter the code, please try the link below</p>
    <a href="https://www.portstewartca.org/events/register/guest">https://www.portstewartca.org/events/register/guest</a>
    <br>
    <p style="font-size: 16px; font-weight: bold; color: #000">If you wish to cancel this event registration, please follow this link below:</p>
    <a href="https://www.portstewartca.org/event/register/cancel?id={{$cancelLink}}">Cancel Event Registration</a>
    <br>
    <p style="font-size: 16px; font-weight: bold; color: #000;">If this wasn't you, we apologise for the inconvenience. Please ignore this email. <br> Thanks, <br> PCA</p>
</div>
</body>
</html>