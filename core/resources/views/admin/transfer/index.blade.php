@extends('admin.layouts.master_iframe')
@section('panel')
    @include('admin.messages')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive p-2">
                        <div class="pager-wrap d-flex justify-content-center">
                            <div class="k-widget d-flex">
                                <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                                    {{ $response->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div class="dt-length">
                                {{-- <select name="example_length" style=" padding: 1px 3px; margin-right: 8px;"
                                    aria-controls="example" class="perPage">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select><label for="perPage"> entries per page</label> --}}
                            </div>
                            <div class="search">
                                <label for="searchInput">Search:</label>
                                <input class="searchInput"
                                    style="padding: 1px 3px; border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                    type="search" placeholder="Tìm kiếm...">
                            </div>
                        </div>
                        <table class="table--light style--two table table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th>Hành động</th>
                                    <th>Mã phiếu</th>
                                    <th>Từ kho</th>
                                    <th>Đến kho</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('')
        @push('breadcrumb-plugins')
            {{-- <a class="btn btn-sm btn-primary" href="{{ route('admin.warehouse.create') }}"><i
                    class="las la-plus"></i></a> --}}
            <!-- Modal Nhập kho -->
            <button type="button" class="btn btn-primary btn-sm" onclick="location.reload();">
                <i class="fa fa-repeat"></i>
            </button>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#warehouseexportModaladd">
                <i class="las la-plus"></i>
            </button>
            <div class="modal fade" id="warehouseModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" style="--bs-modal-width: 933px; !important">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chi tiết phiếu xuất</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Nội dung chi tiết sẽ được load ở đây -->
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="warehouseexportModaladd" tabindex="-1" aria-labelledby="warehouseModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="warehouseModalLabel">Tạo phiếu điều chuyển</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body p-4">
                            @include('admin.transfer.create', [
                                'categories' => $categories,
                                'suppliers' => $suppliers,
                                'products' => $products,
                                'admin' => $admin,
                                'warehouse' => $warehouse,
                            ])

                        </div>
                    </div>
                </div>
            </div>
              <div class="modal fade" id="slipDetailModal" tabindex="-1" aria-labelledby="slipDetailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="slipDetailModalLabel">Lịch sử sửa đổi phiếu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>Người thực hiện</th>
                                            <th>Thời gian</th>
                                            <th>Mô tả</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modificationHistoryTableBody">
                                        <!-- Lịch sử sửa đổi sẽ được thêm vào đây bằng JavaScript -->
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
        @endpush
    @endcan
@endsection
@if ($errors->has('msg'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: '{{ $errors->first('msg') }}',
            confirmButtonColor: '#3085d6',
        });
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
        });
    </script>
@endif


