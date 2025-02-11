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
                        <th>Mã giá</th>
                        <th>Tên giá</th>
                        <th class="small-column">Giá trị</th>
                        {{-- <th>Thời gian hiệu lực</th> --}}
                        <th>Thời gian nhận / trả</th>
                        <th>Làm tròn</th>
                        <th>Mô tả</th>
                        <th>Mã đơn vị</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="table-setup-price">

                </tbody>
            </table>
        </div>

        {{-- <button class="btn btn-primary" onclick="addRoomPrices()">Thêm Giá</button> --}}

        {{-- <button class="btn btn-success" id="addColumnBtn" data-toggle="modal" data-target="#addDayModal">Thêm
            Cột</button> --}}

    </div>


    <!-- Modal -->
    <div class="modal fade" id="addDayModal" tabindex="-1" role="dialog" aria-labelledby="addDayModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDayModalLabel">Chọn Ngày</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="dateSelectionForm" class="d-flex flex-wrap">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Monday" id="dayMonday">
                            <label class="form-check-label" for="dayMonday">Thứ Hai</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Tuesday" id="dayTuesday">
                            <label class="form-check-label" for="dayTuesday">Thứ Ba</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Wednesday" id="dayWednesday">
                            <label class="form-check-label" for="dayWednesday">Thứ Tư</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Thursday" id="dayThursday">
                            <label class="form-check-label" for="dayThursday">Thứ Năm</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Friday" id="dayFriday">
                            <label class="form-check-label" for="dayFriday">Thứ Sáu</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Saturday" id="daySaturday">
                            <label class="form-check-label" for="daySaturday">Thứ Bảy</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Sunday" id="daySunday">
                            <label class="form-check-label" for="daySunday">Chủ Nhật</label>
                        </div>

                    </form>
                    <div>
                        <p>Ngày</p> <input type="text" id="selectedDates" class="form-group" placeholder="Chọn ngày"
                            style="width: 100%;">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="addDayColumn">Thêm Cột Ngày</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @can('')
        @push('breadcrumb-plugins')
            <button type="button" class="btn btn-outline--primary btn-add">
                <i class="las la-plus"></i>
            </button>
        @endpush
    @endcan
    <div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="pricingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg add-pricing" style="margin-left: 380px">

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
    {{-- <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#selectedDates", {
            mode: "multiple",
            dateFormat: "Y-m-d",
        });
        flatpickr("#modalDateValue", {
            mode: "multiple",
            dateFormat: "Y-m-d",
        });


        // click modal btn-add
        $(document).ready(function() {
            var dayTypeElement = document.getElementById('dayType');
            if (dayTypeElement) {
                dayTypeElement.addEventListener('change', function() {
                    var selectedValue = this.value;
                    var checkboxList = document.getElementById('checkboxContainer'); // ngày lễ
                    var priceRequirementInput = document.querySelector('.price_requirement_value');

                    if (selectedValue === 'holiday') {
                        checkboxList.innerHTML = `
                        <label class="form-label">Chọn thứ:</label>
                        <div class="checkbox-group" style="display: flex; gap: 10px">
                            <div>
                                <input type="checkbox" id="monday" class="form-check-input" name="price_requirement[]" value="2">
                                <label for="monday">Thứ 2</label>
                            </div>
                            <div>
                                <input type="checkbox" id="tuesday" class="form-check-input" name="price_requirement[]" value="3">
                                <label for="tuesday">Thứ 3</label>
                            </div>
                            <div>
                                <input type="checkbox" id="wednesday" class="form-check-input" name="price_requirement[]" value="4">
                                <label for="wednesday">Thứ 4</label>
                            </div>
                            <div>
                                <input type="checkbox" id="thursday" class="form-check-input" name="price_requirement[]" value="5">
                                <label for="thursday">Thứ 5</label>
                            </div>
                            <div>
                                <input type="checkbox" id="friday" class="form-check-input" name="price_requirement[]" value="6">
                                <label for="friday">Thứ 6</label>
                            </div>
                            <div>
                                <input type="checkbox" id="saturday" class="form-check-input" name="price_requirement[]" value="7">
                                <label for="saturday">Thứ 7</label>
                            </div>
                            <div>
                                <input type="checkbox" id="sunday" class="form-check-input" name="price_requirement[]" value="8">
                                <label for="sunday">Chủ Nhật</label>
                            </div>
                        </div>
                        <label for="selectedDates" class="form-label">Chọn ngày:</label>
                        <input type="text" id="selectedDates" class="form-group" placeholder="Chọn ngày" name="price_requirement[]" style="width: 100%; height: 35px;">
                    `;
                    } else if (selectedValue === 'time') { // giờ
                        checkboxList.innerHTML = `
                        <input type="text" hidden name="price_requirement" class="price_requirement_value" value="time">
                    `;


                    } else { // ngày thường
                        checkboxList.innerHTML = `
                            <input type="text" hidden name="price_requirement" class="price_requirement_value" value="weekday">
                        `;
                    }
                })
            }
            $(document).on('change', '#dayType', function() {
                var selectedValue = $(this).val();
                var checkboxList = document.getElementById('checkboxContainer');
                var priceRequirementInput = document.querySelector('.price_requirement_value');
                price_requirement_value(selectedValue, checkboxList, priceRequirementInput)
            });

          //  price_requirement_value(selectedValue, checkboxList, priceRequirementInput)
            function price_requirement_value(selectedValue, checkboxList, priceRequirementInput){
                if (selectedValue === 'holiday') {
                    checkboxList.innerHTML = `

                        <label for="selectedDates" class="form-label">Chọn ngày:</label>
                        <input type="text" id="selectedDates" class="form-group" placeholder="Chọn ngày" name="price_requirement[]" style="width: 100%; height: 35px;">
                    `;
                    flatpickr("#selectedDates", {
                        mode: "multiple",
                        dateFormat: "Y-m-d",
                    });
                } else if (selectedValue === 'time') { // giờ
                    checkboxList.innerHTML = `
                        <input type="text"  name="price_requirement" class="form-control price_requirement_value" placeholder="Nhập giờ">
                    `;
                } else if (selectedValue === 'rank') { // ngày thường
                    checkboxList.innerHTML = `
                             <label class="form-label">Chọn thứ:</label>
                        <div class="checkbox-group" style="display: flex; gap: 10px">
                            <div>
                                <input type="checkbox" id="monday" class="form-check-input" name="price_requirement[]" value="2">
                                <label for="monday">Thứ 2</label>
                            </div>
                            <div>
                                <input type="checkbox" id="tuesday" class="form-check-input" name="price_requirement[]" value="3">
                                <label for="tuesday">Thứ 3</label>
                            </div>
                            <div>
                                <input type="checkbox" id="wednesday" class="form-check-input" name="price_requirement[]" value="4">
                                <label for="wednesday">Thứ 4</label>
                            </div>
                            <div>
                                <input type="checkbox" id="thursday" class="form-check-input" name="price_requirement[]" value="5">
                                <label for="thursday">Thứ 5</label>
                            </div>
                            <div>
                                <input type="checkbox" id="friday" class="form-check-input" name="price_requirement[]" value="6">
                                <label for="friday">Thứ 6</label>
                            </div>
                            <div>
                                <input type="checkbox" id="saturday" class="form-check-input" name="price_requirement[]" value="7">
                                <label for="saturday">Thứ 7</label>
                            </div>
                            <div>
                                <input type="checkbox" id="sunday" class="form-check-input" name="price_requirement[]" value="8">
                                <label for="sunday">Chủ Nhật</label>
                            </div>
                        </div>
                        `;
                }
            }
            var formEconomyEdit = {
                'priceCode': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('priceCode'), // id trong input đó
                    'error': document.getElementById('priceCode_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Mã bảng giá')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'priceName': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('priceName'), // id trong input đó
                    'error': document.getElementById('priceName_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Tên bảng giá ')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'priceNote': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('priceNote'), // id trong input đó
                    'error': document.getElementById('priceNote_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Ghi Chú')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'checkInTime': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('checkInTime'), // id trong input đó
                    'error': document.getElementById('checkInTime_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Thời gian nhận')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'checkOutTime': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('checkOutTime'), // id trong input đó
                    'error': document.getElementById('checkOutTime_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Thời gian từ')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'dayType': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('dayType'), // id trong input đó
                    'error': document.getElementById('dayType_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Hình thức')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'roundTime': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('roundTime'), // id trong input đó
                    'error': document.getElementById('roundTime_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Làm tròn thời gian')
                        },
                        {
                            'func': function(value) {
                                return checkInteger(value); // check số
                            },
                            'message': generateErrorMessage('P002', 'Làm tròn thời gian')
                        },
                    ]
                },
            }

            // function validateDate() {
            //     var startDateValue = new Date($('#startDate').val());
            //     var endDateValue = new Date($('#endDate').val());
            //     var errorSpan = $('#endDate_error');
            //     var endDateInput = $('#endDate');

            //     if (startDateValue > endDateValue) {
            //         errorSpan.text('Ngày kết thúc phải lớn hơn ngày bắt đầu.');
            //         errorSpan.addClass('text-danger');
            //         endDateInput.addClass('is-invalid');
            //         return false;
            //     } else {
            //         errorSpan.text('');
            //         errorSpan.removeClass('text-danger');
            //         endDateInput.removeClass('is-invalid');
            //         return true;
            //     }
            // }
            // $(document).on('change', function() {
            //     validateDate();
            // });
            // btn-setupPrice
            $(document).on('click', '.btn-setupPrice', function() {
                if ( validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-setupPrice-submit').submit();
                }
            });
             // btn-setupPrice
             $(document).on('click', '.btn-setupPrice-edit', function() {
                if ( validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-setupPrice-submit-edit').submit();
                }
            });
            // thêm
            $('.btn-add').on('click', function() {
                $('#pricingModal').modal('show');
                $('.add-pricing').empty();
                // ajax get pricing
                $('#loading').show();
                var url = '{{ route('admin.manage.modalAdd') }}';
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $('#loading').hide();
                       $('.add-pricing').append(response.content);
                        formEconomyEdit.priceCode.element = document.getElementById('priceCode');
                        formEconomyEdit.priceCode.error = document.getElementById('priceCode_error');
                        formEconomyEdit.priceName.element = document.getElementById('priceName');
                        formEconomyEdit.priceName.error = document.getElementById('priceName_error');
                        formEconomyEdit.priceNote.element = document.getElementById('priceNote');
                        formEconomyEdit.priceNote.error = document.getElementById('priceNote_error');
                        // formEconomyEdit.startDate.element = document.getElementById('startDate');
                        // formEconomyEdit.startDate.error = document.getElementById('startDate_error');
                        // formEconomyEdit.endDate.element = document.getElementById('endDate');
                        // formEconomyEdit.endDate.error = document.getElementById('endDate_error');
                        formEconomyEdit.checkInTime.element = document.getElementById('checkInTime');
                        formEconomyEdit.checkInTime.error = document.getElementById('checkInTime_error');
                        formEconomyEdit.checkOutTime.element = document.getElementById('checkOutTime');
                        formEconomyEdit.checkOutTime.error = document.getElementById('checkOutTime_error');
                        formEconomyEdit.roundTime.element = document.getElementById('roundTime');
                        formEconomyEdit.roundTime.error = document.getElementById('roundTime_error');
                        formEconomyEdit.dayType.element = document.getElementById('dayType');
                        formEconomyEdit.dayType.error = document.getElementById('dayType_error');
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        console.log('Error:', error);
                    }
                });


            });

            // sửa
            $(document).on('click', '.btn-edit-setup-pricing', function() {

                var dataId = $(this).data('id');
                $('#pricingModal').modal('show');
                $('.add-pricing').empty();
                $('#loading').show();
                $.ajax({
                    url: `{{ route('admin.manage.modalEdit', '') }}/${dataId}`,
                    type: 'POST',
                    success: function(data) {
                        $('#loading').hide();
                        $('.add-pricing').append(data.content);
                        formEconomyEdit.priceCode.element = document.getElementById('priceCode');
                        formEconomyEdit.priceCode.error = document.getElementById('priceCode_error');
                        formEconomyEdit.priceName.element = document.getElementById('priceName');
                        formEconomyEdit.priceName.error = document.getElementById('priceName_error');
                        formEconomyEdit.priceNote.element = document.getElementById('priceNote');
                        formEconomyEdit.priceNote.error = document.getElementById('priceNote_error');
                        // formEconomyEdit.startDate.element = document.getElementById('startDate');
                        // formEconomyEdit.startDate.error = document.getElementById('startDate_error');
                        // formEconomyEdit.endDate.element = document.getElementById('endDate');
                        // formEconomyEdit.endDate.error = document.getElementById('endDate_error');
                        formEconomyEdit.checkInTime.element = document.getElementById('checkInTime');
                        formEconomyEdit.checkInTime.error = document.getElementById('checkInTime_error');
                        formEconomyEdit.checkOutTime.element = document.getElementById('checkOutTime');
                        formEconomyEdit.checkOutTime.error = document.getElementById('checkOutTime_error');
                        formEconomyEdit.roundTime.element = document.getElementById('roundTime');
                        formEconomyEdit.roundTime.error = document.getElementById('roundTime_error');
                        formEconomyEdit.dayType.element = document.getElementById('dayType');
                        formEconomyEdit.dayType.error = document.getElementById('dayType_error');

                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        console.log(xhr.responseText);
                    }
                });

            });
            // xóa
            $(document).on('click', '.icon-delete-room', function() {
                var dataId = $(this).data('id');
                var rowToDelete = $(`tr[data-id="${dataId}"]`);
                Swal.fire({
                    title: 'Xác nhận xóa cơ sở?',
                    text: 'Bạn có chắc chắn muốn xóa cơ sở này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.manage.deletePriceRoomType', '') }}/${dataId}`,
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
        // format date function
        function formatDate(date) {
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function formatDate1(dateString) {
            const dates = dateString.split(',').map(dateStr => {
                const date = new Date(dateStr.trim());
                return date.toLocaleDateString('en-US');
            });
            return dates;
        }

        // Hàm chuyển đổi chuỗi số thành thứ trong tuần
        function formatDayNumber(dayNumberString) {
            const dayNames = ['Chủ Nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
            const dayNumbers = dayNumberString.split(',').map(numStr => {
                const num = parseInt(numStr.trim());
                return dayNames[num];
            });
            return dayNumbers;
        }
        // ajax request
        function listSetupPricing() {
            var url = '{{ route('admin.manage.setupPriceRoomType') }}';
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        var html = '';

                        data.forEach(element => {
                            // Biến chứa dữ liệu cần định dạng
                            const priceRequirementData = element.price_requirement;
                            let formattedData;
                            const isArrayString = /^\[.*\]$/.test(priceRequirementData);
                            if (isArrayString) {
                                let dataArray = JSON.parse(priceRequirementData);


                                formattedData = priceRequirementData;


                                formattedData = dataArray.map(item => {
                                    if (item.includes('-')) {
                                        // Xử lý định dạng cho ngày tháng
                                        const dates = item.split(',').map(dateStr => {
                                            const date = new Date(dateStr.trim());
                                            return date.toLocaleDateString('vi-VN');
                                        });
                                        return dates.join(', ');
                                    } else {
                                        // Xử lý định dạng cho thứ trong tuần
                                        const dayNames = [1, 1, 'Thứ 2', 'Thứ 3', 'Thứ 4',
                                            'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ Nhật'
                                        ];
                                        const num = parseInt(item.trim());
                                        return dayNames[num];
                                    }
                                }).join(', ');

                            } else {
                                formattedData = priceRequirementData;
                            }

                            // const startDate = new Date(`${element.effective_start_date}`);
                            // const endDate = new Date(`${element.effective_end_date}`);
                            // <td>${formatDate(startDate)} - ${ formatDate(endDate) 	}</td>
                            html += `
                                <tr data-id="${element.id}">
                                    <td>${element.price_code}</td>
                                    <td>${element.price_name}</td>
                                    <td>${formattedData}</td>

                                    <td>${element.check_in_time} / ${element.check_out_time}</td>
                                    <td>${element.round_time	}</td>
                                    <td>${element.description	}</td>

                                    <td>${element.unit_code	}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline--primary btn-edit-setup-pricing"
                                            data-id="${element.id}">
                                            <i class="la la-pencil"></i>
                                        </a>

                                        <button class="btn btn-sm btn-outline--danger btn-delete icon-delete-room"
                                            data-id="${element.id}" data-modal_title="@lang('Xóa cài đặt tính giá ')"type="button"
                                            data-pro="0">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });


                        $('#table-setup-price').html(html);
                    }

                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                }
            });
        }
        listSetupPricing()
    </script>
    <style scoped>
        input.form-control,
        select.form-control {
            height: 30px !important;
        }

        .overnightPrice {
            display: none;
        }
    </style>
@endpush
