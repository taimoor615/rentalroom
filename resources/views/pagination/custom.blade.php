@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">
                        <i class="fas fa-chevron-left me-1"></i>Previous
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <i class="fas fa-chevron-left me-1"></i>Previous
                    </a>
                </li>
            @endif

            {{-- Pagination Logic --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $start = max($current - 2, 1);
                $end = min($start + 4, $last);
                $start = max($end - 4, 1);
            @endphp

            {{-- First Page + Ellipsis --}}
            @if($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if($start > 2)
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last Page + Ellipsis --}}
            @if($end < $last)
                @if($end < $last - 1)
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">...</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        Next<i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">
                        Next<i class="fas fa-chevron-right ms-1"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    {{-- Results Info --}}
    <div class="d-flex justify-content-center mt-2">
        <small class="text-muted">
            @if ($paginator->firstItem())
                <span>Showing</span>
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                <span>to</span>
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                <span>of</span>
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                <span>results</span>
            @else
                {{ __('No results found') }}
            @endif
        </small>
    </div>
@endif
