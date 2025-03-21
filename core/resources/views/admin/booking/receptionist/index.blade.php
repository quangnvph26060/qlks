@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row d-flex align-items-center">
        <div class="col-md-3">
            <div class="view-toggle">
                <button id="listViewBtn" class="active" onclick="changeView('list')">
                    <span class="icon"> <i class="fa-solid fa-bars"></i></span> <span class="text">Danh Sách</span>
                </button>
                <button id="gridViewBtn" onclick="changeView('calendar')">
                    <span class="icon"><i class="fa-solid fa-sliders"></i></span> <span class="text"
                        style="display: none;">Lưới</span>
                </button>
                <button id="tableViewBtn" onclick="changeView('grid')">
                    <span class="icon"> <i class="fa-solid fa-th-large"></i></span> <span class="text"
                        style="display: none;">Sơ đồ</span>
                </button>
            </div>
        </div>
        <div class="col-md-9" id="booking-time">
            <div style="float: right; gap: 10px;height: 40px;" class="d-flex">
                <div class="date-input-booking" style="display: flex;gap: 10px;">
                    <input type="date" id="startDate" class="form-control w-auto" style="height: 40px" placeholder="Từ ngày">
                    <input type="date" id="endDate" class="form-control w-auto" style="height: 40px" placeholder="Đến ngày">
                </div>
                <p class="btn btn-primary change-room d-flex align-items-center" style="font-size:13px; gap: 5px;"><i
                        class="la la-plus"></i> Đặt phòng</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @include('admin.booking.partials.system-1')
        </div>
    </div>
    <div class="row">
        <div id="listView" class="view">
            @include('admin/booking/receptionist/list')
        </div>
        <div id="gridView" class="view" style="display: none;">
            @include('admin/booking/receptionist/grid')
        </div>
        <div id="calendarView" class="view" style="display: none;">
            @include('admin/booking/receptionist/calendar')
        </div>
    </div>
@endsection
@push('style-lib')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/system-1.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/view-toggle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/grid_main.css') }}">
@endpush
<script src="https://cdn.jsdelivr.net/npm/tesseract.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endpush

<script>
    var roomBoookingHistory = "{{ route('admin.booking.room-booking-history') }}"
    $(document).ready(function() {
        let dirtyCount = 5; // Ví dụ giá trị
        let incomingCount = 2;
        let occupiedCount = 10;
        let lateCheckinCount = 1;
        let checkOutCount = 3;

        $('.status-available-line-count').text('Đang trống (' + dirtyCount + ')');
        $('.status-incoming-line-count').text('Sắp nhận (' + incomingCount + ')');
        $('.status-occupied-line-count').text('Đang sử dụng (' + occupiedCount + ')');
        $('.status-checkout-line-count').text('Nhận phòng muộn (' + lateCheckinCount + ')');
        $('.status-overdue-line-count').text('Quá giờ trả (' + checkOutCount + ')');
    });

    function loadScript(view) {
        let scriptId = 'view-script';
        let oldScript = document.getElementById(scriptId);
        if (oldScript) {
            oldScript.remove();
        }
        let script = document.createElement('script');
        script.id = scriptId;
        script.src = `{{ asset('assets/admin/js/${view}-main.js') }}`;
        script.onload = function() {};
        document.body.appendChild(script);
    }
    document.addEventListener("DOMContentLoaded", function() {
        const buttons = document.querySelectorAll(".view-toggle button");

        // Lấy trạng thái lưu trữ từ LocalStorage, mặc định là 'list'
        let savedView = localStorage.getItem('selectedView') || 'list';
        setActiveButton(savedView);

        buttons.forEach(button => {
            button.addEventListener("click", function() {
                let selectedView = this.getAttribute("onclick").match(/'([^']+)'/)[1];
                localStorage.setItem('selectedView', selectedView);
                setActiveButton(selectedView);
            });
        });

        function setActiveButton(view) {
            buttons.forEach(btn => {
                btn.classList.remove("active");
                btn.querySelector(".text").style.display = "none";
            });

            let activeButton = document.querySelector(`[onclick="changeView('${view}')"]`);
            if (activeButton) {
                activeButton.classList.add("active");
                activeButton.querySelector(".text").style.display = "inline";
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        let savedView = localStorage.getItem('selectedView') || 'list';
        
        changeView(savedView);
    });

    function changeView(view) {
        document.querySelectorAll('.view').forEach(el => el.style.display = 'none');
        document.getElementById(view + 'View').style.display = 'block';
        loadScript(view);
        localStorage.setItem('selectedView', view);
        // let html  = ""
        // if(view == 'calendar'){
        //         html += `
        //             <input type="date" id="startDate" class="form-control w-auto" style="height: 40px" placeholder="Từ ngày">
        //                 <input type="date" id="endDate" class="form-control w-auto" style="height: 40px"
        //                     placeholder="Đến ngày">
        //         `;
        // }
        // document.querySelectorAll('.date-input-booking').forEach(el => {    el.innerHTML = html;});



    }
</script>
