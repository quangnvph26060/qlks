@if ($response->hasPages())
    <!-- Pagination -->
    <div class="d-flex align-items-center justify-content-center">
        <div>
            <div class="text-center mb-2">
                <p class="small text-muted">
                    {!! __('Showing') !!}
                    <span class="fw-semibold">{{ $response->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="fw-semibold">{{ $response->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="fw-semibold total-records">{{ $response->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>
            <ul class="pagination text-center">
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

                @php
                    $currentPage = $response->currentPage();
                    $lastPage = $response->lastPage();
                @endphp

                {{-- Always show the first page --}}
                <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                    <a href="{{ $response->url(1) }}" class="page-link">1</a>
                </li>

                {{-- Show "..." if current page is greater than 3 --}}
                @if ($currentPage > 3)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif

                {{-- Display 2 pages before and after current page --}}
                @for ($i = max(2, $currentPage - 1); $i <= min($lastPage - 1, $currentPage + 1); $i++)
                    @if ($i != 1 && $i != $lastPage)
                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                            <a href="{{ $response->url($i) }}" class="page-link">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Show "..." if there are pages after current page but before last page --}}
                @if ($currentPage < $lastPage - 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif

                {{-- Always show the last page --}}
                @if ($lastPage > 1)
                    <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                        <a href="{{ $response->url($lastPage) }}" class="page-link">{{ $lastPage }}</a>
                    </li>
                @endif

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
    </div>
@endif
