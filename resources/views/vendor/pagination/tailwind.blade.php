@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-3">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center rounded-md border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-400">
                    {{ __('Previous') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center rounded-md border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
                    {{ __('Previous') }}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center rounded-md border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
                    {{ __('Next') }}
                </a>
            @else
                <span class="inline-flex items-center rounded-md border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-400">
                    {{ __('Next') }}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <p class="text-sm text-zinc-600">
                {{ __('Showing') }}
                <span class="font-medium text-zinc-900">{{ $paginator->firstItem() }}</span>
                {{ __('to') }}
                <span class="font-medium text-zinc-900">{{ $paginator->lastItem() }}</span>
                {{ __('of') }}
                <span class="font-medium text-zinc-900">{{ $paginator->total() }}</span>
                {{ __('results') }}
            </p>

            <div>
                <span class="isolate inline-flex rounded-md shadow-sm">
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center rounded-l-md border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-400">
                            <span aria-hidden="true">&lsaquo;</span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center rounded-l-md border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50" aria-label="{{ __('Previous') }}">
                            <span aria-hidden="true">&lsaquo;</span>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="relative inline-flex items-center border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-500">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="relative inline-flex items-center border border-cyan-600 bg-cyan-600 px-4 py-2 text-sm font-semibold text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center rounded-r-md border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50" aria-label="{{ __('Next') }}">
                            <span aria-hidden="true">&rsaquo;</span>
                        </a>
                    @else
                        <span class="relative inline-flex items-center rounded-r-md border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-400">
                            <span aria-hidden="true">&rsaquo;</span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
