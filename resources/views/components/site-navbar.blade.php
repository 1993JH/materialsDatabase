@props([
    'wrapperClass' => 'max-w-6xl',
    'homeLabel' => config('app.name', 'Wall-E'),
])

@php
    $linkBaseClasses = 'inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition';
    $inactiveLinkClasses = 'text-zinc-600 hover:bg-white/80 hover:text-zinc-900';
    $activeLinkClasses = 'bg-zinc-900 text-white';
@endphp

<header {{ $attributes->class(['mx-auto flex w-full items-center justify-between gap-4 px-6 py-6', $wrapperClass]) }}>
    <a href="{{ route('home') }}" class="text-sm font-semibold tracking-wide text-zinc-700 transition hover:text-zinc-950" wire:navigate>
        {{ $homeLabel }}
    </a>

    <nav class="flex flex-wrap items-center justify-end gap-3 text-sm font-medium">
        <a
            href="{{ route('home') }}"
            @class([
                $linkBaseClasses,
                $activeLinkClasses => request()->routeIs('home'),
                $inactiveLinkClasses => ! request()->routeIs('home'),
            ])
            wire:navigate
        >
            Home
        </a>

        <a
            href="{{ route('calculations') }}"
            @class([
                $linkBaseClasses,
                $activeLinkClasses => request()->routeIs('calculations'),
                $inactiveLinkClasses => ! request()->routeIs('calculations'),
            ])
            wire:navigate
        >
            Calculations
        </a>

        <a
            href="{{ route('about') }}"
            @class([
                $linkBaseClasses,
                $activeLinkClasses => request()->routeIs('about'),
                $inactiveLinkClasses => ! request()->routeIs('about'),
            ])
            wire:navigate
        >
            About us
        </a>

        @auth
            @can('access-admin')
                <a
                    href="{{ route('admin') }}"
                    @class([
                        $linkBaseClasses,
                        $activeLinkClasses => request()->routeIs('admin'),
                        $inactiveLinkClasses => ! request()->routeIs('admin'),
                    ])
                    wire:navigate
                >
                    Admin
                </a>
            @endcan

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="{{ $linkBaseClasses }} {{ $inactiveLinkClasses }}">
                    Log out
                </button>
            </form>
        @else
            <a
                href="{{ route('login') }}"
                @class([
                    $linkBaseClasses,
                    $activeLinkClasses => request()->routeIs('login'),
                    $inactiveLinkClasses => ! request()->routeIs('login'),
                ])
                wire:navigate
            >
                Log in
            </a>
        @endauth
    </nav>
</header>