@push('script')
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script>
        (function($) {
            "use strict"
             $(document).on('click', '.openSlipDetailBtn', function() {
                const entryId = $(this).data('id');
                var slipDetailModal = new bootstrap.Modal(document.getElementById('slipDetailModal'));
                slipDetailModal.show();
                const tbody = $('#modificationHistoryTableBody');
                tbody.empty().append('<tr><td colspan="3">Đang tải dữ liệu...</td></tr>');
                 const getLogsRoute = "{{ route('admin.warehouse.transfer.get.logs', ['id' => '__ID__']) }}";
                  const url = getLogsRoute.replace('__ID__', entryId);
                $.ajax({
                    url: url, // 👉 Route xử lý lấy log
                    method: 'GET',
                    success: function(response) {
                        tbody.empty(); // Xoá dòng "Đang tải..."

                        if (response.length === 0) {
                            tbody.append('<tr><td colspan="3">Không có lịch sử</td></tr>');
                            return;
                        }

                        response.forEach(function(log) {
                            const row = `
                        <tr>
                            <td>${log.user_name ?? 'Không rõ'}</td>
                            <td class="text-center">${log.timestamp}</td>
                            <td class="text-center">${log.action}</td>
                        </tr>
                    `;
                            tbody.append(row);
                        });
                    },
                    error: function() {
                        tbody.empty().append('<tr><td colspan="3">Lỗi khi tải dữ liệu</td></tr>');
                    }
                });
            });
            $(document).ready(function() {
                const apiUrl = '{{ route('admin.warehouse.transfer.index') }}';
                initDataFetch(apiUrl);

            });
            let isPrinting = false;
            let printTimeout;
            $(document).on('click', '.open-warehouse-modal-print', function() {
                if (isPrinting) return;
                isPrinting = true;

                const url = $(this).data('url');

                $.get(url, function(data) {
                    const printFrame = document.createElement('iframe');
                    printFrame.style.position = 'fixed';
                    printFrame.style.right = '0';
                    printFrame.style.bottom = '0';
                    printFrame.style.width = '0';
                    printFrame.style.height = '0';
                    printFrame.style.border = '0';
                    printFrame.setAttribute('id', 'print-frame');
                    document.body.appendChild(printFrame);

                    const frameWindow = printFrame.contentWindow || printFrame;
                    const printDoc = frameWindow.document;

                    printDoc.open();
                    printDoc.write(data);
                    printDoc.close();

                    const interval = setInterval(() => {
                        if (printDoc.readyState === 'complete') {
                            clearInterval(interval);

                            frameWindow.focus();
                            frameWindow.print();

                            // Nếu onafterprint không chạy (người dùng HỦY), vẫn reset sau 5s
                            printTimeout = setTimeout(() => {
                                cleanUpPrint();
                            }, 5000);

                            frameWindow.onafterprint = () => {
                                clearTimeout(printTimeout);
                                cleanUpPrint();
                            };
                        }
                    }, 200);
                });
            });

            function cleanUpPrint() {
                const frame = document.getElementById('print-frame');
                if (frame) {
                    document.body.removeChild(frame);
                }
                isPrinting = false;
            }
            $(document).on('click', '.svg_menu_check_in', function(e) {
                e.stopPropagation(); // Ngăn sự kiện lan ra ngoài

                const $dropdown = $(this).closest('td').find('.menu_dropdown_check_in');

                // Ẩn các dropdown khác
                $('.menu_dropdown_check_in').not($dropdown).removeClass('show');

                // Toggle dropdown hiện tại
                $dropdown.toggleClass('show');
            });

            // Khi click ra ngoài thì ẩn menu
            $(document).on('click', function() {
                $('.menu_dropdown_check_in').removeClass('show');
            });
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
                    Swal.fire({
                        toast: true,
                        position: 'top-end', // góc phải phía trên
                        icon: 'error',
                        title: 'Đã nhập quá tồn kho',
                        showConfirmButton: false,
                        timer: 3000, // hiển thị trong 3 giây
                        timerProgressBar: true
                    });
                }
                input.val(value.toLocaleString('vi-VN'));
                updateTotalEdit();
            });


            // Hàm cập nhật tổng tiền
            function updateTotalEdit() {
                let total = 0;
                const selectedProducts = $('#selected-product-edit .selected-product');

                selectedProducts.each(function() {
                    const price = parseFloat(
                        $(this).find('.price-edit').text().replace('Giá: ', '').replace(' VND',
                            '')
                        .replace(/\./g, '')
                    );



                    const quantityRaw = $(this).find('input[type="text"]').val().replace(/\./g, '');
                    const quantity = parseInt(quantityRaw);
                    total += price * quantity;
                    const priceProduct = price * quantity;
                    $(this).find('.price-product').text(priceProduct.toLocaleString('vi-VN') + '');
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
            $(document).on('click', '.confirmPayment-edit', function() {
                const id = $(this).data('id');
                const supplierVal = $('#supplierSelect').val();
                const warehouseVal = $('#employeeSelect').val();
                const paymentVal = $('#paymentMethod').val();
                const dateWarehouse = $('#dateWarehouse').val();
                const warehouse_code = $('#warehouse_code').val();
                const note = $('#note').val();
                const selectedProducts = $('#selected-product-edit .selected-product');
                let productItems = [];

                selectedProducts.each(function() {
                    const $product = $(this);
                    // ID của chi tiết xuất kho (item)
                    const itemId = $product.data('id');
                    // ID của sản phẩm
                    const productId = $product.find('.product-name').data('id');
                    // ID của kho
                    const warehouses_from = $product.find('#warehouses_from').val();
                    const warehouses_to = $product.find('#warehouses_to').val();
                    // Số lượng
                    const quantityRaw = $(this).find('input[name^="products"]').val();
                    const quantity = parseInt(quantityRaw.replace(/\./g, ''));
                    // Đơn giá
                    const price = parseFloat(
                        $product.find('.price-edit').text().replace(/\./g, '').trim()
                    );

                    // Push vào mảng
                    productItems.push({
                        item_id: itemId,
                        product_id: productId,
                        warehouses_from: warehouses_from,
                        warehouses_to: warehouses_to,
                        quantity: quantity,
                        price: price
                    });
                });
                $.ajax({
                    url: '{{ route('admin.warehouse.transfer.update.import.slipe') }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: id,
                        supplier_id: supplierVal,
                        dateWarehouse: dateWarehouse,
                        warehouse_code: warehouse_code,
                        created_by: warehouseVal,
                        payment_method_id: paymentVal,
                        note: note,
                        productItems: productItems,
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire('Thành công', res.message, 'success');
                            window.location.reload();
                        } else {
                            Swal.fire('Lỗi', res.message, 'error');
                        }
                    }
                    // ,
                    // error: function() {
                    //     Swal.fire('Lỗi', 'Không thể cập nhật dữ liệu.', 'error');
                    // }
                });

            });
            $(document).on('click', '.remove-product-edit', function() {
                const $button = $(this);
                const deleteUrl = $button.data('url');
                const productItemId = $button.data('id');
                Swal.fire({
                    title: 'Xác nhận xoá?',
                    text: 'Bạn có chắc muốn xoá sản phẩm này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xoá',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                if (res.status) {
                                    Swal.fire('Đã xoá!', res.message, 'success');
                                    $('.selected-product[data-id="' + productItemId + '"]')
                                        .remove();
                                    if (res.entry_deleted) {
                                        window.location.reload();
                                    }
                                } else {
                                    Swal.fire('Lỗi', res.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Lỗi', 'Không thể xoá sản phẩm.', 'error');
                            }
                        });
                    }
                });
            });


            $(document).on('click', '.open-warehouse-modal', function() {
                const url = $(this).data('url'); // lấy route URL từ data-url

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#warehouseModal .modal-body').html(data);
                        $('#warehouseModal .modal-body').find("script").each(function() {
                            $.globalEval(this.text || this.textContent || this.innerHTML ||
                                '');
                        });
                        $('#warehouseModal').modal('show');
                    }
                });
            });
            $(document).on('submit', '.delete-warehouse-form', function(e) {
                e.preventDefault(); // Ngăn form gửi ngay
                const form = this; // Lưu lại form hiện tại
                Swal.fire({
                    title: 'Xác nhận xoá?',
                    text: 'Bạn có chắc muốn xoá phiếu điều chuyển này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xoá',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Gửi form nếu xác nhận
                    }
                });
            });



        })(jQuery);
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

    <style>
        @media (min-width: 768px) {
            .form-switch .form-check-input {
                float: right
            }
        }

        @media (min-width: 992px) {
            .form-switch .form-check-input {
                margin-left: 50%;
                transform: translateX(-50%);
                float: none;
            }
        }

        .tooltip1 {
            position: relative;
            display: inline-block;
        }

        .tooltip1 .tooltiptext {
            font-size: 8px;
            visibility: hidden;
            width: 150px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 100;
            bottom: 125%;
            /* Vị trí tooltip */
            left: 50%;
            /* margin-left: -75px; */
            /* Để căn giữa */
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip1:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }


        .btn-toggle {
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            padding: 1px 4px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            text-align: center;
            line-height: 1;
            border-radius: 50%;
            font-family: 'Courier New', Courier, monospace;
        }

        .menu_dropdown_check_in .dropdown-item {
            padding: 5px 10px;
        }

        .menu_dropdown_check_in {
            display: none;
            position: fixed;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .menu_dropdown_check_in.show {
            display: block;
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
