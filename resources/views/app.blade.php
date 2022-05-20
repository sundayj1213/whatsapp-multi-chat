<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes

        <script src="https://cdn.socket.io/4.0.1/socket.io.min.js"
            integrity="sha384-LzhRnpGmQP+lOvWruF/lgkcqD+WDVt9fU3H4BWmwP5u5LTmkUGafMcpZKNObVMLU"
            crossorigin="anonymous"></script>
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="{{ asset('js/bundle.js') }}"></script>
    </body>
</html>
