<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @vite(['resources/js/app.js'])

        <!-- Include Bootstrap 5 CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-c/Sx8WkLvlPvSwOc92CpXa8dW1GgTZv+rkUzF6LdrdaZCXzJ6ST/i/P5ue+OLojH5BcARh/5lJL7r+hGKy+uZg==" crossorigin="anonymous" />
    </head>
    <body>
        @yield('content')

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-Rku7VY1juLVDj7E3yf0TrYZ6HLO87BJwzcUx61Ad1hBvU5eH2kY5rvd0MhdO/+26PbnlczbDrwFz8ZgWvZvzNw==" crossorigin="anonymous" defer></script>
    </body>
</html>
