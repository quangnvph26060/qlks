{{-- @if (!$warehouse->status)
        <div class="action-btn">
            <a href="{{ route('admin.return.create', $warehouse->id) }}" class="btn btn-sm btn-outline--danger"
                id="btn-return"><i class="las la-sync"></i>Trả hàng</a>
        </div>
    @elseif($firstReturn && $firstReturn->status)
        <a href="{{ route('admin.return.show', $warehouse->id) }}" class="btn btn-sm btn-outline--secondary"
            id="btn-return">Chi tiết sản phẩm bị hoàn trả</a>
    @endif --}}

<div class="row">
    <div class="col-lg-6 ">

        <input type="text" class="form-control text-uppercase" id="warehouse_code" placeholder="Nhập mã phiếu"
            name="warehouse_code" value="{{ $warehouse->reference_code }}"
            style="height: 40px; text-transform: uppercase;">
        <select class="form-select mt-1" id="supplierSelect" name="supplier_id" aria-label="Chọn nhà cung cấp">
            <option selected disabled>--- Chọn nhà cung cấp ---</option>
            @foreach ($suppliers as $id => $name)
                <option value="{{ $id }}" {{ $id == $warehouse->supplier_id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>

    </div>
    <div class="col-lg-6">

        @php
            use Carbon\Carbon;
        @endphp

        <input type="date" class="form-control" id="dateWarehouse" name="dateWarehouse"
            value="{{ $warehouse->created_time ? Carbon::parse($warehouse->created_time)->format('Y-m-d') : date('Y-m-d') }}"
            style="height:40px">
        <select class="form-select mt-1" id="paymentMethod" name="payment_method_id"
            aria-label="Chọn phương thức thanh toán">
            <option disabled {{ !$warehouse->payment_method_id ? 'selected' : '' }}>Chọn phương thức thanh toán
            </option>
            <option value="1" {{ $warehouse->payment_method_id == 1 ? 'selected' : '' }}>Thanh toán khi
                nhận hàng</option>
            <option value="2" {{ $warehouse->payment_method_id == 2 ? 'selected' : '' }}>Thanh toán chuyển
                khoản</option>
        </select>





    </div>
    <div class="col-12 mt-2">
        <textarea name="note" id="note" rows="2" placeholder="Ghi chú">{{ $warehouse->note }}</textarea>
        <button type="button" class="btn btn-sm btn--primary mt-1 openSlipDetailBtn" data-id="{{ $warehouse->id }}">
            Chi tiết phiếu
        </button>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        @php($sl = 0)
        @foreach ($warehouse->entries ?? [] as $item)
            @php($sl += $item->quantity - $item->number_of_cancellations)
        @endforeach
        <div class="row">
            <div class="col-12">
                {{-- <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title"></h5>
                    <div id="result-btn">
                        @if ($warehouse->status == 1)
                            <p class="badge badge--success">Hoàn thành</p>
                        @elseif($sl == 0)
                            <p class="badge badge--danger">Đã hủy</p>
                        @elseif($sl > 0)
                            <form action="{{ route('admin.warehouse.update', $warehouse->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline--primary btn-return">
                                    Xác nhận đơn hàng
                                </button>
                            </form>
                        @endif
                    </div>
                </div> --}}
                <div class="card-body">
                    <div class="d-flex fw-bold border-bottom pb-2 mb-2 justify-content-between"
                        style="padding-left: 4px;">
                        <div style="width: 150px; font-weight: bold;" class="responsive-text">Sản
                            phẩm
                        </div>
                        <div style="width: 130px;text-align: center; font-weight: bold;"class="responsive-text">
                            Số lượng</div>
                        <div
                            style="width: 100px; font-weight: bold;"class="responsive-text d-flex justify-content-center">
                            Giá
                        </div>
                        <div style="width: 130px; font-weight: bold;"class="responsive-text">Thành
                            tiền
                        </div>
                        <div style="width: 120px;text-align: center;  font-weight: bold;"class="responsive-text">
                            Kho</div>
                        <div style="width: 37px; font-weight: bold;"class="responsive-text">Xóa
                        </div>
                    </div>
                    <div id="selected-product-edit" style="height: 250px; overflow-y: auto;">
                        @foreach ($warehouse->entries as $item)
                            <div class="selected-product d-flex justify-content-between align-items-center mb-1"
                                data-id="{{ $item->id }}">
                                <div class="product-name me-3 flex-shrink-0 responsive-text" style="width: 150px; "
                                    data-id="{{ $item->product_id }}">
                                    {{ $item->product->name }}
                                </div>
                                <div class="quantity d-flex align-items-center me-3 flex-shrink-0"
                                    style="width: 130px;">
                                    {{-- <button type="button"
                                                class="btn btn-outline-secondary btn-sm decrease-edit">-</button> --}}
                                    <input type="text"
                                        class="form-control no-border mx-2 handled-focus-edit money-input responsive-text"
                                        name="products[{{ $item->product_id }}]" value="{{ $item->quantity }}"
                                        min="1"
                                        style="width: 100%; text-align: center; height: 30px !important; ">
                                    {{-- <button type="button"
                                                class="btn btn-outline-secondary btn-sm increase-edit">+</button> --}}
                                </div>
                                {{-- <div class="product-price text-success me-3 flex-shrink-0 price-edit"
                                            style="width: 100px;" data-price="{{ $item->price }}">
                                            {{ number_format($item->price, 0, ',', '.') }}
                                        </div> --}}
                                <div style="width: 130px;">

                                    <input type="text"
                                        class="form-control no-border text-success me-3 flex-shrink-0 price-edit money-input responsive-text"
                                        style="width: 100%;height: 30px !important; " data-price="{{ $item->price }}"
                                        value="{{ number_format($item->price, 0, ',', '.') }}">
                                </div>
                                <div class="product-price text-success me-3 flex-shrink-0 price-product responsive-text"
                                    style="width: 100px;font-size: 15px ">
                                    {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </div>
                                <select name="warehouses[{{ $item->product_id }}]"
                                    class="form-select form-select-sm me-3 flex-shrink-0 responsive-text"
                                    style="width: 120px; height: 30px !important; ">
                                    <option selected="" disabled="">Chọn kho</option>
                                    @foreach ($warehouses as $k)
                                        <option value="{{ $k->id }}"
                                            {{ $k->id == $item->warehouse_id ? 'selected' : '' }}>
                                            {{ $k->name }}</option>
                                    @endforeach

                                </select>
                                <button class="btn btn-outline-danger btn-sm remove-product-edit flex-shrink-0"
                                    data-id="{{ $item->id }}"
                                    data-url="{{ route('admin.warehouse.destroy.warehouse.item', $item->id) }}">X</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 d-flex justify-content-end align-items-end gap-3 flex-column">
        <div>
            <div class="fw-bold">Tổng cộng: <span class="total-price-edit text-success">
                    {{ number_format($warehouse->total, 0, ',', '.') }} VNĐ</span></div>
        </div>
        <button class="btn btn-primary confirmPayment-edit" type="submit" id="confirmPayment-edit"
            data-id="{{ $warehouse->id }}">Lưu</button>
    </div>
</div>

{{-- @php($firstReturn = optional($warehouse->returns)->first()) --}}
@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var warehouse = @json($warehouse);

                // Cập nhật tổng tiền khi người dùng nhập số trực tiếp
                $(document).on("blur", ".handled-focus-edit", function() {
                    const input = $(this);

                    // Xoá dấu chấm để lấy số gốc
                    let rawValue = input.val().replace(/\./g, '');

                    // Parse lại giá trị
                    let value = parseInt(rawValue) || 0;

                    // Nếu nhỏ hơn 1 thì gán lại là 1
                    if (value < 1) {
                        value = 1;
                    }

                    // Gán lại giá trị đã định dạng
                    input.val(value.toLocaleString('vi-VN'));

                    // Gọi cập nhật tổng
                    updateTotal();
                });


                document.addEventListener('input', function(e) {
                    if (e.target.classList.contains('money-input')) {
                        let value = e.target.value.replace(/\D/g, ""); // Xóa ký tự không phải số
                        value = Number(value).toLocaleString('vi-VN'); // Định dạng theo chuẩn Việt Nam
                        e.target.value = value;
                    }
                });

                // Hàm cập nhật tổng tiền
                function updateTotal() {
                    let total = 0;
                    const selectedProducts = $('#selected-product-edit .selected-product');
                    console.log(price);

                    selectedProducts.each(function() {
                        const price = parseFloat(
                            $(this).find('.price-edit').text().replace('Giá: ', '').replace(' VND',
                                '')
                            .replace(/\./g, '')
                        );



                        const quantity = parseInt($(this).find('input[type="text"]')).val().replace(
                            /\./g, '');
                        total += price * quantity;
                        // const priceProduct = price * quantity;
                        // $(this).find('.price-product').text(priceProduct.toLocaleString('vi-VN') + ' VND');
                    });

                    $('.total-price-edit').text(total.toLocaleString('vi-VN') + ' VND');


                    // Nếu không có sản phẩm nào, hiển thị thông báo
                    if (total === 0) {
                        $('#selected-product-edit').html(
                            '<p class="text-danger text-center">Vui lòng chọn sản phẩm <strong>*</strong></p>'
                        );
                    } else {
                        // Nếu có sản phẩm, không hiển thị thông báo
                        if (selectedProducts.length > 0) {
                            $('#selected-product-edit').find('p.text-danger.text-center')
                                .remove(); // Xóa thông báo nếu có sản phẩm
                        }
                    }
                }

                // Tăng hoặc giảm số lượng sản phẩm
                // $(document).on("click", ".increase-edit", function() {
                //     const wrapper = $(this).closest('.selected-product');
                //     const input = wrapper.find('input[type=number]');
                //     let value = parseInt(input.val()) || 0;
                //     input.val(value + 1);
                //     updateTotal();
                // });

                // $(document).on("click", ".decrease-edit", function() {
                //     const wrapper = $(this).closest('.selected-product');
                //     const input = wrapper.find('input[type=number]');
                //     let value = parseInt(input.val()) || 0;
                //     if (value > 1) input.val(value - 1);
                //     updateTotal();
                // });




                // Xóa sản phẩm khỏi danh sách
                $(document).on("click", ".remove-product-edit", function() {
                    $(this).closest('.selected-product').remove(); // Xóa sản phẩm khỏi DOM
                    updateTotal(); // Cập nhật tổng tiền
                });
                $("#complete").on("click", function() {
                    Swal.fire({
                        title: 'Bạn chắc chắn chứ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.warehouse.update', $warehouse->id) }}",
                                type: "PUT",
                                success: function(response) {
                                    $(".action-btn").empty();
                                    $("#result-btn").empty().append(`
                                        <p class="badge badge--success">Hoàn thành</p>
                                        `);
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal
                                                .stopTimer;
                                            toast.onmouseleave = Swal
                                                .resumeTimer;
                                        },
                                        customClass: {
                                            container: 'custom-toast' // Áp dụng lớp CSS tùy chỉnh
                                        }
                                    });
                                    Toast.fire({
                                        icon: "success",
                                        title: `<p>${response.message}</p>`,
                                    });
                                }
                            })
                        }
                    })
                })

                // $('.entry').on('change', function() {
                //     if ($('.entry:checked').length > 0) {
                //         $('.form-horizontal').attr('action',
                //             '{{ route('admin.return.create', $warehouse->id) }}');
                //         $('.btn-return').prop('disabled', false);
                //     } else {
                //         $('.form-horizontal').attr('action', '#');
                //         $('.btn-return').prop('disabled', true);
                //     }

                // });
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

    <style scoped>
        @media (max-width: 992px) {
            .mt-md-3 {
                margin-top: 1.5rem !important;
            }
        }

        .table-bordered tr td {
            text-align: left
        }

        #ellipsis {
            max-width: 250px;
            /* Chiều rộng tối đa của phần tử */
            white-space: nowrap;
            /* Không cho văn bản xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản bị tràn */
            text-overflow: ellipsis;/
        }
    </style>
    <style scoped>
        /* Modal auto-scaling */
        .modal-dialog {
            max-width: min(1000px, 95vw);
            height: min(95vh, 800px);
            margin: clamp(10px, 2.5vh, 1.75rem) auto;
        }

        .modal-content {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            flex-shrink: 0;
            padding: clamp(8px, 2vh, 1rem) clamp(12px, 3vw, 1.5rem);
        }

        .modal-title {
            font-size: clamp(14px, 2.5vh, 1.25rem);
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: clamp(8px, 2vh, 1.5rem);
        }

        /* Form elements responsive */
        .form-control,
        .form-select {
            height: clamp(28px, 4vh, 40px) !important;
            font-size: clamp(10px, 1.6vh, 14px) !important;
            /* padding: clamp(3px, 0.8vh, 8px) clamp(6px, 1.2vh, 12px) !important; */
        }

        textarea.form-control {
            height: clamp(35px, 6vh, 60px) !important;
            resize: none;
        }

        /* Card responsive */
        .card {
            margin-bottom: clamp(6px, 1.2vh, 1rem);
        }

        .card-body {
            padding: clamp(6px, 1.2vh, 1rem);
        }

        .card-header {
            padding: clamp(4px, 0.8vh, 0.75rem) clamp(6px, 1.2vh, 1rem);
        }

        .card-title {
            font-size: clamp(12px, 2vh, 16px);
            margin-bottom: clamp(4px, 0.8vh, 0.5rem);
        }

        /* Badge responsive */
        .badge {
            font-size: clamp(9px, 1.4vh, 12px) !important;
            padding: clamp(2px, 0.4vh, 4px) clamp(4px, 0.8vh, 8px) !important;
        }

        /* Button responsive */
        .btn {
            padding: clamp(3px, 0.6vh, 6px) clamp(8px, 1.6vh, 12px);
            font-size: clamp(10px, 1.6vh, 14px);
        }

        .btn-sm {
            padding: clamp(2px, 0.4vh, 4px) clamp(6px, 1.2vh, 8px);
            font-size: clamp(9px, 1.4vh, 12px);
        }

        /* Product header responsive */
        .product-header {
            padding: clamp(3px, 0.6vh, 8px) clamp(2px, 0.4vh, 4px);
            margin-bottom: clamp(3px, 0.6vh, 8px);
        }

        .product-header>div {
            font-size: clamp(9px, 1.4vh, 13px) !important;
            font-weight: bold !important;
            line-height: clamp(11px, 1.8vh, 16px);
        }

        /* Product items responsive */
        .selected-product {
            padding: clamp(3px, 0.6vh, 8px) clamp(2px, 0.4vh, 4px);
            border-bottom: 1px solid #e9ecef;
        }

        .selected-product .product-name {
            font-size: clamp(9px, 1.4vh, 13px) !important;
            line-height: clamp(11px, 1.8vh, 16px);
        }

        .selected-product input[type="text"] {
            height: clamp(24px, 3.5vh, 30px) !important;
            font-size: clamp(9px, 1.4vh, 13px) !important;
            text-align: center;
            padding: clamp(2px, 0.4vh, 4px) !important;
        }

        .selected-product .product-price {
            font-size: clamp(9px, 1.4vh, 13px) !important;
            line-height: clamp(11px, 1.8vh, 16px);
        }

        .selected-product select {
            height: clamp(24px, 3.5vh, 30px) !important;
            font-size: clamp(9px, 1.4vh, 13px) !important;
            padding: clamp(2px, 0.4vh, 4px) clamp(4px, 0.8vh, 8px) !important;
        }

        /* Product list area responsive */
       

        /* Column widths responsive */
        .col-product {
            width: clamp(100px, 18vw, 150px) !important;
        }

        .col-quantity {
            width: clamp(80px, 15vw, 130px) !important;
        }

        .col-price {
            width: clamp(70px, 12vw, 100px) !important;
        }

        .col-total {
            width: clamp(80px, 15vw, 130px) !important;
        }

        .col-warehouse {
            width: clamp(80px, 14vw, 120px) !important;
        }

        .col-delete {
            width: clamp(25px, 5vw, 37px) !important;
        }

        /* Total section responsive */
        .total-section {
            margin-top: clamp(6px, 1.2vh, 1rem);
            padding-top: clamp(6px, 1.2vh, 1rem);
            border-top: 1px solid #e9ecef;
        }

        .total-price-edit {
            font-size: clamp(12px, 2vh, 18px) !important;
            font-weight: 600;
        }

        /* Gap responsive */
        .gap-3 {
            gap: clamp(6px, 1.2vh, 1rem) !important;
        }

        /* Margin/Padding responsive */
        .mt-1 {
            margin-top: clamp(2px, 0.4vh, 0.25rem) !important;
        }

        .mt-3 {
            margin-top: clamp(6px, 1.2vh, 1rem) !important;
        }

        .mb-2 {
            margin-bottom: clamp(3px, 0.6vh, 0.5rem) !important;
        }

        .pb-2 {
            padding-bottom: clamp(3px, 0.6vh, 0.5rem) !important;
        }

        .me-3 {
            margin-right: clamp(6px, 1.2vh, 1rem) !important;
        }

        /* Very small screens */
        @media (max-height: 600px) {
            .modal-dialog {
                height: 98vh;
                margin: 1vh auto;
            }

            .modal-body {
                padding: 6px;
            }

            .card-body {
                padding: 4px;
            }

            #selected-product-edit {
                height: 80px !important;
            }

            .form-control,
            .form-select {
                height: 24px !important;
                font-size: 9px !important;
            }

            textarea.form-control {
                height: 30px !important;
            }

            .selected-product input[type="text"],
            .selected-product select {
                height: 20px !important;
                font-size: 8px !important;
            }
        }

        @media (max-height: 500px) {
            .modal-dialog {
                height: 99vh;
                margin: 0.5vh auto;
            }

            .modal-header {
                padding: 4px 8px;
            }

            .modal-body {
                padding: 4px;
            }

            #selected-product-edit {
                height: 60px !important;
            }

            .card {
                margin-bottom: 3px;
            }

            .product-header>div {
                font-size: 8px !important;
            }

            .selected-product .product-name,
            .selected-product .product-price {
                font-size: 8px !important;
            }
        }

        /* Landscape mobile */
        @media (max-height: 450px) and (orientation: landscape) {
            .row .col-lg-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            #selected-product-edit {
                height: 50px !important;
            }

            .product-header>div {
                font-size: 7px !important;
            }
        }

        /* Extra responsive adjustments */
        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 98vw;
            }

            .col-lg-6 {
                margin-bottom: clamp(4px, 0.8vh, 8px);
            }
        }

        /* Custom scrollbar for product list */
        #selected-product-edit::-webkit-scrollbar {
            width: clamp(3px, 0.6vh, 6px);
        }

        #selected-product-edit::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #selected-product-edit::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        #selected-product-edit::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endpush
