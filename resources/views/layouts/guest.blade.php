<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{$slot}}
        </div>
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
   function onSubmit(token) {
     document.getElementById("register-form").submit();
   }
 </script>
 <script>
     grecaptcha.ready(function() {
             grecaptcha.execute('6LcVwOcdAAAAAIgkXu6xND6r_4ZTXCXomFA-DlE6', {action: 'contact'}).then(function(token) {
                if (token) {
                  document.getElementById('recaptcha').value = token;
                }
             });
         });
         </script>
    </body>
</html>
