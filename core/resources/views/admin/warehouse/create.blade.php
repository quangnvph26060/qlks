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
    <div class="row clamp-responsive">

        <!-- Cột trái: Mã phiếu + Nhà cung cấp -->
        <div class="col-md-6">

            <input type="text" class="form-control text-uppercase" id="warehouse_code" placeholder="Nhập mã phiếu"
                name="warehouse_code" style="height: 40px; text-transform: uppercase;">

            <select class="form-select mt-2" id="supplierSelect" name="supplier_id" aria-label="Chọn nhà cung cấp">
                <option selected disabled>--- Chọn nhà cung cấp ---</option>
                @foreach ($suppliers as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>

        </div>

        <!-- Cột phải: Ngày + Thanh toán -->
        <div class="col-md-6">

            <input type="date" class="form-control" id="dateWarehouse" name="dateWarehouse"
                value="{{ date('Y-m-d') }}" style="height: 40px;">

            <select class="form-select mt-2" id="paymentMethod" name="payment_method_id"
                aria-label="Chọn phương thức thanh toán">
                <option selected disabled>Chọn phương thức thanh toán</option>
                <option value="1">Thanh toán khi nhận hàng</option>
                <option value="2">Thanh toán chuyển khoản</option>
            </select>



        </div>

        <!-- Ghi chú (chiếm toàn bộ chiều ngang) -->
        <div class="col-12 mt-2">

            <textarea name="note" id="note" class="form-control" rows="2" placeholder="Ghi chú"></textarea>


        </div>

    </div>

    {{-- Sản phẩm đã chọn --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
               
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
              
                <div class="card-body">
                    <div class="d-flex fw-bold border-bottom pb-2 mb-2 justify-content-between"
                        style="padding-left: 4px;">
                        <div style="width: 150px;font-weight: bold;"class="responsive-text">Sản phẩm
                        </div>
                        <div style="width: 130px;text-align: center;font-weight: bold;"class="responsive-text">
                            Số lượng</div>
                        <div
                            style="width: 100px;font-weight: bold;"class="responsive-text d-flex justify-content-center">
                            Giá</div>
                        <div style="width: 130px;font-weight: bold;"class="responsive-text">Thành tiền
                        </div>
                        <div style="width: 120px;text-align: center;  font-weight: bold;" class="responsive-text">
                            Kho</div>
                        <div style="width: 37px; font-weight: bold;" class="responsive-text">Xóa</div>
                    </div>
                    <div id="selected-product-add" style="height: 250px; overflow-y: auto;">
                        <p class="text-danger text-center">Vui lòng chọn sản phẩm <strong>*</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tổng cộng + Xác nhận --}}
    <div class="row">
        <div class="col-12 d-flex justify-content-end align-items-end gap-1 flex-column">
            <div>
                <div class="fw-bold">Tổng tiền: <span class="total-price text-success"> 0 VND</span></div>
            </div>
            <button class="btn btn-primary" type="submit" id="confirmPayment">Lưu</button>
        </div>
    </div>
</form>
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content move-up">
            <div class="modal-header">
                <h5 class="modal-title">Chọn sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 400px">
                <div class="table-responsive" style="max-height: auto;  overflow-y: auto;">
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
                                    <td class="text-right">{{ number_format($product->import_price, 0, ',', '.') }}</td>
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
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('money-input')) {
                    let value = e.target.value.replace(/\D/g, ""); // Xóa ký tự không phải số
                    value = Number(value).toLocaleString('vi-VN'); // Định dạng theo chuẩn Việt Nam
                    e.target.value = value;
                }
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

                        // 👇 Lấy quantity và loại bỏ dấu chấm
                        const quantityRaw = $(this).find('input[name^="products"]').val();
                        const quantity = parseInt(quantityRaw.replace(/\./g, ''));

                        const warehouseId = $(this).find('select[name^="warehouses"]').val();
                        const price = $(this).find('.price').val().replace(/\./g, '');
                        if (!warehouseId || !quantity || quantity <= 0) {
                            valid = false;
                            return;
                        }

                        products.push({
                            product_id: productId,
                            quantity: quantity,
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
                            <div class="selected-product d-flex justify-content-between align-items-center " data-id="${productId}">
                                <div class="product-name  me-3 flex-shrink-0 " style="width: 150px; max-width: 150px; overflow: hidden; white-space: nowrap;">
                                    ${product.name}
                                </div>

                                <div class="quantity d-flex align-items-center me-3 flex-shrink-0" style="width: 130px;">
                                    <input type="text" class="form-control mx-2 handled-focus no-border money-input" name="products[${productId}]"
                                        value="1" min="1"
                                        style="width: 100%; text-align: center; height: 30px;" >
                                </div>
                                <div style="width: 130px;">
                                    <input type="text" style="width: 100%; text-align: center; height: 30px;"
                                    class="form-control text-success me-3 flex-shrink-0 price money-input  no-border"
                                    data-price="${product.selling_price}"
                                    value="${Number(product.selling_price).toLocaleString('vi-VN')}" />
                                </div>

                                <div class="product-price text-success me-3 flex-shrink-0 price-product" style="width: 100px;font-size: 15px;">
                                    <!-- Có thể thêm tổng tiền ở đây nếu cần -->
                                </div>

                                    <select name="warehouses[${productId}]" class="form-select form-select-sm"style="width: 120px;  height: 30px !important; ">
                                        <option selected disabled>Chọn kho</option>
                                        ${warehouseOptions}
                                    </select>

                                <button class="btn btn-outline-danger btn-sm remove-product flex-shrink-0" >X</button>
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

                    // Xoá dấu chấm để lấy số gốc
                    let rawValue = input.val().replace(/\./g, '');

                    // Parse lại giá trị
                    let value = parseInt(rawValue) || 0;

                    // Set lại value đã định dạng
                    input.val(value.toLocaleString('vi-VN'));

                    // Gọi cập nhật tổng (nếu có)
                    updateTotal();
                });
                $(document).on("blur", ".price", function() {
                    const input = $(this);
                    let rawValue = input.val().replace(/\./g, '');
                    let value = parseInt(rawValue) || 0;
                    input.val(value.toLocaleString('vi-VN'));

                    updateTotal();

                })

                // Hàm cập nhật tổng tiền
                function updateTotal() {
                    let total = 0;
                    const selectedProducts = $('#selected-product-add .selected-product');

                    selectedProducts.each(function() {
                        const price = parseFloat(
                            $(this).find('.price').val()
                            .replace('Giá: ', '')
                            .replace(' VND', '')
                            .replace(/\./g, '')
                        );

                        // 🔧 Lấy giá trị số từ input đã format
                        const quantityRaw = $(this).find('input[type="text"]').val().replace(/\./g, '');
                        const quantity = parseInt(quantityRaw);

                        const priceProduct = price * quantity;
                        total += priceProduct;

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
    <style scoped>
        /* Auto-scaling modal styles */
        .modal-dialog {
            height: min(95vh, 880px);
            margin: clamp(10px, 2.5vh, 1.75rem) auto;
        }

        .modal-content {
            /* height: 100%; */
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

        /* ✅ Mặc định áp dụng cho màn hình nhỏ */
        .move-up {
            margin: 0 auto;
            position: relative;
            top: 80px;
            /* Vị trí cách top cố định */
        }

        @media screen and (max-height: 700px) {
            .move-up {
                top: 10px;
            }
        }

        input.no-border {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        @media screen and (min-height: 701px) and (max-height: 900px) {
            .move-up {
                top: 10px;
            }
        }

        textarea {
            min-height: 51px !important;
        }

        @media screen and (min-height: 901px) {
            .move-up {
                top: -100px;
            }
        }

        .card-body {
            padding-bottom: 0.5rem;
            /* hoặc 0 nếu muốn sát luôn */
        }



        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: clamp(8px, 2vh, 1.5rem) !important;
        }

        .clamp-responsive {
            /* height: clamp(166px, 5vw, 200px) !important; */
        }

        /* Form elements auto-scaling */
        .form-control,
        .form-select {
            height: clamp(32px, 5vh, 40px) !important;
            font-size: clamp(11px, 1.8vh, 14px);
            /* padding: clamp(4px, 1vh, 8px) clamp(6px, 1.5vh, 12px); */
        }

        textarea.form-control {
            /* height: clamp(40px, 8vh, 80px) !important; */
            resize: none;
        }

        /* Card adjustments */
        .card {
            margin-bottom: clamp(8px, 1.5vh, 1rem);
        }

        .card-body {
            /* padding: clamp(8px, 1.5vh, 1rem); */
        }

        .card-header {
            padding: clamp(6px, 1.2vh, 0.75rem) clamp(8px, 1.5vh, 1rem);
        }

        /* Product selection area */
        .add-room-product {
            font-size: clamp(11px, 1.8vh, 14px);
            margin-bottom: clamp(4px, 0.8vh, 0.5rem);
            display: flex;
            align-items: center;
            gap: clamp(4px, 0.8vh, 8px);
            cursor: pointer;
        }

        .add-room-product svg {
            width: clamp(16px, 2.5vh, 20px);
            height: clamp(16px, 2.5vh, 20px);
        }

        /* Product table header */
        .product-header {
            font-size: clamp(10px, 1.6vh, 13px);
            padding: clamp(4px, 0.8vh, 8px) clamp(2px, 0.4vh, 4px);
        }

        /* Selected products area */
        #selected-product-add {
            height: clamp(70px, 20vh, 120px) !important;
            overflow-y: auto;
            font-size: clamp(10px, 1.6vh, 15px);
        }

        @media screen and (min-width: 1200px) {
            #selected-product-add {
                height: clamp(70px, 250px, 250px) !important;

            }

            .clamp-responsive {
                /* height: clamp(200px, 5vw, 200px) !important; */
            }


        }


        /* Product item styling */
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: clamp(4px, 0.8vh, 8px) clamp(2px, 0.4vh, 4px);
            border-bottom: 1px solid #e9ecef;
            font-size: clamp(10px, 1.6vh, 12px);
        }



        .product-item input[type="number"] {
            width: clamp(50px, 8vw, 80px);
            height: clamp(24px, 3vh, 32px) !important;
            text-align: center;
            font-size: clamp(9px, 1.4vh, 11px);
        }

        .product-item select {
            width: clamp(70px, 12vw, 100px);
            height: clamp(24px, 3vh, 32px) !important;
            font-size: clamp(9px, 1.4vh, 11px);
        }

        .product-item .btn-sm {
            padding: clamp(2px, 0.4vh, 4px) clamp(4px, 0.8vh, 6px);
            font-size: clamp(9px, 1.4vh, 11px);
        }

        /* Footer area */
        .total-section {
            margin-top: clamp(8px, 1.5vh, 1rem);
            padding-top: clamp(8px, 1.5vh, 1rem);
            border-top: 1px solid #e9ecef;
        }

        .total-price {
            font-size: clamp(14px, 2.2vh, 18px);
            font-weight: 600;
        }

        .btn {
            padding: clamp(4px, 0.8vh, 6px) clamp(12px, 2.4vh, 16px);
            font-size: clamp(11px, 1.8vh, 14px);
        }

        /* Product selection modal */


        .product-modal-body {
            height: clamp(250px, 40vh, 400px);
        }

        /* .table-responsive {
                                            max-height: clamp(200px, 35vh, 300px) !important;
                                            overflow-y: auto;
                                        } */

        .table {
            font-size: clamp(10px, 1.6vh, 13px);
        }

        .table th,
        .table td {
            padding: clamp(4px, 0.8vh, 8px) clamp(6px, 1.2vh, 12px);
        }

        /* Responsive adjustments for very small screens */
        @media (max-height: 600px) {
            .modal-dialog {
                height: 98vh;
                margin: 1vh auto;
            }

            .modal-body {
                padding: 8px;
            }

            .card-body {
                padding: 6px;
            }

            #selected-product-add {
                height: 100px !important;
            }

            .form-control,
            .form-select {
                height: 28px !important;
                font-size: 11px;
            }

            textarea.form-control {
                height: 35px !important;
            }
        }

        @media (max-height: 500px) {
            .modal-dialog {
                height: 99vh;
                margin: 0.5vh auto;
            }

            .modal-header {
                padding: 6px 12px;
            }

            .modal-body {
                padding: 6px;
            }

            #selected-product-add {
                height: 80px !important;
            }

            .card {
                margin-bottom: 4px;
            }
        }

        /* Landscape mobile optimization */
        @media (max-height: 450px) and (orientation: landscape) {
            .row .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            #selected-product-add {
                height: 60px !important;
            }

            .product-header {
                font-size: 9px;
            }
        }
    </style>
    <style scoped>
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
            margin-top: 10px;
            margin-left: 13px;
        }

        #selected-product-add .selected-product {

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
