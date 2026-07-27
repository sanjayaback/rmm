@if ($paginator->hasPages())
<nav class="flex items-center justify-between mt-6">
    <div class="text-white/30 text-sm">
        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
    </div>
    <div class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-white/20 text-sm cursor-not-allowed">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-white/50 hover:text-orange-400 text-sm transition-colors border border-white/10 hover:border-orange-500/30 rounded-lg">←</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-white/30 text-sm">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3.5 py-2 bg-orange-500 text-white text-sm rounded-lg font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3.5 py-2 text-white/50 hover:text-orange-400 text-sm transition-colors border border-white/10 hover:border-orange-500/30 rounded-lg">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-white/50 hover:text-orange-400 text-sm transition-colors border border-white/10 hover:border-orange-500/30 rounded-lg">→</a>
        @else
            <span class="px-3 py-2 text-white/20 text-sm cursor-not-allowed">→</span>
        @endif
    </div>
</nav>
@endif
