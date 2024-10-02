@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table head--base table">
            <thead>
                <tr>
                    <th></th>
                    <th>@lang('Check In') - @lang('Check Out')</th>
                    <th>@lang('Số lượng phòng')</th>
                    <th>@lang('Total')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookingRequests as $bookingRequest)
                    <tr>
                        <td>
                            <button class="btn btn-link btn-toggle" type="button"
                                onclick=" toggleRepresentatives('{{ $bookingRequest->id }}', this)"></button>
                        </td>

                        <td>
                            {{ showDateTime($bookingRequest->check_in, 'd M, Y') }} -
                            {{ showDateTime($bookingRequest->check_out, 'd M, Y') }}</td>
                        <td>
                            {{ $bookingRequest->bookingItems->count() }}</td>

                        <td class="fw-bold">{{ showAmount($bookingRequest->total_amount) }}</span></td>

                        <td>@php echo $bookingRequest->statusBadge;@endphp</td>

                        <td>
                            <button @disabled($bookingRequest->status) class="btn btn-sm btn-outline--danger confirmationBtn"
                                data-action="{{ route('user.booking.request.cancel', $bookingRequest->id) }}"
                                data-question="@lang('Are you sure to cancel this request?')"><i class="las la-times-circle"></i>
                                @lang('Cancel')</button>
                        </td>
                    </tr>
                    <tr class="collapse" id="rep-{{ $bookingRequest->id }}">
                        <td colspan="8">
                            <div class="representatives-container">
                                <span class="representatives-label">Danh mục:</span>
                                <span class="representatives-list">
                                    <span class="badge bg-warning me-2 cursor-pointer">
                                        <small class="representative-name"> </small>
                                    </span>
                                </span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        @if ($bookingRequests->hasPages())
            <nav aria-label="Page navigation example">
                {{ paginateLinks($bookingRequests) }}
            </nav>
        @endif
    </div>
    <x-confirmation-modal />
@endsection

@push('style')
    <style>
        .custom--table tbody td:first-child,
        .custom--table thead th:first-child {
            text-align: center;
        }

        table .btn {
            padding: 1px 4px !important;
        }

        .btn-toggle {
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;

            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            text-align: center;
            line-height: 1;
            border-radius: 50% !important;
            font-family: 'Courier New', Courier, monospace;
        }

        .btn-toggle:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-toggle.collapsed {
            background-color: red;
            border-color: red;
        }

        .btn-toggle::after {
            content: '+';
            display: inline-block;
        }

        .btn-toggle.collapsed::after {
            content: '−';
        }

        /* Hiệu ứng mở rộng và thu gọn */
        .collapse {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.5s ease, opacity 0.5s ease;
            opacity: 0;
        }

        .collapse.show {
            max-height: 200px;
            /* Điều chỉnh theo nhu cầu */
            opacity: 1;
        }

        .representatives-container {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            /* Border-bottom for separation */
            padding-bottom: 8px;
            /* Optional padding */
            margin-bottom: 8px;
            /* Optional margin */
        }

        .representatives-label {
            font-weight: bold;
            margin-right: 8px;
            /* Space between label and list */
        }

        .representatives-list {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
        }

        .representatives-list::after {
            content: '';
            /* Clear floats if needed */
            display: block;
            width: 100%;
        }
    </style>
@endpush

@push('script')
    <script>
        window.toggleRepresentatives = function(id, button) {
            const row = document.getElementById('rep-' + id);
            row.classList.toggle('show');
            button.classList.toggle('collapsed');
        };
    </script>
@endpush
