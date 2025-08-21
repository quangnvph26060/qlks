@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="table-responsive--md table-responsive">
            <table class="table table-bordered table--light style--two  table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>Mã loại Phòng</th>
                        <th class="small-column">Mã Giá</th>
                        <th>Thời gian hiệu lực</th>
                        <th>Đơn giá</th>
                        <th>Quá giờ</th>
                        <th>Quá người</th>
                        {{-- <th>Tự tính</th> --}}
                        <th>Mã đơn vị</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="room-table-body">


                </tbody>
            </table>
        </div>


    </div>
    @can('')
        @push('breadcrumb-plugins')
            <div class="gap-2 d-flex">
                <button type="button" class="btn btn--primary btn-reload">
                    <i class="fa fa-repeat p-1"></i>
                </button>
                <button type="button" class="btn btn--primary btn-add">
                    <i class="las la-plus"></i>
                </button>
            </div>
        @endpush
    @endcan
    <div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="pricingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pricingModalLabel">Thêm bảng giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="btn-addPrice-submit" action="{{ route('admin.manage.addPrice') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="row-box col-6">
                                <div class="box">
                                    <label for="dayType" class="form-label">Mã loại phòng</label>
                                    <select class="form-select" id="room_type_id" name="room_type_id">
                                        <option value="">Chọn mã phòng</option>
                                        @foreach ($rooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->code }}</option>
                                        @endforeach

                                    </select>
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="room_type_id_error"></span>
                                </div>
                            </div>
                            <div class="row-box col-6">
                                <div class="box">
                                    <label for="dayType" class="form-label">Mã giá</label>
                                    <select class="form-select" id="setup_pricing_id" name="setup_pricing_id">
                                        <option value="">Chọn mã giá</option>
                                        @foreach ($setupPrice as $item)
                                            <option value="{{ $item->id }}">{{ $item->price_code }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="setup_pricing_id_error"></span>
                                </div>
                            </div>
                            <div class="row-box col-12">
                                <div class="box">
                                    <label for="dayType" class="form-label">Thòi gian hiệu lực</label>
                                    <input type="date" id="price_validity_period" class="form-control "
                                        name="price_validity_period">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="price_validity_period_error"></span>
                                </div>
                            </div>
                            <div class="row-box col-4">
                                <div class="box">
                                    <label for="dayType" class="form-label">Đơn giá</label>
                                    <input type="text" id="unit_price" class="form-control money-input" name="unit_price"
                                        placeholder="Đơn giá">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="unit_price_error"></span>
                                </div>
                            </div>
                            <div class="row-box col-4">
                                <div class="box">
                                    <label for="dayType" class="form-label">Quá giờ</label>
                                    <input type="text" name="overtime" id="overtime" class="form-control money-input"
                                        placeholder="Quá giờ">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="overtime_error"></span>
                                </div>
                            </div>
                            <div class="row-box col-4">
                                <div class="box">
                                    <label for="dayType" class="form-label">Quá người</label>
                                    <input type="text" name="too_many_people" id="too_many_people"
                                        class="form-control money-input" placeholder="Quá người">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="too_many_people_error"></span>
                                </div>
                            </div>
                        </div>



                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-add-price">Lưu</button>
                </div>
            </div>
        </div>

        <div id="loading" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-dasharray="15" stroke-dashoffset="15"
                    stroke-linecap="round" stroke-width="2" d="M12 3C16.9706 3 21 7.02944 21 12">
                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.3s" values="15;0" />
                    <animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite" type="rotate"
                        values="0 12 12;360 12 12" />
                </path>
            </svg>
        </div>
    </div>
@endsection
{{-- @push('style-lib')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush --}}
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/manage-price.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/pagination.css') }}">
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
    <script src="{{ asset('assets/admin/js/common.js') }}"></script>
    <script>
        $(document).ready(function() {
            var formEconomyEdit = {
                'setup_pricing_id': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('setup_pricing_id'), // id trong input đó
                    'error': document.getElementById('setup_pricing_id_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Mã  giá')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'room_type_id': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('room_type_id'), // id trong input đó
                    'error': document.getElementById('room_type_id_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Mã loại phòng')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'unit_price': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('unit_price'), // id trong input đó
                    'error': document.getElementById('unit_price_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Đơn giá')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },

                'overtime': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('overtime'), // id trong input đó
                    'error': document.getElementById('overtime_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Quá giờ')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },

                'too_many_people': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('too_many_people'), // id trong input đó
                    'error': document.getElementById('too_many_people_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Quá người')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'price_validity_period': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('price_validity_period'), // id trong input đó
                    'error': document.getElementById('price_validity_period_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Thời gian hiệu lực')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
            }
            $(document).on('click', '.btn-add-price', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-addPrice-submit').submit();
                    // là id trong form
                }
            });
            // format date function
            function formatDate(date) {
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }
            // định dạng tiền input
            function formatNumber(input) {
                var value = input.value;
                // Loại bỏ tất cả các ký tự không phải số và dấu phân cách
                var numericValue = value.replace(/[^0-9,]/g, '');
                // Loại bỏ tất cả dấu phân cách thừa (nếu có)
                var parts = numericValue.split(',');
                var integerPart = parts[0].replace(/\./g, ''); // Loại bỏ tất cả dấu phân cách ngàn
                // Định dạng số tiền theo định dạng tiền tệ của Việt Nam
                var formattedValue = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                // Nếu có phần thập phân, thêm vào sau số nguyên
                if (parts.length > 1) {
                    formattedValue += ',' + parts[1];
                }
                input.value = formattedValue;
            }

            function formatDate(inputDateTime) {
                var dateTimeParts = inputDateTime.split(' ');
                var dateParts = dateTimeParts[0].split('-');
                var formattedDate = dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0];
                var formattedDateTime = formattedDate;
                return formattedDateTime;
            }

            function formatCurrencyNoVND(amount) {
                if (!amount || isNaN(amount)) {
                    return '0 VND'; // Nếu amount không hợp lệ, trả về 0 VND
                }

                const parts = parseFloat(amount).toFixed(2).toString().split('.');
                const integerPart = parts[0];
                const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                return formattedInteger;
            }

            function listSetupPricing() {
                var url = '{{ route('admin.manage.showRoomTypePrice') }}';
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            var data = response.data;
                            var html = '';

                            data.forEach((element, index)  => {
                                const startDate = new Date(
                                    `${element.setup_pricing['effective_start_date']}`);
                                const endDate = new Date(
                                    `${element.setup_pricing['effective_end_date']}`);


                                html += `
                                    <tr data-id="${element.id}" class="${index % 2 === 0 ? 'bg-white' : 'bg-gray'}">
                                        <td data-label="Mã loại phòng"class="text-left"> ${element.room_type['code']} </td>
                                        <td data-label="Mã giá"class="text-left"> ${element.setup_pricing['price_code']} </td>

                                        <td data-label="Thời gian hiệu lực"class="text-right">
                                            ${formatDate(element.price_validity_period)}
                                        </td>
                                         <td data-label="Đơn giá"class="text-right">
                                            ${formatCurrency(element.unit_price)}
                                        </td >

                                        <td data-label="Quá giờ"class="text-right">
                                            ${formatCurrency(element.overtime_price)}
                                        </td>

                                        <td data-label="Quá người"class="text-right">
                                            ${formatCurrency(element.extra_person_price)}
                                        </td>

                                        <td data-label="Mã đơn vị"class="text-left"> ${ element.unit_code } </td>
                                        <td data-label="Thao tác" class="text-center d-flex main-icon justify-content-end" >
                                            <button class="btn btn-sm btn-outline--danger btn-delete icon-delete-room" style="    padding: 5px 8px;"
                                                data-id="${element.id}" data-modal_title="@lang('Xóa cài đặt tính giá ')"type="button"
                                                data-pro="0">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                              <button class="btn btn-sm btn-outline--primary edit-price "
                                                data-id="${element.id}" data-modal_title="@lang('Xóa cài đặt tính giá ')"type="button"
                                                data-pro="0">
                                               <i class="fas fa-edit"></i>

                                            </button>
                                        </td>
                                    </tr>
                                `;

                            });


                            $('#room-table-body').html(html);

                            AutoNumeric.multiple(".moneyInput", {
                                decimalCharacter: ",", // Ký tự phân cách phần thập phân
                                digitGroupSeparator: ".", // Ký tự phân cách phần ngàn
                                decimalPlaces: 0, // Không có chữ số thập phân
                                minimumValue: "0", // Giá trị tối thiểu
                                maximumValue: "1000000000" // Giá trị tối đa
                            });
                        }

                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                    }
                });
            }
            listSetupPricing()
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
                    },
                    customClass: {
                        container: "custom-toast", // Áp dụng lớp CSS tùy chỉnh
                    },
                });
                Toast.fire({
                    icon: icon,
                    title: `<p>${title}</p>`,
                });
            };

            // xóa
            $(document).on('click', '.icon-delete-room', function() {
                var dataId = $(this).data('id');
                var rowToDelete = $(`tr[data-id="${dataId}"]`);
                Swal.fire({
                    title: 'Xác nhận xóa  giá loại phòng này không?',
                    text: 'Bạn có chắc chắn muốn xóa giá loại phòng này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.manage.deleteRoomTypePrice', '') }}/${dataId}`,
                            type: 'POST',
                            success: function(data) {
                                if (data.status === 'success') {
                                    rowToDelete.remove();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                            }
                        });


                    }
                });
            });
            $(document).on('click', '.btn-add', function() {
                let newAction = "{{ route('admin.manage.addPrice') }}"; // Định dạng URL mới
                $('#btn-addPrice-submit').attr('action', newAction);
                $('#pricingModal').modal('show');
            });
            $(document).on('click', '.btn-reload', function() {
                window.location.reload();
            });
            // sửa
            $(document).on('click', '.edit-price', function() {
                let id = $(this).data('id');
                let baseUrl = "{{ route('admin.manage.updateRoomTypePrice', '') }}";

                let newAction = baseUrl + '/' + id;
                $('#btn-addPrice-submit').attr('action', newAction);
                $('#pricingModal').modal('show');
                $.ajax({
                    url: '{{ route('admin.manage.findRooomType') }}',
                    method: 'POST',
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            var data = response.data;
                            console.log(data.room_type['id']);

                            $('#unit_price').val(formatCurrencyNoVND(data.unit_price));
                            $('#overtime').val(formatCurrencyNoVND(data.overtime_price));
                            $('#too_many_people').val(formatCurrencyNoVND(data
                                .extra_person_price));
                            $('#price_validity_period').val(data.price_validity_period);
                            $("#setup_pricing_id").val(data.setup_pricing['id']).change();
                            $("#room_type_id").val(data.room_type['id']).change();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi gửi dữ liệu:', error);
                    }
                });

            });
        });
    </script>

    <style scoped>
        .main-icon {
            justify-content: center;
            align-items: center;
            gap: 10px
        }

        .checkbox-list,
        .checkbox-list1 {
            display: none;
        }

        input.form-control,
        select.form-control {
            height: 30px !important;
        }

        .overnightPrice {
            display: none;
        }
    </style>
@endpush
