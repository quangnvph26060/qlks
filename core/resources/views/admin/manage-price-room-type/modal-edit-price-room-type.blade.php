<div class="modal-content" style="width: 1200px;">
    <div class="modal-header">
        <h5 class="modal-title" id="pricingModalLabel">Cập nhật cài đặt tính giá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form id="btn-setupPrice-submit-edit"
            action="{{ route('admin.manage.editPriceRoomType', ['id' => $pricing->id]) }}" method="POST">
            @csrf
            <div class="row">
                <div class="row-box col-6">
                    <div class="box">
                        <label for="priceCode" class="form-label">Mã bảng giá</label>
                        <input type="text" value="{{ old('price_code', $pricing->price_code) }}" class="form-control"
                            id="priceCode" placeholder="Nhập mã bảng giá" name="price_code">
                        <span class="invalid-feedback d-block" style="font-weight: 500" id="priceCode_error"></span>
                    </div>

                    <div class="box">
                        <label for="priceName" class="form-label">Tên bảng giá</label>
                        <input type="text" value="{{ old('price_name', $pricing->price_name) }}" class="form-control"
                            id="priceName" name="price_name" placeholder="Nhập tên bảng giá">
                        <span class="invalid-feedback d-block" style="font-weight: 500" id="priceName_error"></span>
                    </div>

                    <div class="box">
                        <label for="priceNote" class="form-label">Ghi chú</label>
                        <input type="text" class="form-control"
                            value="{{ old('description', $pricing->description) }}" id="priceNote" name="description"
                            placeholder="Nhập tên bảng giá">
                        <span class="invalid-feedback d-block" style="font-weight: 500" id="priceNote_error"></span>
                    </div>
                </div>
                <div class="row-box col-6">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="startDate" class="form-label">Thời gian nhận</label>
                            <input type="time" class="form-control"
                                value="{{ old('check_in_time', $pricing->check_in_time) }}" id="checkInTime"
                                name="check_in_time">
                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                id="checkInTime_error"></span>
                        </div>
                        <div class="col-lg-6">
                            <label for="endDate" class="form-label">Thời gian trả</label>
                            <input type="time" class="form-control"
                                value="{{ old('check_out_time', $pricing->check_out_time) }}" id="checkOutTime"
                                name="check_out_time">
                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                id="checkOutTime_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="box">
                            <label for="roundTime" class="form-label">Làm tròn thời gian</label>
                            <input type="text" class="form-control" id="roundTime" name="round_time"
                                value="{{ old('round_time', $pricing->round_time) }}"
                                placeholder="Nhập số phút làm tròn">
                            <span class="invalid-feedback d-block" style="font-weight: 500" id="roundTime_error"></span>
                        </div>
                    </div>


                    <div class="box">
                        <label for="dayType" class="form-label">Hình thức</label>
                        <select class="form-select" id="dayType">
                            <option value="">Chọn hình thức</option>
                            <option value="rank">Thứ</option>
                            <option value="holiday">Ngày</option>
                            <option value="time">Giờ</option>
                        </select>
                        <span class="invalid-feedback d-block" style="font-weight: 500" id="dayType_error"></span>
                    </div>
                    <div class="box mt-3">
                        <div id="checkboxContainer" class="checkbox-list">

                            @php
                                $selectedValues = json_decode($pricing->price_requirement);
                            @endphp

                            <div class="input_rank">
                                <label class="form-label">Chọn thứ:</label>
                                <div class="checkbox-group" style="display: flex; gap: 10px">

                                    <div>
                                        <input type="checkbox" id="monday" class="form-check-input"
                                            name="price_requirement[]" value="2">
                                        <label for="monday">Thứ 2</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="tuesday" class="form-check-input"
                                            name="price_requirement[]" value="3">
                                        <label for="tuesday">Thứ 3</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="wednesday" class="form-check-input"
                                            name="price_requirement[]" value="4">
                                        <label for="wednesday">Thứ 4</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="thursday" class="form-check-input"
                                            name="price_requirement[]" value="5">
                                        <label for="thursday">Thứ 5</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="friday" class="form-check-input"
                                            name="price_requirement[]" value="6">
                                        <label for="friday">Thứ 6</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="saturday" class="form-check-input"
                                            name="price_requirement[]" value="7">
                                        <label for="saturday">Thứ 7</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="sunday" class="form-check-input"
                                            name="price_requirement[]" value="8">
                                        <label for="sunday">Chủ Nhật</label>
                                    </div>
                                </div>
                            </div>

                            <div class="input_hodiday">
                                <label for="selectedDates" class="form-label">Chọn ngày:</label>
                                <input type="text" id="selectedDates" class="form-group abc "
                                    placeholder="Chọn ngày" name="price_requirement[]"
                                    style="width: 100%; height: 35px;">
                            </div>

                            <input type="text" name="price_requirement[]"
                                class="form-control price_requirement_value">

                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-success btn-setupPrice-edit">Cập nhật</button>
    </div>
</div>
<script>
    $(document).ready(function() {
        var selectedValues = @json($selectedValues);
        // check type number
        if (typeof(selectedValues) === 'number') {
            $('.price_requirement_value').val(selectedValues);
            $('#dayType').val('time');
            $('.input_rank').hide();
            $('.input_hodiday').hide();
        }

        if (typeof(selectedValues) === 'object') {
            selectedValues.forEach(function(value) {
                if (/^\d{4}-\d{2}-\d{2}(?:,\s*\d{4}-\d{2}-\d{2})*$/.test(value) ) {  
                    $('.abc').val(value);
                    $('.input_rank').hide(); 
                    $('#dayType').val('holiday');
                    $('.price_requirement_value').hide();
                  
                    flatpickr("#selectedDates", {
                        mode: "multiple",
                        dateFormat: "Y-m-d",
                    });
                } else {
                    $('.input_hodiday').hide(); 
                    $('#dayType').val('rank'); 
                    $('.price_requirement_value').hide();
                    var input = document.querySelector('input[value="' + value + '"]');
                    if (input) {
                        input.checked = true;
                    }
                }
            });
        }
    });
</script>
