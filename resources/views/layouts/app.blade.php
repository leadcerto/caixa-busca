<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $meta_title ?? $title ?? config('app.name') }}</title>
        <meta name="description" content="{{ $meta_description ?? config('app.name') }}">

        {{-- Open Graph (WhatsApp, Facebook, Telegram) --}}
        <meta property="og:type"        content="website">
        <meta property="og:site_name"   content="{{ config('app.name') }}">
        <meta property="og:title"       content="{{ $meta_title ?? $title ?? config('app.name') }}">
        <meta property="og:description" content="{{ $meta_description ?? config('app.name') }}">
        <meta property="og:url"         content="{{ url()->current() }}">
        @isset($og_image)
        <meta property="og:image"       content="{{ $og_image }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        @endisset

        {{-- Twitter Card --}}
        <meta name="twitter:card"        content="summary_large_image">
        <meta name="twitter:title"       content="{{ $meta_title ?? $title ?? config('app.name') }}">
        <meta name="twitter:description" content="{{ $meta_description ?? config('app.name') }}">
        @isset($og_image)
        <meta name="twitter:image"       content="{{ $og_image }}">
        @endisset

        {{-- Canonical URL — evita conteúdo duplicado (paginação, query strings, etc.) --}}
        <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

        @stack('preload')
        @stack('schema')

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body>
        <main>
            {{ $slot }}
        </main>

        @livewireScripts
    </body>
</html>
