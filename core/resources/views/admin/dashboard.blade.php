@extends('admin.layouts.master_iframe')
@php
    //$widget = [];
    //  $widget['today_booked']             = 1231;
    // $widget['today_available']          = 123;
    $widget['total'] = 2;
    $widget['active'] = 1;

    $widget['delayed_checkout'] = 4;
    $widget['upcoming_checkin'] = 23;
    $widget['upcoming_checkout'] = 324;

    $widget['total_users'] = 234;
    $widget['verified_users'] = 44;
    $widget['email_unverified_users'] = 555;
    $widget['mobile_unverified_users'] = 66;

@endphp
@section('panel')
    <div class="row gy-4">
        {{-- <div class="col-xxl-3 col-sm-6">
            <x-widget color="danger" icon="la la-sign-out transform-rotate-180" link="admin.delayed.booking.checkout"
                style="2" cover_cursor="1" overlay_icon="0" title="Khách trả phòng muộn"
                value="{{ $widget['delayed_checkout'] }}" />
        </div> --}}
        <div class="col-xxl-3 col-sm-6">
            <x-widget color="info" date="1" dateNow="{{ $bookedRoom }}" inputName="booked_room"
                icon="la la-hospital-alt" icon_style="false" link="admin.book.room" query_string="type=not_booked"
                style="2" cover_cursor="1" overlay_icon="0" title="Phòng trống hôm nay"
                value="{{ $widget['today_available'] }}" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget color="dark" date="1" dateNow="{{ $availableRoom }}" inputName="available_room"
                icon="la la-check-circle" icon_style="false" link="admin.book.room" style="2" cover_cursor="1"
                overlay_icon="0" title="Phòng đã đặt hôm nay" value="{{ $widget['today_booked'] }}" />
        </div>


        <div class="col-xxl-3 col-sm-6">
            <x-widget color="info" icon="la la-sign-out transform-rotate-180" link="admin.upcoming.booking.checkout"
                style="2" cover_cursor="1" overlay_icon="0" title="Phòng đang hoạt động"
                value="{{ $widget['room_checkIn'] }}" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget color="warning" icon="la la-sign-in" link="admin.book.room" style="2" cover_cursor="1"
                overlay_icon="0" title="Khách nhận phòng muộn" value="{{ $widget['pending_checkin'] }}" />
        </div>
        {{-- 
        <div class="col-xxl-3 col-sm-6">
            <x-widget color="info" icon="la la-sign-in" link="admin.upcoming.booking.checkin" style="2"
                cover_cursor="1" overlay_icon="0" title="Đăng ký sắp tới" value="{{ $widget['upcoming_checkin'] }}" />
        </div> --}}





        <div class="col-xxl-3 col-sm-6">
            <x-widget color="success" icon="la la-clipboard-check" icon_style="false" link="admin.book.room" style="2"
                cover_cursor="1" overlay_icon="0" title="Phòng chưa dọn dẹp" value="{{ $widget['is_clean'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget color="primary" icon="la la-city" icon_style="false" link="admin.book.room" style="2"
                cover_cursor="1" overlay_icon="0" title="Phòng sửa chữa" value="{{ $widget['room_fix'] }}" />
        </div>
    </div>

    <div class="row mb-none-30 mt-30">
        <div class="col-xl-12 mb-30">
            <div class="card">
                <h3 style="padding: 20px 20px 0;">Số lượng phòng</h3>

                <div class="tabs"
                    style="display: flex; gap: 20px; padding: 0 20px; margin-top: 10px; border-bottom: 2px solid #eaeaea;">
                    <a href="#" class="active"
                        style="text-decoration: none; padding-bottom: 8px; font-weight: bold; color: #007bff; border-bottom: 2px solid #007bff;">Theo
                        hạng phòng</a>
                </div>

                {{-- <div class="filters" style="padding: 10px 20px;">
                    <label for="timeFilter">Thời gian:</label>
                    <select id="timeFilter"
                        style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                        <option value="today">Hôm nay</option>
                        <option value="yesterday">Hôm qua</option>
                        <option value="last7">7 ngày qua</option>
                        <option value="thisMonth" selected>Tháng này</option>
                        <option value="lastMonth">Tháng trước</option>
                    </select>
                </div> --}}

                <div style="padding: 0 20px 20px;">
                    <canvas id="occupancyChart" width="100%" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mb-30">
            <div class="card">
              <div class="d-flex align-items-center justify-content-between">
                  <h3 style="padding: 20px 20px 0;">DOANH THU THÁNG NÀY</h3>
                <div class="filters" style="padding: 10px 20px;">
                    <select id="timeFilter"
                        style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                        <option value="today">Hôm nay</option>
                        <option value="yesterday">Hôm qua</option>
                        <option value="last7">7 ngày qua</option>
                        <option value="thisMonth" selected>Tháng này</option>
                        <option value="lastMonth">Tháng trước</option>
                    </select>
                </div>
              </div>
                <div class="revenue-summary" style="padding: 20px;">
                    <span
                        style="color: #007bff; font-size: 24px; font-weight: bold; display: inline-flex; align-items: center; gap: 5px;">
                        💰 1,549,499,000
                    </span>
                    <span
                        style="margin-left: 20px; color: #fd7e14; font-size: 20px; font-weight: bold; display: inline-flex; align-items: center; gap: 5px;">
                        📄 24
                    </span>
                </div>

                <div class="chart-tabs" style="display: flex; justify-content: flex-end; padding: 0 20px;">
                    <a href="#" class="chart-tab active" id="barTab"
                        style="text-decoration: none; font-weight: bold; color: #007bff; border-bottom: 2px solid #007bff; margin-right: 10px;">Biểu
                        đồ cột</a>
                    <a href="#" class="chart-tab" id="pieTab"
                        style="text-decoration: none; color: #6c757d;">Biểu đồ tròn</a>
                </div>

                <div style="padding: 0 20px 20px;">
                    <canvas id="revenueChart"></canvas>
                </div>

            </div>
        </div>


    </div>
    {{-- 
    <div class="row mb-none-30 mt-30">
        <div class="col-xxl-3 col-sm-6">
            <x-widget bg="primary" icon="las la-users f-size--56" link="admin.users.all" title="Tổng số khách đã đăng ký" value="{{ $widget['total_users'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget bg="success" icon="las la-user-check f-size--56" link="admin.users.active" title="Khách đã đăng ký đang hoạt động" value="{{ $widget['verified_users'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget bg="danger" icon="lar la-envelope f-size--56" link="admin.users.email.unverified" title="Email Khách chưa được xác minh" value="{{ $widget['email_unverified_users'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget bg="red" icon="las la-mobile-alt f-size--56" link="admin.users.mobile.unverified" title="Khách chưa xác minh trên thiết bị di động" value="{{ $widget['mobile_unverified_users'] }}" />
        </div>
    </div> --}}

    <div class="row mb-none-30 mt-5">
        {{-- <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Đăng nhập bằng trình duyệt') (@lang('30 ngày qua'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Đăng nhập bằng hệ điều hành') (@lang('30 ngày qua'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Đăng nhập theo quốc gia') (@lang('30 ngày qua'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dataRoomType = @json($roomTypeLabels);
        const dataPercent = @json($roomTypePercents);
        const ctx = document.getElementById('occupancyChart').getContext('2d');

        const occupancyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataRoomType,
                datasets: [{
                    label: 'Chi nhánh trung tâm',
                    data: dataPercent,
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false, // Thêm dòng này
                scales: {
                    x: {
                        max: 100,
                        ticks: {
                            callback: value => `${value}%`
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: context => `${context.raw}%`
                        }
                    }
                }
            }
        });

        // document.getElementById('timeFilter').addEventListener('change', function() {
        //     alert('Bạn chọn: ' + this.options[this.selectedIndex].text);
        //     // TODO: Gọi AJAX để cập nhật dữ liệu nếu cần
        // });

        const labels = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        const dataValues = [
            50000000, 630000000, 850000000, 19499000,
            100000000, 0, 0, 0, 0, 0, 0, 0
        ];

        const ctx2 = document.getElementById('revenueChart').getContext('2d');

        let revenueChart = null;

        function createBarChart() {
            return new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Chi nhánh trung tâm',
                        data: dataValues,
                        backgroundColor: '#007bff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            barThickness: 30,
                            maxBarThickness: 40,
                            ticks: {
                                font: {
                                    size: 13
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => `${value / 1000000} tr`,
                                font: {
                                    size: 13
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: context => `${context.dataset.label}: ${context.raw.toLocaleString()} VNĐ`
                            }
                        }
                    }
                }
            });
        }

        function createPieChart() {
            return new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Chi nhánh trung tâm',
                        data: dataValues,
                        backgroundColor: labels.map((_, i) => `hsl(${i * 30}, 70%, 50%)`)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: {
                                    size: 13
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: context => `${context.label}: ${context.raw.toLocaleString()} VNĐ`
                            }
                        }
                    }
                }
            });
        }

        // Khởi tạo chart mặc định là biểu đồ cột
        revenueChart = createBarChart();

        // Xử lý chuyển đổi tab
        document.getElementById('barTab').addEventListener('click', function(e) {
            e.preventDefault();
            if (revenueChart) revenueChart.destroy();
            revenueChart = createBarChart();

            // Update UI tab
            this.classList.add('active');
            this.style.color = '#007bff';
            this.style.borderBottom = '2px solid #007bff';

            const pieTab = document.getElementById('pieTab');
            pieTab.classList.remove('active');
            pieTab.style.color = '#6c757d';
            pieTab.style.borderBottom = 'none';
        });

        document.getElementById('pieTab').addEventListener('click', function(e) {
            e.preventDefault();
            if (revenueChart) revenueChart.destroy();
            revenueChart = createPieChart();

            // Update UI tab
            this.classList.add('active');
            this.style.color = '#007bff';
            this.style.borderBottom = '2px solid #007bff';

            const barTab = document.getElementById('barTab');
            barTab.classList.remove('active');
            barTab.style.color = '#6c757d';
            barTab.style.borderBottom = 'none';
        });
    </script>
