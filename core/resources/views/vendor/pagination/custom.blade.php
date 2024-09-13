@if ($response->hasPages())
    <!-- Amount -->
    <div class="d-flex align-items-center justify-content-between d--block">
        <span class="main__response-pages text--center" style="font-size: 12px">
            Showing {{ $response->firstItem() }} to {{ $response->lastItem() }} of <span
                class="total-records">{{ $response->total() }}</span> results
        </span>

        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($response->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <a href="#" class="page-link">‹</a>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $response->previousPageUrl() }}" class="page-link">‹</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $currentPage = $response->currentPage();
                $lastPage = $response->lastPage();
            @endphp

            {{-- Always show the first 2 pages --}}
            @for ($i = 1; $i <= 2; $i++)
                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a href="{{ $response->url($i) }}" class="page-link">{{ $i }}</a>
                </li>
            @endfor

            {{-- Show middle pages --}}
            @if ($currentPage > 4)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            {{-- Display 2 pages before and after current page --}}
            @for ($i = max(3, $currentPage - 1); $i <= min($lastPage - 2, $currentPage + 1); $i++)
                @if ($i > 2 && $i < $lastPage - 1)
                    {{-- Only show middle pages --}}
                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                        <a href="{{ $response->url($i) }}" class="page-link">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            @if ($currentPage < $lastPage - 3)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            {{-- Always show the last 2 pages --}}
            @for ($i = $lastPage - 1; $i <= $lastPage; $i++)
                @if ($i > 2)
                    {{-- Only show last pages if necessary --}}
                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                        <a href="{{ $response->url($i) }}" class="page-link">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- Next Page Link --}}
            @if ($response->hasMorePages())
                <li class="page-item">
                    <a href="{{ $response->nextPageUrl() }}" class="page-link">›</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <a href="#" class="page-link">›</a>
                </li>
            @endif
        </ul>
    </div>
@endif
