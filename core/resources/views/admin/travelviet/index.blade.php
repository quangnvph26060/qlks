@extends('admin.layouts.master_iframe')

@push('style')
<style>
.hotel-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e3e6f0;
}
.hotel-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.hotel-card .card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 0.75rem 1rem;
}
.hotel-card .card-body {
    padding: 1rem;
}
.hotel-card .card-title {
    color: #5a5c69;
    font-weight: 600;
    margin-bottom: 1rem;
}
.hotel-card .card-text {
    margin-bottom: 0.5rem;
    color: #6c757d;
}
.hotel-card .form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}
.hotel-card .fas {
    width: 16px;
    margin-right: 8px;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-sm-0">
    <div class="card">
        <div class="card-body">
            <form id="tvFormPage" class="row g-3" onsubmit="return false;">
                <div class="col-md-7">
                    <label for="pHotelName" class="form-label">Tên khách sạn</label>
                    <input type="text" class="form-control" id="pHotelName" value="{{ hf('ten_coso') }}" placeholder="Nhập tên khách sạn..." >
                </div>
                <div class="col-md-5 d-flex align-items-end gap-2">
                    <button class="btn btn--primary" id="btnHotel">Lấy thông tin khách sạn</button>
                    <button class="btn btn--success" id="btnRoomTypes">Lấy danh sách loại phòng</button>
                    <button class="btn btn--info" id="btnRooms">Lấy danh sách phòng</button>
                </div>
            </form>

            <div class="mt-3">
                <div id="tvLoading" class="d-none text-center py-3">
                    <div class="spinner-border" role="status"></div>
                    <p class="text-muted mt-2 mb-0">Đang tải dữ liệu...</p>
                </div>
                
                <!-- Table Display -->
                <div id="tableContainer" class="d-none">
                    <div id="pTableTitle" class="mb-2 fw-bold text-secondary"></div>
                    <div class="table-responsive" id="tvTableWrap">
                        <table class="table table-hover">
                            <thead id="pTableHead"></thead>
                            <tbody id="pTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="pSelectAll">
                        <label class="form-check-label" for="pSelectAll">Chọn tất cả</label>
                    </div>
                    <span class="badge bg-primary" id="pSelectedCount">Đã chọn: 0</span>
                    <button class="btn btn--warning ms-auto" id="pSaveBtn" disabled>Lưu vào Database</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function(){
        const pHotelName = document.getElementById('pHotelName');
        const btnHotel = document.getElementById('btnHotel');
        const btnRoomTypes = document.getElementById('btnRoomTypes');
        const btnRooms = document.getElementById('btnRooms');
        const tvLoading = document.getElementById('tvLoading');
        const pTableHead = document.getElementById('pTableHead');
        const pTableBody = document.getElementById('pTableBody');
        const pTableTitle = document.getElementById('pTableTitle');

        // No demo fallback

        function setLoading(isLoading){
            tvLoading.classList.toggle('d-none', !isLoading);
        }

        function escapeHtml(str){
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function render(data){
            // Xử lý dữ liệu từ API TravelViet
            let list = [];
            if (data && data.hotel) {
                // Nếu là dữ liệu khách sạn từ TravelViet
                list = [data.hotel];
            } else if (Array.isArray(data)) {
                list = data;
            } else if (Array.isArray(data?.data)) {
                list = data.data;
            } else if (data?.data && typeof data.data === 'object') {
                list = [data.data];
            }

            if (!list.length){
                document.getElementById('tableContainer').classList.add('d-none');
                return;
            }

            // Hiển thị dạng table cho tất cả
            renderTable(list);

            bindChecks();
            pSaveBtn.disabled = false;
        }


        function renderTable(list) {
            const tableContainer = document.getElementById('tableContainer');
            
            // Hiện table
            tableContainer.classList.remove('d-none');

            // Tạo bảng với các cột từ API
            const importantFields = {
                'hotel_name': 'Tên khách sạn',
                'lang_hotel_address': 'Địa chỉ',
                'lang_province_name': 'Tỉnh/Thành phố',
                'hotel_image': 'Hình ảnh'
            };

            // Tạo header
            const headerCells = Object.keys(importantFields).map(key => 
                `<th>${importantFields[key]}</th>`
            ).join('');
            pTableHead.innerHTML = `<tr><th><input type="checkbox" class="form-check-input" id="pHeaderCheck"></th>${headerCells}</tr>`;

            // Tạo body
            pTableBody.innerHTML = list.map(item => {
                const idVal = item.hotel_id ?? item.id ?? '';
                const cells = Object.keys(importantFields).map(key => {
                    let value = '';
                    // Xử lý mapping các trường từ API
                    if (key === 'hotel_name') {
                        // Lấy hotel_name từ request (tên tìm kiếm)
                        value = window.currentSearchHotelName || item.hotel_name || '';
                    } else if (key === 'lang_hotel_address') {
                        value = item.lang_hotel_address ?? '';
                    } else if (key === 'lang_province_name') {
                        value = item.lang_province_name ?? '';
                    } else if (key === 'hotel_image') {
                        // Xử lý hiển thị hình ảnh
                        const imageUrl = item.hotel_image ?? item.hotel_image_small ?? '';
                        if (imageUrl && imageUrl.trim() !== '') {
                            value = `<img src="${escapeHtml(imageUrl)}" alt="Hotel Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.style.display='none'">`;
                        } else {
                            value = '<span class="text-muted">Không có hình</span>';
                        }
                        return `<td>${value}</td>`;
                    } else {
                        value = item[key] ?? '';
                    }
                    return `<td>${escapeHtml(String(value))}</td>`;
                }).join('');

                return `<tr>
                    <td><input type="checkbox" class="form-check-input p-row-check" data-id="${escapeHtml(idVal)}" data-json='${escapeHtml(JSON.stringify(item))}'></td>
                    ${cells}
                </tr>`;
            }).join('');
        }

        function setTitle(kind){
            const map = { hotel: 'Thông tin khách sạn', roomtypes: 'Danh sách loại phòng', rooms: 'Danh sách phòng' };
            const label = map[kind] || 'Dữ liệu';
            pTableTitle.textContent = 'Dữ liệu: ' + label;
        }

        function call(endpoint, kind){
            const lang = 'en';
            const hotel = (pHotelName.value || '').trim();
            if (!hotel){
                alert('Vui lòng nhập tên khách sạn');
                return;
            }
            
            // Lưu tên khách sạn tìm kiếm để sử dụng trong render
            window.currentSearchHotelName = hotel;
            
            setTitle(kind);
            setLoading(true);
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            $.ajax({
                url: endpoint,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf },
                data: { lang_id: lang, hotel_name: hotel },
            }).done(function(res){
                // Lưu tên khách sạn chính xác từ API response để sử dụng cho các API call khác
                if (res?.data?.hotel?.lang_hotel_name) {
                    window.currentHotelName = res.data.hotel.lang_hotel_name;
                }
                render(res?.data ?? res);
                currentKind = kind;
            }).fail(function(xhr){
                const msg = xhr?.responseJSON?.message || 'Không thể lấy dữ liệu từ API';
                alert(msg);
                // Clear previous table
                pTableHead.innerHTML = '';
                pTableBody.innerHTML = '<tr><td class="text-center text-muted">Không có dữ liệu</td></tr>';
                currentKind = null;
            }).always(function(){
                setLoading(false);
            });
        }

        btnHotel.addEventListener('click', function(){
            call("{{ route('admin.travelviet.search-hotel') }}", 'hotel');
        });
        btnRoomTypes.addEventListener('click', function(){
            // Sử dụng tên khách sạn chính xác từ API nếu có
            const hotelName = window.currentHotelName || (pHotelName.value || '').trim();
            if (!hotelName) {
                alert('Vui lòng tìm khách sạn trước khi lấy danh sách loại phòng');
                return;
            }
            // Tạm thời thay đổi giá trị input để sử dụng tên chính xác
            const originalValue = pHotelName.value;
            pHotelName.value = hotelName;
            call("{{ route('admin.travelviet.search-roomtypes') }}", 'roomtypes');
            // Khôi phục giá trị gốc
            pHotelName.value = originalValue;
        });
        btnRooms.addEventListener('click', function(){
            // Sử dụng tên khách sạn chính xác từ API nếu có
            const hotelName = window.currentHotelName || (pHotelName.value || '').trim();
            if (!hotelName) {
                alert('Vui lòng tìm khách sạn trước khi lấy danh sách phòng');
                return;
            }
            // Tạm thời thay đổi giá trị input để sử dụng tên chính xác
            const originalValue = pHotelName.value;
            pHotelName.value = hotelName;
            call("{{ route('admin.travelviet.search-rooms') }}", 'rooms');
            // Khôi phục giá trị gốc
            pHotelName.value = originalValue;
        });

        const pSelectAll = document.getElementById('pSelectAll');
        const pSelectedCount = document.getElementById('pSelectedCount');
        const pSaveBtn = document.getElementById('pSaveBtn');
        let currentKind = null;

        function bindChecks(){
            const headerCheck = document.getElementById('pHeaderCheck');
            const rowChecks = document.querySelectorAll('.p-row-check');
            
            if (headerCheck) headerCheck.onchange = function(){
                rowChecks.forEach(c => c.checked = this.checked);
                pSelectAll.checked = this.checked;
                updateSelectedCount();
            }
            pSelectAll.onchange = function(){
                rowChecks.forEach(c => c.checked = this.checked);
                if (headerCheck) headerCheck.checked = this.checked;
                updateSelectedCount();
            }
            rowChecks.forEach(c => c.addEventListener('change', function(){
                const all = Array.from(rowChecks).every(x => x.checked);
                pSelectAll.checked = all;
                if (headerCheck) headerCheck.checked = all;
                updateSelectedCount();
            }));
            updateSelectedCount();
        }

        pSaveBtn.addEventListener('click', function(){
            const selected = Array.from(document.querySelectorAll('.p-row-check:checked')).map(x => {
                try { return JSON.parse(x.dataset.json); } catch (e) { return null; }
            }).filter(Boolean);
            if (!selected.length){
                alert('Vui lòng chọn ít nhất 1 khách sạn để lưu vào database');
                return;
            }
            
            // Hiển thị thông tin chi tiết trước khi lưu
            const hotelNames = selected.map(item => item.lang_hotel_name || 'N/A').join(', ');
            if (!confirm(`Bạn có chắc chắn muốn lưu ${selected.length} khách sạn vào database?\n\nKhách sạn: ${hotelNames}`)) {
                return;
            }

            setLoading(true);
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            $.ajax({
                url: "{{ route('admin.travelviet.save') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf },
                contentType: 'application/json; charset=UTF-8',
                data: JSON.stringify({ kind: currentKind || 'hotel', items: selected }),
            }).done(function(res){
                alert('✅ Đã lưu thành công ' + (res?.saved ?? selected.length) + ' khách sạn vào database!');
                // Reset selection
                document.querySelectorAll('.p-row-check:checked').forEach(cb => cb.checked = false);
                document.getElementById('pSelectAll').checked = false;
                updateSelectedCount();
            }).fail(function(xhr){
                const msg = xhr?.responseJSON?.message || 'Lưu thất bại';
                alert('❌ Lỗi: ' + msg);
            }).always(function(){
                setLoading(false);
            });
        });

        function updateSelectedCount() {
            const count = document.querySelectorAll('.p-row-check:checked').length;
            document.getElementById('pSelectedCount').textContent = 'Đã chọn: ' + count;
            document.getElementById('pSaveBtn').disabled = count === 0;
        }
    })();
</script>
@endpush


