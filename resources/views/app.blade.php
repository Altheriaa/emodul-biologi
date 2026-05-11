<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/Logo Pals.png') }}">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>