@endpush
@push('style')
    <style>
        .apexcharts-menu {
            min-width: 120px !important;
        }

        .card {
            border: 1px solid #e3e6f0;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
            padding-bottom: 20px;
        }

        .card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .tabs a {
            font-size: 14px;
            padding: 10px 0;
            display: inline-block;
            position: relative;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .tabs a.active {
            font-weight: bold;
            color: #007bff;
            border-bottom: 2px solid #007bff;
        }

        .filters {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .filters label {
            margin-right: 10px;
            font-size: 14px;
            color: #333;
        }

        .filters select {
            font-size: 14px;
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            min-width: 150px;
        }

        /* Make canvas responsive */
        canvas#occupancyChart {
            width: 100% !important;
            max-height: 300px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            padding: 20px;
            overflow: hidden;
        }

        .revenue-summary {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .revenue-summary span {
            display: inline-flex;
            align-items: center;
            font-weight: bold;
            gap: 8px;
        }

        .revenue-summary span:first-child {
            color: #007bff;
            font-size: 24px;
        }

        .revenue-summary span:last-child {
            color: #fd7e14;
            font-size: 20px;
        }

        .chart-tabs {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
            font-size: 14px;
            padding: 0 20px;
        }

        .chart-tabs a {
            text-decoration: none;
            color: #007bff;
            margin-left: 10px;
            padding-bottom: 4px;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .chart-tabs a.active {
            font-weight: bold;
            border-bottom-color: #0076f3;
        }

        canvas#revenueChart {
            width: 100% !important;
            max-width: 100%;
            height: 400px !important;
            min-height: 400px;
        }
    </style>
@endpush
