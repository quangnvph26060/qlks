@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">  <div class="pagination-container"> </div>
            <div class="card b-radius--10">

                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive mt-1">
                        <table class="table--light style--two table" id="data-table">
                            <thead>
                                <tr>
                                    <th data-table="Hạng phòng">Hạng phòng</th>
                                    <th data-table="Phòng">Tên phòng</th>
                                    <th data-table="Ngày">Ngày</th>
                                    <th data-table="Trạng thái phòng">Trạng thái phòng</th>
                                    <th data-table="Giá">Giá</th>
                                    {{-- <th data-table="Thao tác">Thao tác</th> --}}
                                </tr>
                            </thead>

                            <tbody id="show-room">

                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- @if ($roomStatusHistory->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($roomStatusHistory) }}
                    </div>
                @endif --}}
            </div>
        </div>

    </div>



    @can('admin.hotel.room.search')
        @push('breadcrumb-plugins')
            <!-- Form tìm kiếm trực tiếp -->
            <div class=" mt-2 d-flex mb-2" style="gap: 10px;justify-content: space-around;">
                <div class="">
                    <label for="">Chọn hạng phòng</label>
                    <select class="form-select" id="selected-hang-phong">
                    </select>
                </div>
                <div class="">
                    <label for="">Chọn tên phòng</label>
                    <select class="form-select" id="selected-name-phong">

                    </select>
                </div>
                <div class="">
                    <label for="">Từ ngày</label>
                    <input type="date" class="form-control " id="date-chon-phong-in" style="height: 38px">
                </div>
                <div class="">
                    <label for="">Đến ngày</label>
                    <input type="date" class="form-control" id="date-chon-phong-out" style="height: 38px">
                </div>
                <div class="">
                    <label for="">Trạng thái phòng</label>
                    <select class="form-select" id="status-room">
                        <option value="">Chọn trạng tên phòng</option>
                        <option value="Trống">Trống</option>
                        <option value="Đã đặt">Đã đặt</option>
                        <option value="Đã nhận">Đã nhận</option>
                    </select>
                </div>
            </div>
        @endpush
    @endcan

    @can('admin.hotel.room.status')
        <x-confirmation-modal />
    @endcan
@endsection

{{-- @push('breadcrumb-plugins')
    <button class="btn btn-outline--primary" data-bs-target="#addModal" data-bs-toggle="modal"><i
            class="las la-plus"></i>
        @lang('Thêm mới')</button>

@endpush --}}
@push('script-lib')
    <script  src="{{ asset('assets/admin/js/pagination.js') }}"></script>
@endpush

