@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class=" my-1 ota-settings-container">
                <div class="">
                    <div class="card">

                        <div class="mb-4 p-1">
                            <label for="branch" class="form-label fw-semibold fs-5">Cơ sở: {{ $hotels->ten_coso }}</label>
                            {{-- <select id="branch" name="branch" class="form-select w-50">
                                @foreach ($hotels as $item)
                                    <option value="{{ $item->id }}">{{ $item->ten_coso }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <!-- CASE OPTIONS -->

                        <form action="{{ route('admin.ota.save') }}" method="post">
                            @csrf
                            <div class="vstack gap-4">
                                <input type="hidden" value="{{ $hotels->id }}" name="hotel_id">
                                <div>
                                    <label class="switch">
                                        <input type="checkbox" name="status" id="toggleStatus"
                                            value="{{ isset($ota) && $ota->status == 1 ? 1 : 0 }}"
                                            onchange="toggleValue(this)"
                                            {{ isset($ota) && $ota->status == 1 ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <!-- Case 1 -->
                                <div class="p-3 bg-light rounded-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            {{ isset($ota) && $ota->allow_all_rooms == 1 ? 'checked' : '' }}
                                            type="radio" name="ota_case" id="case1" value="1" checked>
                                        <label class="form-check-label fs-6 fw-semibold" for="case1"
                                            style="color: #000000">
                                            <i class="bi bi-building"></i> Cho phép lấy tất cả phòng của cơ sở
                                        </label>
                                    </div>
                                </div>

                                <!-- Case 2 -->
                                <div class="p-3 bg-light rounded-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input"
                                            {{ isset($ota) && is_array($ota->allowed_room_types) && !empty($ota->allowed_room_types) ? 'checked' : '' }}
                                            type="radio" name="ota_case" id="case2" value="2">
                                        <label class="form-check-label fs-6 fw-semibold" for="case2"
                                            style="color: #000000">
                                            <i class="bi bi-layers"></i> Chọn loại phòng được phép lấy
                                        </label>
                                    </div>
                                    <div class="room-types-checkboxes ms-4 mt-2 row row-cols-1 row-cols-md-3">
                                        @foreach ($room_type as $item)
                                            <div class="form-check col">
                                                <input class="form-check-input" type="checkbox" name="room_types[]"
                                                    value="{{ $item->id }}" id="{{ $item->name }}"
                                                    @if (isset($ota) && is_array($ota->allowed_room_types) && in_array((string) $item->id, $ota->allowed_room_types)) checked @endif>
                                                <label class="form-check-label" style="color:#000000"
                                                    for="roomTypeStandard">{{ $item->name }}</label>
                                            </div>
                                        @endforeach


                                    </div>
                                </div>
                                <!-- Case 3 -->

                                <div class="p-3 bg-light rounded-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                            {{ isset($ota) && is_array($ota->allowed_rooms) && !empty($ota->allowed_rooms) ? 'checked' : '' }}
                                            name="ota_case" id="case3" value="3">
                                        <label class="form-check-label fs-6 fw-semibold" for="case3"
                                            style="color: #000000">
                                            <i class="bi bi-door-closed"></i> Chọn từng phòng cụ thể
                                        </label>
                                    </div>
                                    <div class="room-list-by-type ms-4 mt-2">
                                        @foreach ($rooms as $item)
                                            <div class="mb-3">
                                                <h6 class="fw-bold" style="color: #000000">{{ $item->name }}</h6>
                                                <div class="form-check d-flex gap-5 ">
                                                    @foreach ($item->rooms as $room)
                                                        <div class="d-flex justify-content-center gap-1">

                                                            <input class="form-check-input" type="checkbox" name="rooms[]"
                                                                value="{{ $room->id }}" id="room{{ $room->id }}"
                                                                @if (isset($ota) && is_array($ota->allowed_rooms) && in_array((string) $room->id, $ota->allowed_rooms)) checked @endif>
                                                            <label class="form-check-label" for="room"
                                                                style="color: #000000">{{ $room->room_number }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                            <button class="btn btn-primary" style="width: 100%;">
                                Lưu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex" style="gap:5px">

        {{-- <a class="btn mt-1 btn-sm btn--primary btn-submit-sync-roles">
            <i class="las la-sync"></i>
        </a> --}}
    </div>
@endpush
@push('script')
    <script>
        function toggleValue(el) {
            el.value = el.checked ? "1" : "0";
        }
        $(document).ready(function() {
            "use strict";
            $('.btn-submit-sync-roles').on('click', function() {
                location.reload();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('input[name="ota_case"]').on('change', function() {
                const val = $(this).val();

                // Hiển thị theo giá trị radio được chọn
                $('.room-types-checkboxes').css('display', val == '2' ? 'flex' : 'none');
                $('.room-list-by-type').css('display', val == '3' ? 'block' : 'none');

                // Bỏ tích checkbox không liên quan
                if (val != '2') {
                    $('.room-types-checkboxes input[type="checkbox"]').prop('checked', false);
                }

                if (val != '3') {
                    $('.room-list-by-type input[type="checkbox"]').prop('checked', false);
                }
            });

            // Khi trang vừa load
            const checked = $('input[name="ota_case"]:checked').val();
            $('.room-types-checkboxes').css('display', checked == '2' ? 'flex' : 'none');
            $('.room-list-by-type').css('display', checked == '3' ? 'block' : 'none');


            // Khi chọn chi nhánh (branch)
            $('#branch').on('change', function() {
                const selectedText = $(this).find('option:selected').text();
                $('#branch-name').text(selectedText);
            });

        });
    </script>
@endpush
<style scoped>
    switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        height: 25px;
        position: absolute;
        cursor: pointer;
        background-color: #ccc;
        border-radius: 34px;
        top: 39px;
        left: 5px;
        right: 18px;
        bottom: 32px;
        transition: .4s;
        width: 50px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(24px);
    }
</style>
