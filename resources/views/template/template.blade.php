<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- THIS IS FOR TOKEN -->
        <meta name="csrf-token" content="{{csrf_token()}}">
        <script>Window.Laravel = {csrfToken: '{{csrf_token()}}'}</script>
        <!-- THIS IS FOR TOKEN -->
        <title>@yield('title')</title>
        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="{{asset('../bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('../bootstrap/css/custom-modal.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('../bootstrap/css/font-awesome/all.min.css')}}">

        <style type="text/css">
            html, body{
                height: 100vh;
                margin: 0; 
            }
            .hideObj{
                display: none;
            }
        </style>
    </head>
    <body>
		@yield('content')
        <script type="text/javascript" src="{{asset('../bootstrap/js/jquery-3.4.1.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('../bootstrap/js/bootstrap.min.js')}}"></script>
        <!-- INCLUDE THE VUE JS FILE -->
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>
