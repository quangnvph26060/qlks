@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="warehouse-selector">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-3 text-white">
                                <i class="fas fa-building me-2"></i>
                                Chọn kho hàng
                            </h4>
                            <div class="d-flex gap-3 ">
                                  <select class="form-select form-select-lg" id="warehouseSelect">
                                <option value="all">Tất cả kho hàng</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                           <button type="button" class="btn text-black bg-white btn-lg btn-no-hover" onclick="location.reload();">
    <i class="fa fa-repeat"></i>
</button>

                            </div>

                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="d-flex flex-column align-items-md-end">
                                <div class="d-flex gap-2">
                                    <input type="date" class="form-control form-control-sm bg-white text-black"
                                        id="startDate">
                                    <input type="date" class="form-control form-control-sm bg-white text-black"
                                        id="endDate">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <x-stats-card icon="fas fa-boxes" color="primary" value="" label="Tổng sản phẩm" id="totalProducts" />

            <x-stats-card icon="fas fa-chart-line" color="success" value="" label="Tổng giá trị tồn kho"
                id="totalValue" />

            <x-stats-card icon="fas fa-exclamation-triangle" color="warning" value="" label="Sản phẩm sắp hết"
                id="lowStockItems" />

            <x-stats-card icon="fas fa-times-circle" color="danger" value="" label="Hết hàng" id="outOfStock" />
        </div>

        <!-- Charts Row -->
        {{-- <div class="row mb-4">
            <div class="col-lg-8 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Thống kê theo danh mục
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Tình trạng tồn kho
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="stockStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Inventory Table -->
        <div class="row">
            <div class="col-12">
                <div class="table-container p-2">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Danh sách sản phẩm tồn kho
                        </h5>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control form-control-sm" placeholder="Tìm kiếm sản phẩm..."
                                style="width: 200px;">
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-download me-1"></i>
                                Xuất Excel
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table--light style--two">
                            <thead class="table-light ">
                                <tr>
                                    <th>Mã SP</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Tồn đầu</th>
                                    <th>Nhập</th>
                                    <th>Xuất</th>
                                    <th>Tồn cuối</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryTableBody">
                                {{-- <tr>
                                    <td><strong>SP001</strong></td>
                                    <td>iPhone 15 Pro Max 256GB</td>
                                    <td>Điện thoại</td>
                                    <td>45</td>
                                    <td>1,350,000,000 ₫</td>
                                    <td><span class="badge status-badge high-stock">Đủ hàng</span></td>

                                </tr>
                                <tr>
                                    <td><strong>SP002</strong></td>
                                    <td>Samsung Galaxy S24 Ultra</td>
                                    <td>Điện thoại</td>
                                    <td>12</td>
                                    <td>360,000,000 ₫</td>
                                    <td><span class="badge status-badge medium-stock">Sắp hết</span></td>

                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white">
                        <nav class="" style="justify-content: center !important;margin-top: 10px">
                            <ul id="pagination" class="pagination pagination-sm justify-content-center mb-0">
                                {{-- <li class="page-item disabled">
                                    <span class="page-link" style="white-space: nowrap;">Trước</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Sau</a></li> --}}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- ✅ Kết thúc row chính --}}
@endsection



@push('script')
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script>
        (function($) {
            "use strict"

            $(document).ready(function() {
                const apiUrl = '{{ route('admin.inventory.index') }}';
                initDataFetch(apiUrl);

            });
        })(jQuery);
    </script>
    <script>
        // Initialize charts
        // let categoryChart, stockStatusChart;

        // // Category Chart
        // const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        // categoryChart = new Chart(categoryCtx, {
        //     type: 'bar',
        //     data: {
        //         labels: ['Điện thoại', 'Laptop', 'Tablet', 'Phụ kiện', 'Đồng hồ'],
        //         datasets: [{
        //             label: 'Số lượng tồn kho',
        //             data: [120, 85, 45, 200, 30],
        //             backgroundColor: [
        //                 'rgba(54, 162, 235, 0.8)',
        //                 'rgba(255, 99, 132, 0.8)',
        //                 'rgba(255, 205, 86, 0.8)',
        //                 'rgba(75, 192, 192, 0.8)',
        //                 'rgba(153, 102, 255, 0.8)'
        //             ],
        //             borderColor: [
        //                 'rgba(54, 162, 235, 1)',
        //                 'rgba(255, 99, 132, 1)',
        //                 'rgba(255, 205, 86, 1)',
        //                 'rgba(75, 192, 192, 1)',
        //                 'rgba(153, 102, 255, 1)'
        //             ],
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         responsive: true,
        //         maintainAspectRatio: false,
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        // });

        // Stock Status Chart
        // const stockStatusCtx = document.getElementById('stockStatusChart').getContext('2d');
        // stockStatusChart = new Chart(stockStatusCtx, {
        //     type: 'doughnut',
        //     data: {
        //         labels: ['Đủ hàng', 'Sắp hết', 'Hết hàng'],
        //         datasets: [{
        //             data: [75, 20, 5],
        //             backgroundColor: [
        //                 'rgba(40, 167, 69, 0.8)',
        //                 'rgba(255, 193, 7, 0.8)',
        //                 'rgba(220, 53, 69, 0.8)'
        //             ],
        //             borderColor: [
        //                 'rgba(40, 167, 69, 1)',
        //                 'rgba(255, 193, 7, 1)',
        //                 'rgba(220, 53, 69, 1)'
        //             ],
        //             borderWidth: 2
        //         }]
        //     },
        //     options: {
        //         responsive: true,
        //         maintainAspectRatio: false,
        //         plugins: {
        //             legend: {
        //                 position: 'bottom'
        //             }
        //         }
        //     }
        // });
        $(document).ready(function() {
            // Bắt sự kiện onchange bằng jQuery
            $('#warehouseSelect, #startDate, #endDate').on('change', function() {
                updateWarehouseData(1);
            });

            // Hàm gọi AJAX để cập nhật dữ liệu








            // Tự set ngày mặc định khi load trang
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            const formatDate = (date) => date.toISOString().split('T')[0];
            $('#startDate').val(formatDate(firstDay));
            $('#endDate').val(formatDate(today));

            // Gọi lần đầu
            updateWarehouseData(1);

        });

        function renderServerPagination(currentPage, lastPage) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            // Nút Trước
            pagination.innerHTML += `
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="event.preventDefault(); updateWarehouseData(${currentPage - 1})" style="white-space: nowrap;">Trước</a>
                    </li>
                `;

            // Các trang số
            for (let i = 1; i <= lastPage; i++) {
                pagination.innerHTML += `
                        <li class="page-item ${currentPage === i ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="event.preventDefault(); updateWarehouseData(${i})">${i}</a>
                        </li>
                    `;
            }

            // Nút Sau
            pagination.innerHTML += `
                    <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="event.preventDefault(); updateWarehouseData(${currentPage + 1})">Sau</a>
                    </li>
                `;
        }

        function formatNumber(number) {
            return Number(number).toLocaleString('en-US');
        }
        const renderInventory = (data) => {
            const tbody = document.getElementById('inventoryTableBody');
            tbody.innerHTML = ''; // clear table

            data.forEach(item => {
                let stockClass = 'high-stock';
                let stockText = 'Đủ hàng';

                if (item.ton_cuoi <= 5) {
                    stockClass = 'low-stock';
                    stockText = 'Hết hàng';
                } else if (item.ton_cuoi <= 10) {
                    stockClass = 'medium-stock';
                    stockText = 'Sắp hết';
                }

                const row = `
                            <tr>
                                <td class="text-left"><strong>${item.product_code ?? ""}</strong></td>
                                <td>${item.product_name}</td>
                                <td class="text-right">${formatNumber(item.ton_dau)}</td>
                                <td class="text-right">${formatNumber(item.nhap)}</td>
                                <td class="text-right">${formatNumber(item.xuat)}</td>
                                <td class="text-right">${formatNumber(item.ton_cuoi)}</td>
                            </tr>
                        `;

                tbody.insertAdjacentHTML('beforeend', row);
            });
        };

        function updateWarehouseData(page = 1) {
            const warehouseId = $('#warehouseSelect').val();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            $.ajax({
                url: '{{ route('admin.inventory.get') }}',
                method: 'GET',
                data: {
                    warehouse_id: warehouseId,
                    start_date: startDate,
                    end_date: endDate,
                    page: page
                },
                success: function(data) {
                    $('#totalProducts').text(Number(data.total_inventory).toLocaleString('vi-VN'));
                    const formattedTotal = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(data.entries_sum ?? 0);

                    $('#totalValue').text(formattedTotal);
                    $('#lowStockItems').text(data.lowStockCount);
                    $('#outOfStock').text(data.outOfStock);
                    renderInventory(data.data);
                    renderServerPagination(data.current_page, data.last_page);
                },
                error: function(xhr) {
                    console.error('Lỗi khi lấy dữ liệu tồn kho:', xhr.responseText);
                }
            });
        }
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-card {
            transition: transform 0.2s;
        }
       
    .btn-no-hover:hover {
        background-color: white !important;
        color: black !important;
        border-color: transparent !important;
        box-shadow: none !important;
    }


        .stats-card:hover {
            transform: translateY(-5px);
        }

        .warehouse-selector {
            background: #005AA1;
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 30px;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .status-badge {
            font-size: 0.8em;
            padding: 4px 8px;
        }

        .low-stock {
            background-color: #dc3545;
            color: white;
        }

        .medium-stock {
            background-color: #ffc107;
            color: black;
        }

        .high-stock {
            background-color: #28a745;
            color: white;
        }
    </style>
@endpush
