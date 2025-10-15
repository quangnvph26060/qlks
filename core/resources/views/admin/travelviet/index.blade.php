@extends('admin.layouts.master_iframe')

@section('content')
<div class="container-fluid px-3 px-sm-0">
    <div class="card">
        <div class="card-body">
            <form id="tvFormPage" class="row g-3" onsubmit="return false;">
                <div class="col-md-7">
                    <label for="pHotelName" class="form-label">Tên khách sạn</label>
                    <input type="text" class="form-control" id="pHotelName" value="{{ hf('ten_coso') }}" placeholder="Nhập tên khách sạn..." disabled>
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
                <div id="pTableTitle" class="mb-2 fw-bold text-secondary"></div>
                <div class="table-responsive" id="tvTableWrap">
                    <table class="table table-hover">
                        <thead id="pTableHead"></thead>
                        <tbody id="pTableBody"></tbody>
                    </table>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="pSelectAll">
                        <label class="form-check-label" for="pSelectAll">Chọn tất cả</label>
                    </div>
                    <span class="badge bg-primary" id="pSelectedCount">Đã chọn: 0</span>
                    <button class="btn btn--warning ms-auto" id="pSaveBtn" disabled>Lưu</button>
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
            const list = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
            if (!list.length){
                pTableHead.innerHTML = '';
                pTableBody.innerHTML = '<tr><td class="text-center text-muted">Không có dữ liệu</td></tr>';
                return;
            }
            const keys = Object.keys(list[0] || {});
            pTableHead.innerHTML = `<tr><th><input type="checkbox" class="form-check-input" id="pHeaderCheck"></th>${keys.map(k => `<th>${escapeHtml(k)}</th>`).join('')}</tr>`;
            pTableBody.innerHTML = list.map(item => {
                const idVal = item.id ?? '';
                return `<tr>
                    <td><input type="checkbox" class="form-check-input p-row-check" data-id="${escapeHtml(idVal)}" data-json='${escapeHtml(JSON.stringify(item))}'></td>
                    ${keys.map(k => `<td>${escapeHtml(item[k] ?? '')}</td>`).join('')}
                </tr>`;
            }).join('');
            bindChecks();
            pSaveBtn.disabled = false;
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
            setTitle(kind);
            setLoading(true);
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            $.ajax({
                url: endpoint,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf },
                data: { lang_id: lang, hotel_name: hotel },
            }).done(function(res){
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
            call("{{ route('admin.travelviet.search-roomtypes') }}", 'roomtypes');
        });
        btnRooms.addEventListener('click', function(){
            call("{{ route('admin.travelviet.search-rooms') }}", 'rooms');
        });

        const pSelectAll = document.getElementById('pSelectAll');
        const pSelectedCount = document.getElementById('pSelectedCount');
        const pSaveBtn = document.getElementById('pSaveBtn');
        let currentKind = null;

        function bindChecks(){
            const headerCheck = document.getElementById('pHeaderCheck');
            const rowChecks = document.querySelectorAll('.p-row-check');
            function updateCount(){
                const count = document.querySelectorAll('.p-row-check:checked').length;
                pSelectedCount.textContent = 'Đã chọn: ' + count;
                pSaveBtn.disabled = count === 0;
            }
            if (headerCheck) headerCheck.onchange = function(){
                rowChecks.forEach(c => c.checked = this.checked);
                pSelectAll.checked = this.checked;
                updateCount();
            }
            pSelectAll.onchange = function(){
                rowChecks.forEach(c => c.checked = this.checked);
                if (headerCheck) headerCheck.checked = this.checked;
                updateCount();
            }
            rowChecks.forEach(c => c.addEventListener('change', function(){
                const all = Array.from(rowChecks).every(x => x.checked);
                pSelectAll.checked = all;
                if (headerCheck) headerCheck.checked = all;
                updateCount();
            }));
            updateCount();
        }

        pSaveBtn.addEventListener('click', function(){
            const selected = Array.from(document.querySelectorAll('.p-row-check:checked')).map(x => {
                try { return JSON.parse(x.dataset.json); } catch (e) { return null; }
            }).filter(Boolean);
            if (!selected.length){
                alert('Vui lòng chọn ít nhất 1 dòng');
                return;
            }
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            $.ajax({
                url: "{{ route('admin.travelviet.save') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf },
                contentType: 'application/json; charset=UTF-8',
                data: JSON.stringify({ kind: currentKind || 'hotel', items: selected }),
            }).done(function(res){
                alert('Đã lưu thành công ' + (res?.saved ?? selected.length) + ' mục');
            }).fail(function(xhr){
                const msg = xhr?.responseJSON?.message || 'Lưu thất bại';
                alert(msg);
            });
        });
    })();
</script>
@endpush


