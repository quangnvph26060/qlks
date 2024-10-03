@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table head--base table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>@lang('Check In') - @lang('Check Out')</th>
                    <th>@lang('Số lượng phòng')</th>
                    <th>@lang('Thuế')</th>
                    <th>@lang('Total')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookingRequests as $bookingRequest)
                    <tr>
                        <td>
                            <h6> {{ $loop->iteration }}.</h6>
                        </td>

                        <td>
                            <p> {{ showDateTime($bookingRequest->check_in, 'd M, Y') }}</p>
                            <p> {{ showDateTime($bookingRequest->check_out, 'd M, Y') }}</p>
                        </td>
                        <td>
                            {{ $bookingRequest->bookingItems->count() }}</td>
                        <td>
                            {{ showAmount($bookingRequest->bookingItems->sum('tax_charge')) }}</td>

                        <td class="fw-bold">
                            <p> {{ showAmount($bookingRequest->total_amount) }}</p>
                            <small class="text-muted">Đã bao gồm thuế</small>
                        </td>

                        <td>@php echo $bookingRequest->statusBadge;@endphp</td>

                        <td>
                            <button data-resource="{{ $bookingRequest->bookingItems }}"
                                class="btn btn-sm btn-outline--primary detail-booking-request"><i class="las la-desktop"></i>
                            </button>
                            <button @disabled($bookingRequest->status) class="btn btn-sm btn-outline--danger confirmationBtn"
                                data-action="{{ route('user.booking.request.cancel', $bookingRequest->id) }}"
                                data-question="@lang('Bạn có chắc chắn hủy yêu cầu này không?')"><i class="las la-times-circle"></i>
                                @lang('Cancel')</button>
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


    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog madal-dialog-centered modal-lg modal-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdrop">Danh sách phòng đã yêu cầu đặt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive--md">
                        <table class="custom--table head--base table">
                            <thead>
                                <tr>
                                    <th>@lang('Số phòng')</th>
                                    <th>@lang('Giá phòng')</th>
                                    <th>@lang('Thuế')</th>
                                    <th>@lang('Trạng thái')</th>
                                    <th>@lang('Hành động')</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        #staticBackdrop {
            z-index: 1050 !important;
        }

        .modal-backdrop {
            z-index: 1049 !important;
        }

        #confirmationModal {
            z-index: 1060 !important;
        }

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
        $(document).on('click', '.detail-booking-request', function() {
            appendToTable($(this).data('resource'));
            $('#staticBackdrop').modal('show');
        })

        function formatPrice(amount) {
            const formattedAmount = amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return `${formattedAmount} VND`;
        }

        function appendToTable(data) {
            $('#tbody').empty();

            const html = data.map(element => {
                let statusBadge = '';
                let disabled = 'disabled';

                switch (element['status']) {
                    case 0:
                        statusBadge = '<span class="badge bg-warning">@lang('Đang chờ xử lý')</span>';
                        disabled = '';
                        break;
                    case 1:
                        statusBadge = '<span class="badge bg-success">@lang('Đã chấp nhận')</span>';
                        break;
                    default:
                        statusBadge = '<span class="badge bg-danger">@lang('Đã hủy')</span>';
                        break;
                }

                // Xây dựng URL từ route Laravel
                const actionUrl = `{{ route('user.booking.request-item.cancel', ':id') }}`.replace(':id', element[
                    'id']);
                return `
            <tr data-id="${element['id']}">
                <td>${element['room']['room_number']}</td>
                <td>${formatPrice(Number(element['unit_fare']))}</td>
                <td>${formatPrice(Number(element['tax_charge']))}</td>
                <td>${statusBadge}</td>
                <td>
                    <button ${disabled}
                    data-action="${actionUrl}"
                    data-question="@lang('Xác nhận yêu cầu hủy phòng?')"
                    class="btn btn-sm btn-outline--danger confirmationBtn"
                    >Hủy phòng
                    </button>
                </td>
            </tr>
        `;
            }).join('');

            $('#tbody').html(html);
        }
    </script>
@endpush
