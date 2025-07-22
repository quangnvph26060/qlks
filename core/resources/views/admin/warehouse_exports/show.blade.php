<div class="row">
    {{-- @if (!$warehouse->status)
        <div class="action-btn">
            <a href="{{ route('admin.return.create', $warehouse->id) }}" class="btn btn-sm btn-outline--danger"
                id="btn-return"><i class="las la-sync"></i>Trả hàng</a>
        </div>
    @elseif($firstReturn && $firstReturn->status)
        <a href="{{ route('admin.return.show', $warehouse->id) }}" class="btn btn-sm btn-outline--secondary"
            id="btn-return">Chi tiết sản phẩm bị hoàn trả</a>
    @endif --}}

    <div class="col-lg-6 col-md-12">
        <div class="card">

            {{-- <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="w-25 text-left"><i class="fas fa-user me-2"></i> Tên nhà cung cấp</th>
                                <td>{{ $warehouse->supplier->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="text-left"><i class="fas fa-phone me-2"></i> Số điện thoại</th>
                                <td>{{ $warehouse->supplier->phone ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="text-left"><i class="fas fa-envelope me-2"></i> Email</th>
                                <td>{{ $warehouse->supplier->email ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="text-left"><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ</th>
                                <td>{{ $warehouse->supplier->address ?? '' }}</td>
                            </tr>
                        </thead>
                    </table>
                </div> --}}
            <div class="card-body">
                <input type="text" class="form-control text-uppercase" id="warehouse_code" placeholder="Nhập mã phiếu"
                    name="warehouse_code" value="{{ $warehouse->reference_code }}"
                    style="height: 40px; text-transform: uppercase;">
                <select class="form-select mt-1" id="supplierSelect" name="supplier_id" aria-label="Chọn nhà cung cấp">
                    <option selected disabled>--- Chọn nhà cung cấp ---</option>
                    @foreach ($suppliers as $id => $name)
                        <option value="{{ $id }}" {{ $id === $warehouse->supplier_id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                <textarea name="note" id="note" cols="10" rows="3" class="mt-1" placeholder="Ghi chú">{{ $warehouse->note }}</textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mt-xl-0 mt-3 col-md-12">
        <div class="card">


            {{-- <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="w-25 text-left"><i class="fas fa-receipt me-2"></i> Mã đơn hàng</th>
                                <td>{{ $warehouse->reference_code ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="text-left"><i class="fas fa-money-bill-wave me-2"></i> Tổng tiền</th>
                                <td>{{ showAmount($warehouse->total) ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="text-left"><i class="fas fa-calendar-alt me-2"></i> Ngày tạo</th>
                                <td>{{ \Carbon\Carbon::parse($warehouse->confirmation_date)->format('d/m/Y') ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="text-left"><i class="fas fa-credit-card me-2"></i> Phương thức thanh toán</th>
                                <td>Thanh toán khi nhận hàng</td>
                            </tr>
                          
                        </thead>
                    </table> --}}
            <div class="card-body">
                @php
                    use Carbon\Carbon;
                @endphp

                <input type="date" class="form-control" id="dateWarehouse" name="dateWarehouse"
                    value="{{ $warehouse->created_time ? Carbon::parse($warehouse->created_time)->format('Y-m-d') : date('Y-m-d') }}"
                    style="height:40px">


                {{-- <select class="form-select mb-3" id="employeeSelect" name="employee_id" aria-label="Chọn nhân viên">
                    <option selected disabled>Chọn nhân viên</option>
                    @foreach ($admin as $id => $name)
                        <option value="{{ $id }}" {{ $name->id == $warehouse->created_by ? 'selected' : '' }}>
                            {{ $name->name }}</option>
                    @endforeach
                </select> --}}
                <select class="form-select mt-1" id="paymentMethod" name="payment_method_id"
                    aria-label="Chọn phương thức thanh toán">
                    <option disabled {{ !$warehouse->payment_method_id ? 'selected' : '' }}>Chọn phương thức thanh toán
                    </option>
                    <option value="1" {{ $warehouse->payment_method_id == 1 ? 'selected' : '' }}>Thanh toán khi
                        nhận hàng</option>
                    <option value="2" {{ $warehouse->payment_method_id == 2 ? 'selected' : '' }}>Thanh toán chuyển
                        khoản</option>
                </select>

                <div class="payment-details mt-3 hidden" id="paymentDetails"></div>
            </div>

        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="card">
            @php($sl = 0)
            @foreach ($warehouse->entries ?? [] as $item)
                @php($sl += $item->quantity - $item->number_of_cancellations)
            @endforeach
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title"></h5>
                            <div id="result-btn">
                                @if ($warehouse->status == 1)
                                    <p class="badge badge--success">Hoàn thành</p>
                                @elseif($sl == 0)
                                    <p class="badge badge--danger">Đã hủy</p>
                                @elseif($sl > 0)
                                    <form action="{{ route('admin.warehouse.export.update', $warehouse->id) }}"
                                        method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline--primary btn-return">
                                            Xác nhận đơn hàng
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex fw-bold border-bottom pb-2 mb-2 justify-content-between"
                                style="padding-left: 4px;">
                                <div style="width: 150px;">Sản phẩm</div>
                                <div style="width: 130px;text-align: center">Số lượng</div>
                                <div style="width: 100px;">Giá</div>
                                <div style="width: 130px;">Thành tiền</div>
                                <div style="width: 120px;text-align: center">Kho</div>
                                <div style="width: 37px;">Xóa</div>
                            </div>
                            <div id="selected-product-edit" style="height: 250px; overflow-y: auto;">
                                @foreach ($warehouse->entries as $item)
                                    <div class="selected-product d-flex justify-content-between align-items-center mb-2 py-2 border-bottom"
                                        data-id="{{ $item->id }}">
                                        <div class="product-name fw-bold me-3 flex-shrink-0" style="width: 150px;"
                                            data-id="{{ $item->product_id }}">{{ $item->product->name }}
                                        </div>
                                        <div class="quantity d-flex align-items-center me-3 flex-shrink-0"
                                            style="width: 130px;">
                                            {{-- <button type="button"
                                                class="btn btn-outline-secondary btn-sm decrease-edit">-</button> --}}
                                            <input type="text" class="form-control mx-2 handled-focus-edit money-input"
                                                name="products[{{ $item->product_id }}]" value="{{ $item->quantity }}"
                                                min="1" max="{{ $item->product['stock'] }}"
                                                style="width: 117px; text-align: center; height: 30px;">
                                            {{-- <button type="button"
                                                class="btn btn-outline-secondary btn-sm increase-edit">+</button> --}}
                                        </div>
                                        <div class="product-price text-success me-3 flex-shrink-0 price-edit"
                                            style="width: 100px; white-space: nowrap;" data-price="{{ $item->price }}">
                                            {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                        <div class="product-price text-success me-3 flex-shrink-0 price-product"
                                            style="width: 100px; white-space: nowrap;">
                                            {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                        </div>
                                        <select name="warehouses[{{ $item->product_id }}]"
                                            class="form-select form-select-sm me-3 flex-shrink-0" style="width: 120px;">
                                            <option selected="" disabled="">Chọn kho</option>
                                            @foreach ($warehouses as $k)
                                                <option value="{{ $k->id }}"
                                                    {{ $k->id == $item->warehouse_id ? 'selected' : '' }}>
                                                    {{ $k->name }}</option>
                                            @endforeach

                                        </select>
                                        <button class="btn btn-outline-danger btn-sm remove-product-edit flex-shrink-0"
                                            style="width: 30px;" data-id="{{ $item->id }}"
                                            data-url="{{ route('admin.warehouse.export.destroy.warehouse.item', $item->id) }}">X</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-end align-items-end gap-3 flex-column">
            <div>
                <div class="fw-bold">Tổng cộng: <span class="total-price-edit text-success">
                        {{ number_format($warehouse->total, 0, ',', '.') }} VNĐ</span></div>
            </div>
            <button class="btn btn-primary confirmPayment-edit" type="submit" id="confirmPayment-edit"
                data-id="{{ $warehouse->id }}">Lưu</button>
        </div>
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
                     let rawValue = input.val().replace(/\./g, '');
                    let value = parseInt(rawValue) || 0;
                    const min = parseInt(input.attr('min')) || 1;
                    const max = parseInt(input.attr('max')) || Infinity;

                    // Đảm bảo giá trị không nhỏ hơn min
                    if (value < min || isNaN(value)) {
                        value = min;
                    }

                    // Đảm bảo giá trị không vượt quá max
                    if (value > max) {
                        value = max;
                    }

                    input.val(value);

                    // Gọi cập nhật tổng, nếu có
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



                        const quantity = parseInt($(this).find('input[type="text"]')).val().replace(/\./g, '');
                        total += price * quantity;
                    });

                    $('.total-price-edit').text(total.toLocaleString('vi-VN'));


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

    <style>
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
@endpush
