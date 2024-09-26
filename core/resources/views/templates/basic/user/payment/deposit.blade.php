@extends($activeTemplate . 'layouts.master')
@section('content')
    <style>
        .payment-system-list {
            max-height: 400px;
            /* Giới hạn chiều cao để tạo thanh cuộn nếu danh sách quá dài */
            overflow-y: auto;
            /* Tạo thanh cuộn dọc nếu chiều cao vượt quá giới hạn */
            border: 1px solid #dee2e6;
            /* Thêm đường viền xám nhẹ */
            padding: 10px;
            /* Thêm khoảng cách bên trong */
            background-color: #ffffff;
            /* Màu nền trắng */
            border-radius: 5px;
            /* Bo tròn các góc */
        }

        .gateway-option {
            display: flex;
            /* Hiển thị theo hàng ngang */
            align-items: center;
            /* Căn giữa theo chiều dọc */
            padding: 10px;
            /* Khoảng cách bên trong */
            margin-bottom: 10px;
            /* Khoảng cách bên dưới mỗi item */
            border: 1px solid #dee2e6;
            /* Đường viền bao quanh */
            border-radius: 5px;
            /* Bo tròn các góc */
            cursor: pointer;
            /* Đổi con trỏ chuột khi hover */
            transition: background-color 0.3s ease;
            /* Hiệu ứng khi hover */
        }

        .gateway-option:hover {
            background-color: #f1f1f1;
            /* Màu nền khi hover */
        }

        .gateway-option__info {
            flex: 1;
            /* Chiếm hết không gian còn lại */
            display: flex;
            /* Hiển thị theo hàng ngang */
            align-items: center;
            /* Căn giữa theo chiều dọc */
        }

        .gateway-option__name {
            font-size: 16px;
            /* Kích thước chữ */
            font-weight: 600;
            /* Độ đậm chữ */
            color: #343a40;
            /* Màu chữ */
            margin-left: 10px;
            /* Khoảng cách bên trái của tên */
        }

        .payment-item__thumb {
            width: 50px;
            /* Chiều rộng ảnh */
            height: 50px;
            /* Chiều cao ảnh */
            overflow: hidden;
            /* Ẩn phần thừa của ảnh */
            border-radius: 5px;
            /* Bo tròn góc ảnh */
            margin-right: 15px;
            /* Khoảng cách bên phải của ảnh */
        }

        .payment-item__thumb img {
            width: 100%;
            /* Chiều rộng đầy đủ */
            height: auto;
            /* Chiều cao tự động theo tỷ lệ */
        }

        .payment-item__btn {
            width: 100%;
            /* Chiều rộng đầy đủ */
            text-align: center;
            /* Căn giữa chữ */
            padding: 10px;
            /* Khoảng cách bên trong */
            background-color: #f8f9fa;
            /* Màu nền */
            border: 1px solid #dee2e6;
            /* Đường viền */
            border-radius: 5px;
            /* Bo tròn góc */
            cursor: pointer;
            /* Đổi con trỏ chuột khi hover */
            transition: background-color 0.3s ease;
            /* Hiệu ứng khi hover */
        }

        .payment-item__btn:hover {
            background-color: #e9ecef;
            /* Màu nền khi hover */
        }

        /* .booked_infor {
                                                            background-color: #f8f9fa;
                                                            border: 1px solid #dee2e6;
                                                            padding: 15px;
                                                            border-radius: 5px;
                                                            margin-bottom: 20px;
                                                        } */

        .booked_infor h4 {
            font-size: 18px;
            /* Kích thước chữ tiêu đề */
            font-weight: 600;
            /* Độ đậm chữ tiêu đề */
            margin-bottom: 10px;
            /* Khoảng cách bên dưới tiêu đề */
            color: #343a40;
            /* Màu chữ tiêu đề */
        }

        .booked_infor p {
            margin-bottom: 8px;
            /* Khoảng cách bên dưới các đoạn văn */
            color: #495057;
            /* Màu chữ */
        }

        .booked_infor img {
            width: 100%;
            /* Chiều rộng đầy đủ */
            height: auto;
            /* Chiều cao tự động theo tỷ lệ */
            border-radius: 5px;
            /* Bo tròn góc ảnh */
            margin-bottom: 15px;
            /* Khoảng cách bên dưới ảnh */
        }
    </style>
    <div class="container ">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('user.deposit.insert') }}" method="post" class="deposit-form">
                    @csrf
                    <input type="hidden" name="currency">
                    <div class="gateway-card">
                        <div class="row justify-content-center gy-sm-4 gy-3">
                            <div class="col-lg-6">
                                <div class="payment-system-list is-scrollable gateway-option-list">
                                    {{-- {{$booking}} --}}
                                    <div class="booked_infor">
                                        <h4>Thông tin phòng</h4>
                                        <img src="{{ asset($booking->bookedRooms[0]->roomType->main_image) }}"
                                            alt="ảnh phòng">
                                        <p>Mã đặt phòng: {{ $booking->bookedRooms[0]->booking->booking_number }}</p>
                                        <p>Loại phòng: {{ $booking->bookedRooms[0]->roomType->name }}</p>
                                        <p>Phòng số: {{ $booking->bookedRooms[0]->room->room_number }}</p>
                                        <p>Ngày:
                                            {{ Carbon\Carbon::parse($booking->bookedRooms[0]->booking->check_in)->format('d/m/Y') }}
                                            -
                                            {{ Carbon\Carbon::parse($booking->bookedRooms[0]->booking->check_out)->format('d/m/Y') }}
                                        </p>
                                        <h4>Thông tin người đặt</h4>
                                        <p>Tên người đặt:
                                            {{ $booking->bookedRooms[0]->booking->user->lastname }}
                                            {{ $booking->bookedRooms[0]->booking->user->firstname }}
                                        </p>
                                        <p>Email: {{ $booking->bookedRooms[0]->booking->user->email }}</p>
                                        <p>Số điện thoại: {{ $booking->bookedRooms[0]->booking->user->mobile }}</p>
                                        <p>Địa chỉ: {{ $booking->bookedRooms[0]->booking->user->address }},
                                            {{ $booking->bookedRooms[0]->booking->user->city }},
                                            {{ $booking->bookedRooms[0]->booking->user->state }}</p>
                                        <p>Quốc gia:
                                            {{ $booking->bookedRooms[0]->booking->user->country_name }}({{ $booking->bookedRooms[0]->booking->user->country_code }})
                                        </p>
                                    </div>
                                    @foreach ($gatewayCurrency as $data)
                                        <label for="{{ titleToKey($data->name) }}"
                                            class="payment-item @if ($loop->index > 4) d-none @endif gateway-option">
                                            <div class="payment-item__info">
                                                <span class="payment-item__check"></span>
                                                <span class="payment-item__name">{{ __($data->name) }}</span>
                                            </div>
                                            <div class="payment-item__thumb">
                                                <img class="payment-item__thumb-img"
                                                    src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}"
                                                    alt="@lang('payment-thumb')">
                                            </div>
                                            <input class="payment-item__radio gateway-input"
                                                id="{{ titleToKey($data->name) }}" hidden
                                                data-gateway='@json($data)' type="radio" name="gateway"
                                                value="{{ $data->method_code }}"
                                                @if (old('gateway')) @checked(old('gateway') == $data->method_code)
                                            @else @checked($loop->first) @endif
                                                data-min-amount="{{ showAmount($data->min_amount) }}"
                                                data-max-amount="{{ showAmount($data->max_amount) }}">
                                        </label>
                                    @endforeach
                                    @if ($gatewayCurrency->count() > 4)
                                        <button type="button" class="payment-item__btn more-gateway-option">
                                            <p class="payment-item__btn-text">@lang('Show All Payment Options')</p>
                                            <span class="payment-item__btn__icon"><i class="fas fa-chevron-down"></i></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="payment-system-list p-3">
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text mb-0">@lang('Tổng')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text">
                                                <span>{{ number_format($booking->bookedRooms[0]->booking->booking_fare) }}</span>
                                                {{ __(gs('cur_text')) }}
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text has-icon"> @lang('Thuế')
                                                <span></span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span
                                                    class="gateway-limit">{{ number_format($booking->bookedRooms[0]->booking->tax_charge) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    {{-- <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text has-icon">@lang('Phí xử lý')
                                                <span data-bs-toggle="tooltip" title="@lang('Processing charge for payment gateways')"
                                                    class="proccessing-fee-info"><i class="las la-info-circle"></i> </span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span class="processing-fee">@lang('0.00')</span>
                                                {{ __(gs('cur_text')) }}
                                            </p>
                                        </div>
                                    </div> --}}

                                    <div class="deposit-info total-amount pt-3">
                                        <div class="deposit-info__title">
                                            <p class="text">@lang('Tổng tiền')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span
                                                    class="final-amount">{{ number_format($booking->bookedRooms[0]->booking->booking_fare + $booking->bookedRooms[0]->booking->tax_charge) }}</span>
                                                {{ __(gs('cur_text')) }}</p>
                                        </div>
                                    </div>

                                    <div class="deposit-info gateway-conversion d-none total-amount pt-2">
                                        <div class="deposit-info__title">
                                            <p class="text">@lang('Chuyển đổi')
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"></p>
                                        </div>
                                    </div>
                                    <div class="deposit-info conversion-currency d-none total-amount pt-2">
                                        <div class="deposit-info__title">
                                            <p class="text">
                                                @lang('In') <span class="gateway-currency"></span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text">
                                                <span class="in-currency"></span>
                                            </p>

                                        </div>
                                    </div>
                                    <div class="d-none crypto-message mb-3">
                                        @lang('Chuyển đổi với') <span class="gateway-currency"></span> @lang('và giá trị cuối cùng sẽ hiển thị ở bước tiếp theo')
                                    </div>
                                    <!-- Checkbox Đặt cọc -->
                                    <div class="deposit-info deposit-option mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input deposit-checkbox"
                                                id="deposit-checkbox" value="{{ $deposit }}">
                                            <label class="form-check-label" for="deposit-checkbox">
                                                Đặt cọc ({{ $deposit }}%):
                                            </label>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text">
                                                @php
                                                    $deposit_money =
                                                        (($booking->bookedRooms[0]->booking->booking_fare +
                                                            $booking->bookedRooms[0]->booking->tax_charge) *
                                                            $deposit) /
                                                        100;
                                                @endphp
                                                <span class="deposit-amount">{{ number_format($deposit_money) }}</span>
                                                {{ __(gs('cur_text')) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="transaction-method mb-3"><select name="transaction" id="transaction"
                                            class="form-select">
                                            <option value="">Phương thức thanh toán</option>
                                            @foreach ($methods as $method)
                                                <option value="{{ $method->id }}"
                                                    {{ request('transaction') == $method->id ? 'selected' : '' }}>
                                                    {{ $method->name }}</option>
                                            @endforeach
                                        </select></div>
                                    <!-- Kết thúc phần Checkbox -->
                                    <button id="confirm-payment-btn" class="btn btn--base w-100" disabled>
                                        @lang('Xác nhận thanh toán')
                                    </button>
                                    <div class="info-text pt-3">
                                        <p class="text">@lang('Đảm bảo tiền của bạn tăng trưởng an toàn thông qua quy trình gửi tiền an toàn với các tùy chọn thanh toán đẳng cấp thế giới.')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Thông tin thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="qr-code-section text-center">
                        <!-- QR code -->
                        <img id="qrCodeImage"
                            src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg"
                            alt="Mã QR" class="img-fluid mb-3">
                    </div>
                    <div class="payment-info">
                        <p><strong>Nội dung chuyển khoản: </strong>{{ $booking->bookedRooms[0]->booking->user->lastname }}
                            +
                            {{ $booking->bookedRooms[0]->booking->user->firstname }} đặt phòng<span
                                id="paymentContent"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.querySelector('#transaction').addEventListener('change', function() {
            // Lấy phương thức thanh toán đã chọn
            const paymentMethod = this.value;
            const paymentMethodText = this.options[this.selectedIndex].text.toLowerCase();

            // Điều kiện kiểm tra nếu phương thức thanh toán có chữ "tiền mặt" hoặc không chọn gì (value rỗng)
            if (!paymentMethod || paymentMethodText.includes('tiền mặt')) {
                // Nếu không chọn phương thức hoặc có từ "tiền mặt" thì disable nút
                $('#confirm-payment-btn').attr('disabled', true);
            } else {
                // Kích hoạt nút khi chọn phương thức hợp lệ
                $('#confirm-payment-btn').attr('disabled', false);
            }
        });

        // Khi người dùng nhấn nút xác nhận thanh toán
        document.querySelector('.btn--base').addEventListener('click', function(event) {
            // Lấy phương thức thanh toán được chọn
            const paymentMethod = document.querySelector('#transaction').value;
            const paymentMethodText = document.querySelector('#transaction option:checked').text.toLowerCase();

            // Kiểm tra ô checkbox Đặt cọc
            const isDepositChecked = document.querySelector('#deposit-checkbox').checked;

            // Lấy thông tin số tiền
            const totalAmount = parseFloat(document.querySelector('.final-amount').innerText.replace(/,/g, ''));
            const depositAmount = parseFloat(document.querySelector('.deposit-amount').innerText.replace(/,/g, ''));

            // Nếu không có phương thức thanh toán nào được chọn (value rỗng) hoặc có chữ "tiền mặt"
            if (!paymentMethod || paymentMethodText.includes('tiền mặt')) {
                event.preventDefault(); // Ngăn việc submit form
                return;
            }

            // Cập nhật nội dung modal
            const paymentContent = document.querySelector('#paymentContent');
            const paymentAmount = document.querySelector('#paymentAmount');

            // Tính số tiền dựa trên checkbox Đặt cọc
            const finalAmount = isDepositChecked ? depositAmount : totalAmount;

            // Cập nhật số tiền và nội dung chuyển khoản
            paymentContent.innerText =
                `${document.querySelector('.deposit-info p.text').innerText} + thanh toán đặt phòng`;
            paymentAmount.innerText = finalAmount.toLocaleString();

            // Hiển thị modal
            $('#paymentModal').modal('show');

            // Ngăn submit form để chờ người dùng xác nhận trong modal
            event.preventDefault();
        });
    </script>
    <script>
        "use strict";
        (function($) {

            var amount = parseFloat($('.amount').val() || 0);
            var gateway, minAmount, maxAmount;


            $('.amount').on('input', function(e) {
                amount = parseFloat($(this).val());
                if (!amount) {
                    amount = 0;
                }
                calculation();
            });

            $('.gateway-input').on('change', function(e) {
                gatewayChange();
            });

            function gatewayChange() {
                let gatewayElement = $('.gateway-input:checked');
                let methodCode = gatewayElement.val();

                gateway = gatewayElement.data('gateway');
                minAmount = gatewayElement.data('min-amount');
                maxAmount = gatewayElement.data('max-amount');


                let processingFeeInfo =
                    `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`
                $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);
                calculation();
            }

            gatewayChange();

            $(".more-gateway-option").on("click", function(e) {
                let paymentList = $(".gateway-option-list");
                paymentList.find(".gateway-option").removeClass("d-none");
                $(this).addClass('d-none');
                paymentList.animate({
                    scrollTop: (paymentList.height() - 60)
                }, 'slow');
            });

            function calculation() {
                if (!gateway) return;
                $(".gateway-limit").text(minAmount + " - " + maxAmount);

                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge);
                    fixedCharge = parseFloat(gateway.fixed_charge);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));
                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                    $(".deposit-form button[type=submit]").attr('disabled', true);
                } else {
                    $(".deposit-form button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {
                    $('.deposit-form').addClass('adjust-height')

                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.deposit-info__input .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ?
                        8 : 2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.deposit-form').removeClass('adjust-height')
                }

                if (gateway.method.crypto == 1) {
                    $('.crypto-message').removeClass('d-none');
                } else {
                    $('.crypto-message').addClass('d-none');
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            $('.gateway-input').change();
        })(jQuery);
    </script>
@endpush
