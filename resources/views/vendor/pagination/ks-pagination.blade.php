@if ($paginator->hasPages())
<nav style="display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin-top:6px;">
    @php
        $pageName = $paginator->getPageName();
    @endphp

    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="ks-page-btn ks-page-btn-disabled">‹</span>
    @else
        <button
            type="button"
            wire:click="changeTablePage('{{ $pageName }}', {{ $paginator->currentPage() - 1 }})"
            wire:key="prev-{{ $pageName }}"
            class="ks-page-btn"
        >‹</button>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <span class="ks-page-btn ks-page-btn-disabled">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span wire:key="active-{{ $pageName }}-{{ $page }}" class="ks-page-btn ks-page-btn-active">{{ $page }}</span>
                @else
                    <button
                        type="button"
                        wire:click="changeTablePage('{{ $pageName }}', {{ $page }})"
                        wire:key="page-{{ $pageName }}-{{ $page }}"
                        class="ks-page-btn"
                    >{{ $page }}</button>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <button
            type="button"
            wire:click="changeTablePage('{{ $pageName }}', {{ $paginator->currentPage() + 1 }})"
            wire:key="next-{{ $pageName }}"
            class="ks-page-btn"
        >›</button>
    @else
        <span class="ks-page-btn ks-page-btn-disabled">›</span>
    @endif
</nav>
@endif
