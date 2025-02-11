@extends('admin.layouts.app')
@section('panel')
    <div class="bodywrapper__inner">

        <div class="d-flex justify-content-end mb-3">
            {{-- <h4>Thêm Giá Phòng</h4> --}}
            {{-- <div class="d-flex justify-content-center align-items-center" style="gap: 10px;">

                <form action="{{ route('admin.manage.price.all') }}" method="GET" id="search-premium">
                    <div class="input-group flex-nowrap">
                        <input
                            type="search"
                            class="searchInput"
                            name="name"
                            value="{{ $input }}"
                            id="searchInput"
                            value="{{ request('keyword') }}"
                            placeholder="Tìm kiếm ..." style="width:100%">
                        <!-- Nút tìm kiếm -->
                        <button type="submit" class="btn btn-primary" >
                            <i class="las la-search"></i>
                        </button>
                    </div>
                </form>
                <button type="button" class="btn btn-primary main-add-day" id="addColumnBtn" data-toggle="modal"
                    data-target="#addDayModal" style="    white-space: nowrap;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M13 4v7h7v2h-7v7h-2v-7H4v-2h7V4h2Z" />
                    </svg>
                    Thứ,Ngày
                </button>
            </div> --}}
        </div>

        <div class="table-responsive--md table-responsive">
            <table class="table table-bordered table--light style--two table table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>Mã hạng Phòng</th>
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
            <button type="button" class="btn btn-outline--primary btn-add" data-bs-toggle="modal" data-bs-target="#pricingModal">
                <i class="las la-plus"></i>

            </button>
        @endpush
    @endcan
    <div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="pricingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="margin-left: 380px">
            <div class="modal-content" style="width: 1200px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="pricingModalLabel">Thêm giá loại phòng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="btn-addPrice-submit" action="{{ route('admin.manage.addPrice') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="row-box col-6">
                                <div class="box">
                                    <label for="dayType" class="form-label">Mã hạng phòng</label>
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
    @endsection
    @push('style-lib')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/manage-price.css') }}">
    @endpush
    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="{{ asset('assets/validator/validator.js') }}"></script>
        <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
        <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
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
                                'message': generateErrorMessage('P001', 'Mã hạng phòng')
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

                function listSetupPricing() {
                    var url = '{{ route('admin.manage.showRoomTypePrice') }}';
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            if (response.status === 'success') {
                                var data = response.data;
                                var html = '';

                                data.forEach(element => {
                                    const startDate = new Date(
                                        `${element.setup_pricing['effective_start_date']}`);
                                    const endDate = new Date(
                                        `${element.setup_pricing['effective_end_date']}`);


                                    html += `
                                    <tr data-id="${element.id}">
                                        <td> ${element.room_type['code']} </td>
                                        <td> ${element.setup_pricing['price_code']} </td>
                                        <td>  <input type="date" class="form-control moneyInputDate" data-id="${element.id}" data-name="price_validity_period" value="${element.price_validity_period === null ? '' : element.price_validity_period}" name="price_validity_period"> </td>
                                        <td>
                                            <input type="text" class="form-control moneyInput"  placeholder="0" data-id="${element.id}" data-name="unit_price" value="${element.unit_price === null ? '' : element.unit_price}" name="unit_price">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control moneyInput"  placeholder="0" data-id="${element.id}" data-name="overtime_price" value="${element.overtime_price === null ? '' : element.overtime_price}" name="overtime_price">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control moneyInput"  placeholder="0" data-id="${element.id}"  data-name="extra_person_price"  value="${element.extra_person_price === null ? '' : element.extra_person_price }" name="extra_person_price">
                                        </td>

                                        <td> ${ element.unit_code } </td>
                                        <td>


                                            <button class="btn btn-sm btn-outline--danger btn-delete icon-delete-room"
                                                data-id="${element.id}" data-modal_title="@lang('Xóa cài đặt tính giá ')"type="button"
                                                data-pro="0">
                                                <i class="fas fa-trash"></i>
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
                $(document).on('blur', '.moneyInput, .moneyInputDate', function() {
                    // Lấy giá trị và data-id
                    const value = $(this).val();
                    const rawValue = value.replace(/\./g, '');
                    const dataId = $(this).data('id');
                    const dataName = $(this).data('name');

                    $.ajax({
                        url: '{{ route('admin.manage.updateRoomTypePrice') }}', // URL xử lý
                        method: 'POST',
                        data: {
                            id: dataId,
                            value: rawValue,
                            method: dataName
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showSwalMessage('success', response.message)
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Lỗi khi gửi dữ liệu:', error);
                        }
                    });
                });
                 // xóa
                $(document).on('click', '.icon-delete-room', function() {
                    var dataId = $(this).data('id');
                    var rowToDelete = $(`tr[data-id="${dataId}"]`);
                    Swal.fire({
                        title: 'Xác nhận xóa cơ sở?',
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
            });
        </script>

        <style scoped>
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
