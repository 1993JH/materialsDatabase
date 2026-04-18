<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => 'Admin'])
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">
        <x-site-navbar />

        <livewire:admin-dashboard />

        @fluxScripts
    </body>
</html>

