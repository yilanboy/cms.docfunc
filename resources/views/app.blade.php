<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('icons/logo.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css','resources/js/app.ts'])
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>
