<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('icons/logo.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css','resources/js/app.ts'])
    <x-inertia::head>
        <title>{{ config('app.name') }}</title>
    </x-inertia::head>
</head>
<body>
<x-inertia::app />
</body>
</html>
