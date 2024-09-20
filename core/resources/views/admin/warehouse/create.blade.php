@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-8 col-lg-12">
            <div class="card">
                <div class="card-header d-flex gap-2 align-items-center position-relative">
                    <input type="search" name="searchInput" class="form-control searchInput" placeholder="Tìm kiếm sản phẩm..."
                        autocomplete="off">
                    <select class="form-select categorySelect" aria-label="Chọn danh mục" style="width: auto;">
                        <option selected value="0">Chọn danh mục</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <div class="position-absolute w-100 start-0 bg-light text-dark p-2 rounded shadow"
                        style="top: 100%; left: 0; z-index: 100;">
                        <div class="search-results" id="search-results">

                            <!-- Thêm nhiều sản phẩm nếu cần -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 md-mt-5">
            <form action="" method="post" id="warehouseForm">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Tóm tắt sản phẩm đã chọn</h5>
                    </div>
                    <div class="card-body">
                        <div id="selected-product">
                            <p class="text-danger text-center">Vui lòng chọn sản phẩm <strong>*</strong></p>
                        </div>
                        <!-- Hàng tính tổng -->
                        <div class="total d-flex justify-content-between align-items-center pt-3">
                            <div class="fw-bold">Tổng cộng:</div>
                            <div class="total-price text-success">0 VND</div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Nhà cung cấp</h5>
                    </div>
                    <div class="card-body">
                        <select class="form-select" id="supplierSelect" name="supplier_id" aria-label="Chọn nhà cung cấp">
                            <option selected disabled>--- Chọn nhà cung cấp ---</option>
                            @foreach ($suppliers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <label for="paymentMethod" class="form-label">Phương thức thanh toán</label>
                        <select class="form-select" id="paymentMethod" name="payment_method_id"
                            aria-label="Chọn phương thức thanh toán">
                            <option selected disabled>Chọn phương thức thanh toán</option>
                            <option value="credit_card">Thẻ tín dụng</option>
                            <option value="paypal">PayPal</option>
                            <option value="2">Chuyển khoản ngân hàng</option>
                            <option value="1">Thanh toán khi nhận hàng</option>
                        </select>

                        <div class="payment-details mt-3 hidden" id="paymentDetails"></div>

                        <button class="btn btn-primary mt-4" type="submit" id="confirmPayment">Xác nhận thanh toán</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.product.index') }}"><i
                class="las la-list"></i>@lang('Danh sách nhập kho')</a>
    @endpush
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                let debounceTimer;

                let path_url = "http://qlks.test/storage/";

                $("#warehouseForm").on("submit", function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('admin.warehouse.store') }}",
                        type: "POST",
                        data: $(this).serializeArray(),
                        success: function(response) {
                            if (response.status) {
                                window.location.href =
                                    "{{ route('admin.warehouse.index') }}";
                            } else {
                                const firstKey = Object.keys(response.errors)[0];

                                const firstError = response.errors[firstKey];
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 20000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    },
                                    customClass: {
                                        container: 'custom-toast' // Áp dụng lớp CSS tùy chỉnh
                                    }
                                });
                                Toast.fire({
                                    icon: "error",
                                    title: `<p>${firstError}</p>`,
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                });

                $(document).on("click", ".result-item", function(e) {
                    e.preventDefault(); // Ngăn chặn hành động mặc định
                    e.stopPropagation(); // Ngăn chặn hành động mặc định
                    const product = $(this).data('resource'); // Lấy thông tin sản phẩm từ data-resource
                    const productId = product.id; // Lấy ID của sản phẩm


                    // Kiểm tra xem sản phẩm đã được chọn chưa
                    if ($('#selected-product').find(`[data-id="${productId}"]`).length > 0) {
                        alert('Sản phẩm này đã được chọn.');
                        return; // Dừng hàm nếu sản phẩm đã được chọn
                    }

                    // Tạo HTML cho sản phẩm đã chọn
                    const selectedProductHtml = `
                        <div class="selected-product d-flex align-items-center mb-3 pb-3 border-bottom" data-id="${productId}">
                            <div class="image me-2">
                                <img width="80" src="${path_url}${product.image_path}" alt="${product.name}">
                            </div>
                            <div class="info flex-grow-1">
                                <div class="name fw-bold ellipsis">${product.name}</div>
                                <div class="price text-success">Giá: ${Math.floor(product.import_price).toString()}</div>
                                <div class="quantity d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm decrease">-</button>
                                    <input type="number" class="form-control mx-2 handled-focus" name="products[${productId}]" value="1" min="1" style="width: 70px; text-align: center; height: 30px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm increase">+</button>
                                </div>
                            </div>
                            <button class="btn btn-outline-danger btn-sm ms-2 remove-product">X</button> <!-- Nút Xóa -->
                        </div>
                    `;

                    // Thêm sản phẩm vào tóm tắt
                    $('#selected-product').append(selectedProductHtml);

                    // Cập nhật tổng tiền
                    updateTotal();
                });

                function formattedNumber(number) {
                    return (number * 1.08).toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }) + ' VND';
                }


                // Cập nhật tổng tiền khi người dùng nhập số trực tiếp
                $(document).on("blur", ".handled-focus", function() {
                    const input = $(this);
                    let value = parseInt(input.val());
                    if (value < 1) {
                        input.val(1); // Đảm bảo giá trị tối thiểu là 1
                    }
                    updateTotal();
                });

                // Hàm cập nhật tổng tiền
                function updateTotal() {
                    let total = 0;
                    const selectedProducts = $('#selected-product .selected-product');

                    selectedProducts.each(function() {
                        const price = parseFloat($(this).find('.price').text().replace('Giá: ', '')
                            .replace(' VND', ''));
                        const quantity = parseInt($(this).find('input[type="number"]').val());
                        total += price * quantity;
                    });

                    $('.total-price').text(total + ' VND');

                    // Nếu không có sản phẩm nào, hiển thị thông báo
                    if (total === 0) {
                        $('#selected-product').html(
                            '<p class="text-danger text-center">Vui lòng chọn sản phẩm <strong>*</strong></p>'
                        );
                    } else {
                        // Nếu có sản phẩm, không hiển thị thông báo
                        if (selectedProducts.length > 0) {
                            $('#selected-product').find('p.text-danger.text-center')
                                .remove(); // Xóa thông báo nếu có sản phẩm
                        }
                    }
                }

                // Tăng hoặc giảm số lượng sản phẩm
                $(document).on("click", ".increase", function() {
                    const input = $(this).siblings('input');
                    let value = parseInt(input.val());
                    input.val(value + 1);
                    updateTotal();
                });

                $(document).on("click", ".decrease", function() {
                    const input = $(this).siblings('input');
                    let value = parseInt(input.val());
                    if (value > 1) {
                        input.val(value - 1);
                    }
                    updateTotal();
                });

                // Xóa sản phẩm khỏi danh sách
                $(document).on("click", ".remove-product", function() {
                    $(this).closest('.selected-product').remove(); // Xóa sản phẩm khỏi DOM
                    updateTotal(); // Cập nhật tổng tiền
                });

                function fetchData() {
                    const search = $(".searchInput").val();
                    const categoryId = $(".categorySelect").val(); // Lấy giá trị danh mục

                    $.ajax({
                        url: "{{ route('admin.product.filter') }}",
                        method: "GET",
                        data: {
                            search,
                            category_id: categoryId,
                        },
                        success: function(data) {
                            $("#search-results").html(data.results);
                            notData();
                        },
                    });
                }

                $(".categorySelect").on("change", function() {
                    if ($(".searchInput").val() !== "") {
                        fetchData();
                    }
                })

                $(".searchInput").on("input", function() {
                    clearTimeout(debounceTimer);
                    const searchValue = $(this).val();

                    if (searchValue !== "") {
                        debounceTimer = setTimeout(() => {
                            fetchData(); // Gọi fetchData nếu có giá trị tìm kiếm
                        }, 500);
                    } else {
                        $("#search-results").html("");
                    }

                });

                $('#paymentMethod').on('change', function() {
                    const paymentDetails = $('#paymentDetails');
                    paymentDetails.empty(); // Xóa thông tin cũ

                    if (this.value === "Chọn phương thức thanh toán") {
                        paymentDetails.removeClass('visible').addClass('hidden'); // Ẩn khi chưa chọn
                        return;
                    }

                    paymentDetails.removeClass('hidden').addClass('visible'); // Hiện phần chi tiết

                    if (this.value === 'credit_card') {
                        paymentDetails.html(`
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Số thẻ</label>
                        <input type="text" class="form-control" id="cardNumber" placeholder="Nhập số thẻ">
                    </div>
                    <div class="mb-3">
                        <label for="cardExpiry" class="form-label">Ngày hết hạn</label>
                        <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY">
                    </div>
                    <div class="mb-3">
                        <label for="cardCVC" class="form-label">Mã CVC</label>
                        <input type="text" class="form-control" id="cardCVC" placeholder="Nhập mã CVC">
                    </div>
                    `);
                    } else if (this.value === 'paypal') {
                        paymentDetails.html(`
                    <div class="mb-3">
                        <label for="paypalEmail" class="form-label">Email PayPal</label>
                        <input type="email" class="form-control" id="paypalEmail" placeholder="Nhập email PayPal">
                    </div>
                    `);
                    } else if (this.value === '2') {
                        paymentDetails.html(`
                    <div class="mb-3">
                        <label for="bankAccount" class="form-label">Số tài khoản ngân hàng</label>
                        <input type="text" class="form-control" id="bankAccount" placeholder="Nhập số tài khoản">
                    </div>
                    <div class="mb-3">
                        <label for="bankName" class="form-label">Tên ngân hàng</label>
                        <input type="text" class="form-control" id="bankName" placeholder="Nhập tên ngân hàng">
                    </div>
                    `);
                    } else if (this.value === '1') {
                        paymentDetails.html(`
                    <p>Vui lòng chuẩn bị tiền mặt khi nhận hàng.</p>
                    `);
                    }
                });

                $('#confirmPayment').on('click', function(event) {
                    if ($('#selected-product .selected-product').length === 0) {
                        event.preventDefault();
                        alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
                    } else {
                        alert('Đang xử lý thanh toán...');
                    }
                });

                function notData() {

                    // Check if there are no rows in the tbody
                    if ($(".result-item").length === 0) {
                        console.log("no data");

                        // Append the "No data" row
                        $("#search-results").append(
                            `<p id="no-data-row" class="text-center">Không tìm thấy dữ liệu!</p>`
                        );
                    } else {
                        console.log("has data");

                        // Remove the "No data" row if it exists
                        $("#no-data-row").remove();
                    }
                }

            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

    <style>
        @media (max-width: 1200px) {
            .md-mt-5 {
                margin-top: 2.5rem !important;
            }
        }

        .card {
            transition: transform 0.2s;
        }

        .payment-details {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 15px;
            background-color: #f8f9fa;
            transition: opacity 0.5s ease;
        }

        .hidden {
            display: none;
            /* Ẩn phần tử */
        }

        .visible {
            display: block;
            /* Hiện phần tử */
        }

        .form-label {
            margin-top: 15px;
        }

        .ellipsis {
            max-width: ;: 250px;
            /* Chiều rộng tối đa của phần tử */
            white-space: nowrap;
            /* Không cho văn bản xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản bị tràn */
            text-overflow: ellipsis;/
        }

        .search-results {
            max-height: 300px;
            overflow-y: auto;
        }

        .result-item {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            transition: background-color 0.3s;
        }

        .result-item:hover {
            background-color: #f8f9fa;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            /* Ẩn nút cho Firefox */
        }

        #ellipsis {
            max-width: 600px;
            /* Chiều rộng tối đa của phần tử */
            white-space: nowrap;
            /* Không cho văn bản xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản bị tràn */
            text-overflow: ellipsis;/
        }
    </style>
@endpush
