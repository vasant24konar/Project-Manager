@if ($paginator->hasPages())
<nav class="d-flex justify-content-center mt-4">
    <ul class="pagination mb-0" style="gap:4px;display:flex;flex-wrap:wrap;align-items:center;">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link" style="border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border:1px solid #dee2e6;color:#aaa;background:#fff;">
                    <i class="fa fa-chevron-left" style="font-size:.75rem;"></i>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   style="border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(134,184,23,.4);color:#86B817;background:#fff;">
                    <i class="fa fa-chevron-left" style="font-size:.75rem;"></i>
                </a>
            </li>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link" style="border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border:none;color:#aaa;background:transparent;">…</span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link" style="border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:#86B817;border:1px solid #86B817;color:#fff;font-weight:600;">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}"
                               style="border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(134,184,23,.3);color:#86B817;background:#fff;">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                   style="border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(134,184,23,.4);color:#86B817;background:#fff;">
                    <i class="fa fa-chevron-right" style="font-size:.75rem;"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link" style="border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border:1px solid #dee2e6;color:#aaa;background:#fff;">
                    <i class="fa fa-chevron-right" style="font-size:.75rem;"></i>
                </span>
            </li>
        @endif

    </ul>
</nav>
@endif
