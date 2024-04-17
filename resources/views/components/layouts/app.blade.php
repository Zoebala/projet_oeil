<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{'bootstrap5/css/bootstrap.min.css'}}">
        <script src="{{'bootstrap5/js/bootstrap.min.js'}}"></script>

        <title>{{ $title ?? 'Page Title' }}</title>
      
    </head>
    <body>
        {{ $slot }}
    </body>
</html>
