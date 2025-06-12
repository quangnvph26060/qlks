@extends('admin.layouts.master_iframe')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4 mt-2 mb-2">
                <div class="d-flex justify-content-center ali gap-3">
                    <button class="btn btn--primary btn-reload" data-modal_title="Làm mới">
                        <i class="fa fa-repeat p-1"></i>
                    </button>
                    <input type="text" placeholder="Đặt phòng" id="search-input-code" style="height: 36px"
                        class="form-control" value="" />
                    <input type="text" placeholder="Nhân viên" id="search-input-staff" style="height: 36px"
                        class="form-control" value="" />
                    <button type="submit" class="btn btn--primary btn-search-history">
                        <i class="las la-search p-1"></i>
                    </button>
                </div>
            </div>
            <div class="border p-2">
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <nav class="mt-3 justify-content-center mb-2">
                            <ul class="pagination justify-content-center" id="pagination"></ul>
                        </nav>
                        <div class="table-responsive--sm">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>Đặt phòng</th>
                                        <th>@lang('Tên phòng')</th>
                                        <th>Tình trạng</th>
                                        <th>@lang('Ngày tạo')</th>
                                        <th>@lang('Nhân viên')</th>
                                        @can()
                                            <th>@lang('Hành động')</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="data-table"></tbody>
                            </table>
                            {{-- Nơi hiển thị phân trang --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            fetchData(1); // Mặc định trang 1
            $(document).on('click', '.btn-reload', function() {
                location.reload();
            });
            // Gắn sự kiện click cho phân trang
            $(document).on('click', '#pagination a.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                fetchData(page);
            });
            $(document).on('click', '.btn-search-history', function() {
                let code = $('#search-input-code').val().trim();
                let staff = $('#search-input-staff').val().trim();

                $.ajax({
                    url: "{{ route('admin.getbookingActionHistory.booking.getbookingActionHistory') }}",
                    type: 'GET',
                    data: {
                        code: code,
                        staff: staff,
                        _token: $('meta[name="csrf-token"]').attr(
                            'content') // CSRF token nếu dùng Laravel
                    },
                    success: function(response) {
                        if (response.success) {
                            const tbody = $(".data-table");
                            const pagination = $("#pagination");
                            tbody.empty();
                            pagination.empty();

                            const data = response.data.data; // Lấy mảng data
                            const currentPage = response.data.current_page;
                            const lastPage = response.data.last_page;

                            if (data.length === 0) {
                                tbody.append(
                                    '<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>'
                                    );
                                return;
                            }

                            $.each(data, function(index, item) {
                                const roomName = item.room ?? '(Không có)';
                                const adminName = item.admin ?? '(Không có)';
                                const createdAt = item.action_time ?? '-';

                                let actions = '';
                                @can('some_permission')
                                    actions = `
                                    <button class="btn btn-sm btn-danger btn-delete-clean"
                                        onclick="confirmDelete(${item.id})"
                                        data-id="${item.id}">
                                        Xoá
                                    </button>`;
                                @endcan

                                tbody.append(`
                                <tr>
                                    <td>${index + 1 + ((currentPage - 1) * 10)}</td>
                                    <td>${item.booking_id}</td>
                                    <td>${roomName}</td>
                                    <td>${item.remark}</td>
                                    <td>${formatDatetime(createdAt)}</td>
                                    <td>${adminName}</td>
                                    <td>${actions}</td>
                                </tr>
                            `);
                            });

                            // Render nút phân trang
                            for (let i = 1; i <= lastPage; i++) {
                                const active = i === currentPage ? 'active' : '';
                                pagination.append(`
                                <li class="page-item ${active}">
                                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                                </li>
                            `);
                            }
                        }
                    },
                    error: function(xhr) {
                        console.log('Có lỗi xảy ra:', xhr.responseText);
                    }
                });

            })
        });

        function formatDatetime(datetimeStr) {
            const date = new Date(datetimeStr);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${day}/${month}/${year} ${hours}:${minutes}`;
        }

        function fetchData(page = 1) {
            $.ajax({
                url: "{{ route('admin.getbookingActionHistory.booking.getbookingActionHistory') }}",
                method: "GET",
                data: {
                    page: page
                },
                success: function(response) {
                    if (response.success) {
                        const tbody = $(".data-table");
                        const pagination = $("#pagination");
                        tbody.empty();
                        pagination.empty();

                        const data = response.data.data; // Lấy mảng data
                        const currentPage = response.data.current_page;
                        const lastPage = response.data.last_page;

                        if (data.length === 0) {
                            tbody.append('<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>');
                            return;
                        }

                        $.each(data, function(index, item) {
                            const roomName = item.room ?? '(Không có)';
                            const adminName = item.admin ?? '(Không có)';
                            const createdAt = item.action_time ?? '-';

                            let actions = '';
                            @can('some_permission')
                                actions = `
                                    <button class="btn btn-sm btn-danger btn-delete-clean"
                                        onclick="confirmDelete(${item.id})"
                                        data-id="${item.id}">
                                        Xoá
                                    </button>`;
                            @endcan

                            tbody.append(`
                                <tr>
                                    <td>${index + 1 + ((currentPage - 1) * 10)}</td>
                                    <td>${item.booking_id}</td>
                                    <td>${roomName}</td>
                                    <td>${item.remark}</td>
                                    <td>${formatDatetime(createdAt)}</td>
                                    <td>${adminName}</td>
                                    <td>${actions}</td>
                                </tr>
                            `);
                        });

                        // Render nút phân trang
                        for (let i = 1; i <= lastPage; i++) {
                            const active = i === currentPage ? 'active' : '';
                            pagination.append(`
                                <li class="page-item ${active}">
                                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                                </li>
                            `);
                        }
                    }
                },
                error: function() {
                    alert("Đã có lỗi xảy ra khi tải dữ liệu.");
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Hành động này sẽ xóa dữ liệu lịch sử đặt phòng!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url =
                        "{{ route('admin.delBookingActionHistory.booking.delBookingActionHistory', ['id' => ':id']) }}";
                    url = url.replace(':id', id);

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                fetchData();
                            } else {
                                Swal.fire('Lỗi!', 'Đã xảy ra lỗi khi xóa dữ liệu.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể gửi yêu cầu đến máy chủ.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
