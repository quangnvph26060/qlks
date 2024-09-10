@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table table-striped table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Tên Nhà Cung Cấp</th>
                                    <th>Thông Tin Liên Hệ</th>
                                    <th>Địa Chỉ</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
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
@endsection

@can('')
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.supplier.create') }}"><i
                class="las la-plus"></i>@lang('Thêm mới')</a>
    @endpush
@endcan

@push('script')
    <script>
        function toggleRepresentatives(id, button) {
            const row = document.getElementById(id);
            row.classList.toggle('show');
            button.classList.toggle('collapsed');
        }
        (function($) {
            "use strict";
            $(document).ready(function() {

                let debounceTimer;
                let currentPage = 1;

                function checkNotData() {
                    if ($("#no-data-row").length <= 0) {
                        fetchData();
                    }
                }

                function fetchData(page = 1) {

                    currentPage = page; // Cập nhật trang hiện tại
                    const search = $('.searchInput').val();
                    const perPage = $('.perPage').val();

                    $.ajax({
                        url: '{{ route('admin.supplier.index') }}',
                        method: 'GET',
                        data: {
                            search,
                            page,
                            perPage
                        },
                        success: function(data) {
                            $('#data-table tbody').html(data.results);
                            $('#pagination').html(data.pagination);
                            notData();

                        }
                    });

                }

                // Tìm kiếm
                $('.searchInput').on('input', function() {
                    clearTimeout(debounceTimer);
                    const searchValue = $(this).val();
                    if (searchValue === "") {
                        // Khi giá trị tìm kiếm rỗng, lấy lại các bản ghi ban đầu
                        checkNotData();
                    }
                    debounceTimer = setTimeout(() => {
                        checkNotData();
                    }, 500);
                });

                // Thay đổi số bản ghi trên trang
                $('.perPage').on('change', function() {
                    checkNotData();
                });

                // Phân trang
                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];

                    fetchData(page);
                });

                // Tải dữ liệu ban đầu
                fetchData();

                function notData() {

                    if ($(".table tbody tr").length == 0) {
                        $('.table tbody').append(
                            '<tr id="no-data-row"><td colspan="6" class="text-center">@lang('Không tìm thấy dữ liệu')</td></tr>'
                        );
                    } else {
                        $('#no-data-row').remove(); // Xóa hàng thông báo nếu có bản ghi
                    }
                }

                let lastId = null;
                // Đặt Swal thông báo chung
                const showSwalMessage = (icon, title, timer = 2000) => {

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: timer,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: icon,
                        title: `<p>${title}</p>`
                    });
                };
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .btn-toggle {
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            padding: 1px 4px;
            /* Đã điều chỉnh padding để đảm bảo độ lớn của nút không thay đổi */
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            text-align: center;
            line-height: 1;
            border-radius: 50%;
            font-family: 'Courier New', Courier, monospace;
            /* Font monospace giúp các ký tự có kích thước đồng nhất */
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