@push('style')
    <style>
          .pagination-container {
            display: flex;
            justify-content: center;
            /* margin-top: 20px; */
        }

        .pagination-container button {
            background-color: #4634ff;
            color: white;
            border: 1px solid #ddd;
            padding: 4px 10px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .pagination-container button:hover {
            background-color: #4634ff;
            transform: scale(1.05);
        }

        .pagination-container button:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        .pagination-container button.active {
            background-color: #4634ff;
            border-color: #4634ff;
        }
          .pagination-container button:hover {
            background-color: #4634ff;
            transform: scale(1.05);
        }

        .pagination-container button:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        .pagination-container button.active {
            background-color: #4634ff;
            border-color: #4634ff;
        }

        /* .pagination-container button:first-child {
            border-radius: 5px 0 0 5px;
        }

        .pagination-container button:last-child {
            border-radius: 0 5px 5px 0;
        } */
        .upload-box {
            width: 100%;
            height: 200px;
            border: 2px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            position: relative;
            margin: 20px auto;
            transition: border-color 0.3s ease;
            overflow: hidden;
        }

        .upload-box:hover {
            border-color: #999;
        }

        .upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 16px;
            color: #666;
            z-index: 2;
        }

        .upload-label i {
            font-size: 40px;
            margin-bottom: 10px;
            color: #666;
        }

        .upload-box input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
            /* Đảm bảo input file nằm trên cùng để có thể click vào */
        }

        .upload-box span {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            text-align: center;
        }

        .preview-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }

        .modal {
            z-index: 1070;
            /* Giá trị mặc định cho Bootstrap */
        }

        .select2-container {
            z-index: 1080;
            /* Đảm bảo select2 hiển thị trên modal */
        }
        .background-primary {
            background: #0b138d;
        }

        .background-yellow {
            background-color: #eeddaa;
        }

        .background-white {
            color: #5b6e88;
            background-color: #f0f1f1;
        }

        .background-yellow td,
        .background-primary td {
            color: white !important;
        }
        #data-table {
        border-collapse: collapse; /* Gộp viền bảng */
    }
        #data-table td, #data-table th {
            line-height: 1 !important;
        }
        #data-table td {
            height: 30px !important;
            overflow: hidden;
            white-space: nowrap; /* Ngăn xuống dòng để giảm chiều cao */
        }


        #data-table td, #data-table th {
            padding: 6px !important; /* Giảm padding để thu nhỏ chiều cao */
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        var url = "{{ route('admin.manage.room.status.history') }}";
        var showRoomUrl = "{{ route('admin.booking.showRoom') }}";
        function formatCurrency(amount) {
            const parts = amount.toString().split('.');
            const integerPart = parts[0];
            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return formattedInteger + ' VND';
        }
        $(document).ready(function () {

            $("#room_number, #status_room").change(function () {
                let roomId = $("#room_number").val();
                let statusId = $("#status_room").val();
                $.ajax({
                    url: url, // Route Laravel
                    type: "GET",
                    data: {
                        room_id: roomId,
                        status_id: statusId
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            $('.data-table').empty();
                            loadingData()
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Lỗi khi gửi AJAX:", error);
                    }
                });
            });

            function showRoom(data = "", checkInDateValue = "", checkOutDateValue = "", selectedOptionHangPhong = "",
                selectedOptionNamePhong = "", selectedOptionStatusPhong = "") {
                $('#loading').show();
                $('[id="date-chon-phong-in"]').val(checkInDateValue);
                $('[id="date-chon-phong-out"]').val(checkOutDateValue);
                $.ajax({
                    url: showRoomUrl,
                    type: 'POST',
                    data: {
                        roomIds: data,
                        checkInDate: checkInDateValue,
                        checkOutDate: checkOutDateValue,
                        optionHangPhong: selectedOptionHangPhong,
                        optionNamePhong: selectedOptionNamePhong,
                        optionStatusPhong: selectedOptionStatusPhong,
                    },
                    success: function(data) {
                        // <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p>
                        var tbody = $('#show-room');

                        const dataNew = Object.values(data.data);
                        let seenRooms = new Set();
                        tbody.empty();
                        dataNew.forEach(function(item) {
                            let rowClass = '';
                            let isFirst = !seenRooms.has(item.room_number);
                            seenRooms.add(item.room_number);
                            // Nếu không phải bản ghi đầu tiên, đặt class theo trạng thái
                            if (!isFirst) {
                                if (item.check_booked === 'Đã nhận') {
                                    rowClass = "background-primary";
                                } else if (item.check_booked === 'Đã đặt') {
                                    rowClass = 'background-yellow';
                                }
                            } else {
                                if (item.check_booked === 'Đã nhận') {
                                    rowClass = "background-primary";
                                } else if (item.check_booked === 'Đã đặt') {
                                    rowClass = 'background-yellow';
                                } else {
                                    rowClass = "background-white";
                                }
                            }
                            // <td>
                            //                 <input type="checkbox" ${item.status == 1 ? 'disabled' : ''} data-date="${ item.date }" data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" id="checkbox-${item.id}">
                            //             </td>
                            var tr = `
                                    <tr class="${rowClass}">
                                        <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ item.room_type['name'] } </td>
                                        <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ item.room_number } </td>
                                        <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ formatDate(item.date) } </td>
                                        <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ item.check_booked } </td>
                                        <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ formatCurrency(item.room_type.room_type_price['unit_price']) } </td>

                                    </tr>
                                `;
                            tbody.append(tr);

                        });
                        // hạng phòng
                        var selected_hang = $('#selected-hang-phong');
                        selected_hang.empty();
                        let option = `<option value="">Chọn hạng phòng</option>`;
                        data.roomType.forEach(function(item) {
                            if (item.id == data.option_hang_phong) {
                                option += `<option value="${item.id}" selected>${item.name}</option>`;
                            } else {
                                option += `<option value="${item.id}">${item.name}</option>`;
                            }
                        });
                        selected_hang.append(option);

                        // tên phòng
                        var selected_name = $('#selected-name-phong');
                        selected_name.empty();
                        let options = `<option value="">Chọn tên phòng</option>`;


                        data.room.forEach(function(item) {
                            if (item.id == data.option_name_phong) {
                                options +=
                                    `<option value="${item.id}" selected>${item.room_number}</option>`;
                            } else {
                                options += `<option value="${item.id}">${item.room_number}</option>`;
                            }
                        });

                        selected_name.append(options);
                        // trạng thái phòng
                        // var selected_status = $('#status-room');
                        // selected_status.empty();
                        // let status = ``;
                        // if(data.option_status_phong === null){
                        //      status += `<option value="">Chọn trạng thái phòng</option>`;
                        // }else{
                        //      status += `<option value="${data.option_status_phong}">${data.option_status_phong}</option>`;
                        // }
                        // selected_status.append(status);
                        $('#loading').hide();
                    },
                    error: function(error) {
                        $('#loading').hide();
                        console.log('Error:', error);
                    }
                });
            }

            $('#selected-name-phong, #selected-hang-phong, #date-chon-phong-out, #date-chon-phong-in, #status-room').on(
                'change',
                function() {
                    var selectedOptionHangPhong = $('#selected-hang-phong').val();
                    var selectedOptionNamePhong = $('#selected-name-phong').val();
                    var selectedOptionStatusPhong = $('#status-room').val();
                    const roomIds = [];
                    $('#list-booking tr').each(function() {
                        const roomId = $(this).attr('data-room-id');

                        if (roomId) {
                            roomIds.push(roomId);
                        }
                    });
                    const checkInDateValue = $('#date-chon-phong-in').val();
                    const checkOutDateValue = $('#date-chon-phong-out').val();
                    showRoom(roomIds, checkInDateValue, checkOutDateValue, selectedOptionHangPhong, selectedOptionNamePhong,
                        selectedOptionStatusPhong)
            });
            const date_booking = new Date();
            const date_yyyy = date_booking.getFullYear();
            const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
            const date_dd = String(date_booking.getDate()).padStart(2, '0');
            const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
            const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

            const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
            const formattedTimes = `${date_hour}:${date_minutes}`;

            console.log(formattedDates);
            let date = new Date(formattedDates);
                    date.setDate(date.getDate() + 1);
            // $('[id="date-chon-phong-in"]').val(formattedDates);
            // // $('[id="date-chon-phong-out"]').val(formattedTimes);
            showRoom('', formattedDates, date.toISOString().split('T')[0], '' );

        });
        function formatDate(inputDate) {
    // Chia chuỗi ngày thành các phần tử: năm, tháng, ngày
    var parts = inputDate.split('-');

    // Định dạng lại chuỗi ngày
    var formattedDate = parts[2] + '/' + parts[1] + '/' + parts[0];

    return formattedDate;
}
        function loadingData(page = 1) {
            let roomId = $("#room_number").val();
            let statusId = $("#status_room").val();
                $.ajax({
                    url: url, // Route Laravel
                    type: "GET",
                    data: {
                        room_id: roomId,
                        status_id: statusId,
                        page:page,
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            let data = response.data;
                            var pagination = response.pagination;
                            let html = '';
                            $('.data-table').empty();
                            data.forEach((item, index) => {
                                html += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.room_name}</td>
                                        <td>${formatDate(item.start_date)}</td>
                                        <td>${formatDate(item.end_date)}</td>
                                        <td>${item.status_name}</td>
                                        <td>${item.unit_code}</td>
                                    </tr>
                                `;
                            });
                            $('.data-table').append(html);
                            updatePagination(pagination, 'loadingData');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Lỗi khi gửi AJAX:", error);
                    }
                });

        }
        $(document).ready(function() {
            loadingData()
        });
        $('.select2-multi-select').select2({
            placeholder: "Select options",
            // tags: false,
            tokenSeparators: [','],
            // dropdownParent: $('.append-item')
        })
        //Chuyển mọi ký tự trong input room_id thành uppercase
        $(document).ready(function() {
            $('input[name="room_id"]').on('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

        $(document).on('click', '.addItem', function() {
            var modal = $(this).parents('.modal');
            var div = modal.find('.append-item');
            div.append(`
                    <div class="form-group">
                        <div class="d-flex">
                            <div class="input-group row gx-0">
                                <input type="text" class="form-control" name=room_numbers[]" required>
                            </div>
                            <button type="button" class="btn btn--danger input-group-text border-0 removeRoomBtn flex-shrink-0 ms-4"><i class="las la-times me-0"></i></button>
                        </div>
                    </div>
                    `);
            div.removeClass('d-none');
        });


        $('.editBtn').on('click', function() {
            let modal = $('#editModal');
            let resource = $(this).data('resource');
            console.log(resource);

            let route = `{{ route('admin.hotel.room.update', '') }}/${resource.id}`;
            modal.find('form').attr('action', route);
            modal.find('[name=code]').val(resource.code);
            modal.find('[name=name]').val(resource.name);

            // Hiển thị hình ảnh cũ nếu có
            let showImage = modal.find('#showImage');
            showImage.attr('src', 'http://quanlykhachsan.test/storage/' + resource.main_image);
            showImage.show();

            // Đặt lại file input
            $('#edit_main_image').val(''); // Đặt lại file input cho modal chỉnh sửa

            modal.modal('show');
        });

        // Sự kiện để hiển thị ảnh mới khi người dùng chọn cho modal chỉnh sửa
        $('#edit_main_image').on('change', function(event) {
            const input = event.target;
            const showImage = document.getElementById('showImage');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    showImage.src = e.target.result; // Gán src cho img
                    showImage.style.display = 'block'; // Hiện ảnh mới
                };
                reader.readAsDataURL(input.files[0]); // Đọc file dưới dạng URL base64
            } else {
                showImage.style.display = 'none'; // Ẩn ảnh nếu không có file
            }
        });

        // Sự kiện để hiển thị ảnh mới khi người dùng chọn cho modal thêm
        $('#add_main_image').on('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('add_preview');
            const labelIcon = document.querySelector('#addModal .upload-label i'); // Icon tải lên
            const labelText = document.querySelector('#addModal .upload-label span'); // Text tải lên

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    labelIcon.style.display = 'none'; // Ẩn icon
                    labelText.style.display = 'none'; // Ẩn văn bản
                };

                reader.readAsDataURL(input.files[0]); // Đọc file dưới dạng URL base64
            } else {
                // Nếu không có tệp nào được chọn, đặt lại preview
                preview.src = '';
                preview.style.display = 'none';
                labelIcon.style.display = 'block'; // Hiện icon
                labelText.style.display = 'block'; // Hiện văn bản
            }
        });

        $(document).on('click', '.removeRoomBtn', function() {
            $(this).parents('.form-group').remove();
        });

        $('#editModal').on('shown.bs.modal', function(e) {
            $(document).off('focusin.modal');
        });

        $('#addModal').on('shown.bs.modal', function(e) {
            $(document).off('focusin.modal');
        });
        $('#addModal').on('hidden.bs.modal', function(e) {
            $(this).find('.append-item').html('');
        });

        $('#main_image').on('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const labelIcon = document.querySelector('.upload-label i'); // Icon tải lên
            const labelText = document.querySelector('.upload-label span'); // Text tải lên

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    labelIcon.style.display = 'none'; // Ẩn icon
                    labelText.style.display = 'none'; // Ẩn văn bản
                };

                reader.readAsDataURL(input.files[0]); // Đọc file dưới dạng URL base64
            } else {
                // Nếu không có tệp nào được chọn, đặt lại preview
                preview.src = '';
                preview.style.display = 'none';
                labelIcon.style.display = 'block'; // Hiện icon
                labelText.style.display = 'block'; // Hiện văn bản
            }
        });
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Xóa loại phòng',
                text: 'Bạn có chắc chắn không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Huỷ'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.hotel.room.delete', ':id') }}"
                            .replace(':id', id),
                        success: function(response) {
                            if (response.status === 'success') {
                                notify('success', response.message);
                                location.reload();
                            } else {

                            }
                        }
                    });
                }
            })
        })
    </script>

    <script>
        function handleSearchClear() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput.value === '') {
                window.location.href = '{{ route('admin.manage.room.status') }}';
            }
        }
    </script>
@endpush

@push('style')
    <style>
        @media (max-width: 768px) {
            #searchForm {
                order: 2;
                width: 100% !important;
                margin-top: 15px !important;
            }

            .breadcrumb-plugins>button {
                order: 1;
                width: 100% !important;
                margin-right: 3rem !important;
                margin-left: 3rem !important;
            }
        }
    </style>
@endpush
