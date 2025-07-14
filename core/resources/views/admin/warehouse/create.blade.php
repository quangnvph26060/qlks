{{-- <div class="row">
    <div class="col-xl-12 col-lg-12">
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
                    <div class="search-results" id="search-results"></div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<form action="" method="post" id="warehouseForm">
    {{-- Nhà cung cấp và thanh toán trên cùng hàng --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card h-100">

                <div class="card-body">
                    <input type="text" class="form-control text-uppercase" id="warehouse_code"
                        placeholder="Nhập mã phiếu" name="warehouse_code"
                        style="height: 40px; text-transform: uppercase;">
                    <select class="form-select mt-1" id="supplierSelect" name="supplier_id"
                        aria-label="Chọn nhà cung cấp">
                        <option selected disabled>--- Chọn nhà cung cấp ---</option>
                        @foreach ($suppliers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <textarea name="note" id="note" cols="10" rows="3" class="mt-1" placeholder="Ghi chú"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <input type="date" class="form-control" id="dateWarehouse" name="dateWarehouse"
                        value="{{ date('Y-m-d') }}" style="height:40px">
                    {{-- <select class="form-select mb-3" id="employeeSelect" name="employee_id" aria-label="Chọn nhân viên">
                        <option selected disabled>Chọn nhân viên</option>
                        @foreach ($admin as $id => $name)
                            <option value="{{ $id }}" {{$name->id ==  authAdmin()->id ? 'selected' : ""}}>{{ $name->name }}</option>
                        @endforeach
                    </select> --}}
                    <select class="form-select mt-1" id="paymentMethod" name="payment_method_id"
                        aria-label="Chọn phương thức thanh toán">
                        <option selected disabled>Chọn phương thức thanh toán</option>
                        <option value="1">Thanh toán khi nhận hàng</option>
                        <option value="2">Thanh toán chuyển khoản</option>
                    </select>

                    <div class="payment-details mt-3 hidden" id="paymentDetails"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- Sản phẩm đã chọn --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <p class="add-room-product" style="width: 185px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                    <path
                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12Zm10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16Z">
                                    </path>
                                    <path
                                        d="M13 7a1 1 0 1 0-2 0v4H7a1 1 0 1 0 0 2h4v4a1 1 0 1 0 2 0v-4h4a1 1 0 1 0 0-2h-4V7Z">
                                    </path>
                                </g>
                            </svg>
                            Chọn sản phẩm
                        </p>
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
                    <div id="selected-product-add" style="height: 250px; overflow-y: auto;">
                        <p class="text-danger text-center">Vui lòng chọn sản phẩm <strong>*</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tổng cộng + Xác nhận --}}
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-end align-items-end gap-3 flex-column">
            <div>
                <div class="fw-bold">Tổng tiền: <span class="total-price text-success"> 0 VND</span></div>
            </div>
            <button class="btn btn-primary" type="submit" id="confirmPayment">Lưu</button>
        </div>
    </div>
</form>
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 400px">
                <div class="table-responsive" style="max-height: 300px;
    overflow-y: auto;">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã sản phẩm</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Giá tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="result-item" data-resource="{{ $product }}">
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ number_format($product->import_price, 0, ',', '.') }} VND</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.add-room-product', function() {
                $('#productModal').modal('show');
            });
            var warehouse = @json($warehouse);
            $(document).ready(function() {
                let debounceTimer;

                let path_url = window.location.origin + '/storage/';

                let products = [];

                $('#confirmPayment').on('click', function(event) {
                    products = []; // reset trước khi push

                    if ($('#selected-product-add .selected-product').length === 0) {
                        event.preventDefault();
                        alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
                        return;
                    }

                    let valid = true;

                    $('#selected-product-add .selected-product').each(function() {
                        const productId = $(this).data('id');
                        const quantity = $(this).find('input[name^="products"]').val();
                        const warehouseId = $(this).find('select[name^="warehouses"]').val();
                        const price = $(this).find('.price').data('price');

                        if (!warehouseId || !quantity || quantity <= 0) {
                            valid = false;
                            return;
                        }

                        products.push({
                            product_id: productId,
                            quantity: parseInt(quantity),
                            warehouse_id: warehouseId,
                            price: parseFloat(price)
                        });
                    });

                    if (!valid) {
                        event.preventDefault();
                        alert('Vui lòng chọn kho và số lượng hợp lệ cho từng sản phẩm.');
                        return;
                    }

                    console.log("Danh sách sản phẩm đã chọn:", products);
                    // alert('Đang xử lý thanh toán...');
                    // $('#warehouseForm').submit(); // gọi submit form chính
                });
                $(document).on('change', '#dateWarehouse', function() {
                    const selectedDate = $(this).val();
                    console.log('Ngày đã chọn:', selectedDate);

                    // Thêm xử lý logic tại đây nếu cần
                });
                $(document).on('input', '#warehouse_code', function() {
                    const upperValue = $(this).val().toUpperCase();
                    $(this).val(upperValue); // cập nhật lại giá trị thật
                });
                $("#warehouseForm").on("submit", function(e) {
                    e.preventDefault();

                    let formData = new FormData();

                    // Thêm các trường cần thiết từ form
                    formData.append('supplier_id', $('#supplierSelect').val());
                    formData.append('employee_id', $('#employeeSelect').val());
                    formData.append('payment_method_id', $('#paymentMethod').val());
                    formData.append('note', $('#note').val());
                    formData.append('warehouse_code', $('#warehouse_code').val());
                    formData.append('date_warehouse', $('#dateWarehouse').val());

                    // Thêm danh sách sản phẩm
                    products.forEach((item, index) => {
                        formData.append(`products[${index}][product_id]`, item.product_id);
                        formData.append(`products[${index}][quantity]`, item.quantity);
                        formData.append(`products[${index}][warehouse_id]`, item.warehouse_id);
                        formData.append(`products[${index}][price]`, item.price);
                    });


                    $.ajax({
                        url: "{{ route('admin.warehouse.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
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
                                        container: 'custom-toast'
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
                    if ($('#selected-product-add').find(`[data-id="${productId}"]`).length > 0) {
                        alert('Sản phẩm này đã được chọn.');
                        return; // Dừng hàm nếu sản phẩm đã được chọn
                    }
                    let warehouseOptions = warehouse.map((w, index) =>
                        `<option value="${w.id}" ${index === 0 ? 'selected' : ''}>${w.name}</option>`
                    ).join('');
                    // Tạo HTML cho sản phẩm đã chọn
                    const selectedProductHtml = `
                            <div class="selected-product d-flex justify-content-between align-items-center mb-2 py-2 border-bottom" data-id="${productId}">
                                <div class="product-name fw-bold me-3 flex-shrink-0 text-truncate" style="width: 150px; max-width: 150px; overflow: hidden; white-space: nowrap;">
                                    ${product.name}
                                </div>

                                <div class="quantity d-flex align-items-center me-3 flex-shrink-0" style="width: 130px;">
                                    <input type="number" class="form-control mx-2 handled-focus" name="products[${productId}]"
                                        value="1" min="1"
                                        style="width: 80px; text-align: center; height: 30px;">
                                </div>

                                <div class="product-price text-success me-3 flex-shrink-0 price"
                                    style="width: 100px;" data-price="${product.import_price}">
                                    ${Number(product.import_price).toLocaleString('vi-VN')}
                                </div>

                                <div class="product-price text-success me-3 flex-shrink-0 price-product" style="width: 100px;">
                                    <!-- Có thể thêm tổng tiền ở đây nếu cần -->
                                </div>

                                <div class="me-3 flex-shrink-0" style="width: 120px;">
                                    <select name="warehouses[${productId}]" class="form-select form-select-sm">
                                        <option selected disabled>Chọn kho</option>
                                        ${warehouseOptions}
                                    </select>
                                </div>

                                <button class="btn btn-outline-danger btn-sm remove-product flex-shrink-0" style="width: 30px;">X</button>
                            </div>
                        `;


                    // Thêm sản phẩm vào tóm tắt
                    $('#selected-product-add').append(selectedProductHtml);

                    // Cập nhật tổng tiền
                    updateTotal();
                });

                function formattedNumber(number) {
                    return (number * 1.08).toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }) + ' VND';
                }


                $(document).on("blur", ".handled-focus", function() {
                    const input = $(this);
                    let value = parseInt(input.val());
                    input.val(value);
                    // Gọi cập nhật tổng, nếu có
                    updateTotal();
                });

                // Hàm cập nhật tổng tiền
                function updateTotal() {
                    let total = 0;
                    const selectedProducts = $('#selected-product-add .selected-product');

                    selectedProducts.each(function() {
                        const price = parseFloat(
                            $(this).find('.price').text().replace('Giá: ', '').replace(' VND', '')
                            .replace(/\./g, '')
                        );


                        const quantity = parseInt($(this).find('input[type="number"]').val());
                        total += price * quantity;
                        const priceProduct = price * quantity;
                        $(this).find('.price-product').text(priceProduct.toLocaleString('vi-VN') + '');
                    });

                    $('.total-price').text(total.toLocaleString('vi-VN') + ' VND');


                    // Nếu không có sản phẩm nào, hiển thị thông báo
                    if (total === 0) {
                        $('#selected-product-add').html(
                            '<p class="text-danger text-center">Vui lòng chọn sản phẩm <strong>*</strong></p>'
                        );
                    } else {
                        // Nếu có sản phẩm, không hiển thị thông báo
                        if (selectedProducts.length > 0) {
                            $('#selected-product-add').find('p.text-danger.text-center')
                                .remove(); // Xóa thông báo nếu có sản phẩm
                        }
                    }
                }

                // Tăng hoặc giảm số lượng sản phẩm
                // $(document).on("click", ".increase", function() {
                //     const input = $(this).siblings('input');
                //     let value = parseInt(input.val());

                //     input.val(value + 1);
                //     updateTotal();
                // });

                // $(document).on("click", ".decrease", function() {
                //     const input = $(this).siblings('input');
                //     let value = parseInt(input.val());
                //     if (value > 1) {
                //         input.val(value - 1);
                //     }
                //     updateTotal();
                // });

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

                // $('#paymentMethod').on('change', function() {
                //     const paymentDetails = $('#paymentDetails');
                //     paymentDetails.empty(); // Xóa thông tin cũ

                //     if (this.value === "Chọn phương thức thanh toán") {
                //         paymentDetails.removeClass('visible').addClass('hidden'); // Ẩn khi chưa chọn
                //         return;
                //     }

                //     paymentDetails.removeClass('hidden').addClass('visible'); // Hiện phần chi tiết

                //     if (this.value === 'credit_card') {
                //         paymentDetails.html(`
            //     <div class="mb-3">
            //         <label for="cardNumber" class="form-label">Số thẻ</label>
            //         <input type="text" class="form-control" id="cardNumber" placeholder="Nhập số thẻ">
            //     </div>
            //     <div class="mb-3">
            //         <label for="cardExpiry" class="form-label">Ngày hết hạn</label>
            //         <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY">
            //     </div>
            //     <div class="mb-3">
            //         <label for="cardCVC" class="form-label">Mã CVC</label>
            //         <input type="text" class="form-control" id="cardCVC" placeholder="Nhập mã CVC">
            //     </div>
            //     `);
                //     } else if (this.value === 'paypal') {
                //         paymentDetails.html(`
            //     <div class="mb-3">
            //         <label for="paypalEmail" class="form-label">Email PayPal</label>
            //         <input type="email" class="form-control" id="paypalEmail" placeholder="Nhập email PayPal">
            //     </div>
            //     `);
                //     } else if (this.value === '2') {
                //         paymentDetails.html(`
            //     <div class="mb-3">
            //         <label for="bankAccount" class="form-label">Số tài khoản ngân hàng</label>
            //         <input type="text" class="form-control" id="bankAccount" placeholder="Nhập số tài khoản">
            //     </div>
            //     <div class="mb-3">
            //         <label for="bankName" class="form-label">Tên ngân hàng</label>
            //         <input type="text" class="form-control" id="bankName" placeholder="Nhập tên ngân hàng">
            //     </div>
            //     `);
                //     } else if (this.value === '1') {
                //         paymentDetails.html(`
            //         <p>Vui lòng chuẩn bị tiền mặt khi nhận hàng.</p>
            //     `);
                //     }
                // });


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

        .add-room-product {
            padding: 4px 10px;
            border: 1px solid #337ab7;
            border-radius: 8px;
            cursor: pointer;
            color: #337ab7;
            margin-left: 26px;
        }

        #selected-product-add .selected-product {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
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
            max-width: ;
            : 250px;
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

        .ellipsis {
            white-space: nowrap;
            /* Ngăn nội dung xuống dòng */
            overflow: hidden;
            /* Ẩn phần nội dung vượt quá chiều rộng */
            text-overflow: ellipsis;
            /* Thêm dấu "..." vào cuối nội dung bị cắt */
            max-width: 200px;
            /* Đặt chiều rộng tối đa cho phần tử */
            display: inline-block;
            /* Đảm bảo thuộc tính hoạt động với phần tử */
        }
    </style>
@endpush
