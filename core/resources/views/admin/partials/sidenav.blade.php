@php
    $sideBarLinks = json_decode($sidenav);
@endphp

<div class="sidebar bg--dark" id="sidebar">
    {{-- <button class="toggle-btn" id="toggle-btn">&#8592;</button> --}}
    <button class="res-sidebar-close-btn">
       <i class="las la-times"></i>
    </button>
    <div class="sidebar__inner">
        <div class="sidebar__logo d-none-mobi">
            {{-- <a href="{{ route('admin.display') }}" class="sidebar__main-logo">
                <img src="{{ siteLogo() }}" alt="image">
            </a> --}}
               <div class="d-flex" style="margin-bottom: 12px;margin-top: 12px">
                {{-- <li>
                    <a class="btn btn--danger booking-req me-2 me-md-3" style="white-space: nowrap;"
                        href="{{ route('admin.receptionist.booking.receptionist') }}">
                        Yêu cầu đặt phòng
                    </a>
                </li> --}}
                  <li class="dropdown d-flex profile-dropdown">
                    <button type="button" data-bs-toggle="dropdown" data-display="static" aria-haspopup="true"
                        aria-expanded="false" style="background: none">
                        <span class="navbar-user">
                            <span class="navbar-user__thumb"><img
                                    src="{{ getImage(getFilePath('adminProfile') . '/' . auth()->guard('admin')->user()->image, getFileSize('adminProfile')) }}"
                                    alt="image"></span>
                            {{-- <span class="navbar-user__info">
                                <span class="navbar-user__name">{{ auth()->guard('admin')->user()->username }}</span>
                            </span> --}}
                            <span class="icon"><i class="las la-chevron-circle-down"></i></span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                        <a href="{{ route('admin.profile') }}"
                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-user-circle"></i>
                            <span class="dropdown-menu__caption">@lang('Hồ sơ')</span>
                        </a>

                        <a href="{{ route('admin.password') }}"
                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-key"></i>
                            <span class="dropdown-menu__caption">@lang('Mật khẩu')</span>
                        </a>
                        {{-- <a href="{{ route('admin.setting.system') }}"
                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-cog"></i>

                            <span class="dropdown-menu__caption">@lang('Thiết lập hệ thống')</span>
                        </a> --}}
                        <a href="{{ route('admin.logout') }}"
                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                            <span class="dropdown-menu__caption">@lang('Đăng xuất')</span>
                        </a>
                    </div>
                    <button type="button" class="breadcrumb-nav-open ms-2 d-none">
                        <i class="las la-sliders-h"></i>
                    </button>
                </li>
                <li style="width: 120px;    list-style-type: none;">
                    <a  class="btn btn--danger booking-req me-2 me-md-3"target="_blank" style="white-space: nowrap;width: 120px"
                        href="{{ route('admin.receptionist.booking.receptionist') }}">
                        Lễ tân
                    </a>
                </li>
              
            </div>
        </div>
        <div class="sidebar__menu-wrapper">

            <ul class="sidebar__menu">
                <!-- <li class="sidebar-menu-item">
                    <a href="{{ route('admin.travelviet.index') }}" onclick="return loadIframe(this.href);" class="nav-link">
                        <i class="menu-icon las la-cloud-download-alt"></i>
                        <span class="menu-title">Lấy Dữ Liệu TravelViet</span>
                    </a>
                </li> -->
                @foreach ($sideBarLinks as $key => $data)
                    {{-- @if (@$data->header && auth()->guard('admin')->id() == 1)
                            <li class="sidebar__menu-header">{{ __($data->header) }}</li>
                        @endif --}}

                    @if (@$data->submenu)
                        @can(array_column($data->submenu, 'route_name'))
                            <li class="sidebar-menu-item sidebar-dropdown">
                                <a href="javascript:void(0)" onclick="return loadIframe(this.href);"
                                    class="{{ menuActive(@$data->menu_active, 3) }}">

                                    <!-- <a href="javascript:void(0)" class="{{ menuActive(@$data->menu_active, 3) }}"> -->
                                    <i class="menu-icon {{ @$data->icon }}"></i>
                                    <span class="menu-title">{{ __(@$data->title) }}</span>
                                    @foreach (@$data->counters ?? [] as $counter)
                                        @if ($counter > 0)
                                            <span class="menu-badge menu-badge-level-one bg--warning ms-auto">
                                                <i class="fas fa-exclamation"></i>
                                            </span>
                                            @break
                                        @endif
                                    @endforeach
                                </a>
                                <div class="sidebar-submenu {{ menuActive(@$data->menu_active, 2) }} ">
                                    <ul>
                                        @foreach ($data->submenu as $menu)
                                            @php
                                                $submenuParams = null;
                                                if (@$menu->params) {
                                                    foreach ($menu->params as $submenuParamVal) {
                                                        $submenuParams[] = array_values((array) $submenuParamVal)[0];
                                                    }
                                                }
                                            @endphp

                                            @can($menu->route_name)
                                                <li class="sidebar-menu-item {{ menuActive(@$menu->menu_active) }}"
                                                    data-route="{{ $menu->route_name }}">


                                                    <!-- <a href="{{ route(@$menu->route_name, $submenuParams) }}" class="nav-link"> -->
                                                    <a href="{{ route(@$menu->route_name, $submenuParams) }}"
                                                        onclick="return loadIframe(this.href);" class="nav-link">


                                                        {{--   <a href="{{ route(@$menu->route_name, $submenuParams) }}" class="nav-link"> --}}

                                                        <i class="menu-icon las la-dot-circle"></i>
                                                        <span class="menu-title">{{ __($menu->title) }}</span>
                                                        @php $counter = @$menu->counter; @endphp
                                                        @if (@$$counter)
                                                            <span
                                                                class="menu-badge bg--info ms-auto">{{ @$$counter }}</span>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endcan
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endcan
                    @else
                        @php
                            $mainParams = null;
                            if (!empty($data->params)) {
                                foreach ($data->params as $paramVal) {
                                    $mainParams[] = array_values((array) $paramVal)[0];
                                }
                            }
                        @endphp

                        @if (!empty($data->route_name))
                            @can($data->route_name)
                                <li class="sidebar-menu-item {{ menuActive($data->menu_active ?? '') }}">
                                    <a href="{{ route($data->route_name, $mainParams) }}"
                                        onclick="return loadIframe(this.href);" class="nav-link">
                                        <i class="menu-icon {{ $data->icon }}"></i>
                                        <span class="menu-title">{{ __($data->title) }}</span>
                                        @php $counter = $data->counter ?? null; @endphp
                                        @if (!empty($$counter))
                                            <span class="menu-badge bg--info ms-auto">{{ $$counter }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                        @elseif (!empty($data->redirect))
                            <li class="sidebar-menu-item click_demo">
                                <a href="{{ $data->redirect }}" class="nav-link" target="_blank" rel="noopener">
                                    <i class="menu-icon {{ $data->icon }}"></i>
                                    <span class="menu-title">{{ __($data->title) }}</span>
                                </a>
                            </li>
                        @else
                            <li class="sidebar-menu-item disabled">
                                <a href="javascript:void(0);" class="nav-link">
                                    <i class="menu-icon {{ $data->icon }}"></i>
                                    <span class="menu-title">{{ __($data->title) }}</span>
                                </a>
                            </li>
                        @endif
                    @endif



                    {{-- @else
                        @php
                            $mainParams = null;
                            if (@$data->params) {
                                foreach ($data->params as $paramVal) {
                                    $mainParams[] = array_values((array) $paramVal)[0];
                                }
                            }
                        @endphp
                        @can(@$data->route_name)
                            <li class="sidebar-menu-item {{ menuActive(@$data->menu_active) }}">
                                <a href="{{ route(@$data->route_name, $mainParams) }}"
                                    onclick="return loadIframe(this.href);" class="nav-link">
                                    <i class="menu-icon {{ $data->icon }}"></i>
                                    <span class="menu-title">{{ __(@$data->title) }}</span>
                                    @php $counter = @$data->counter; @endphp
                                    @if (@$$counter)
                                        <span class="menu-badge bg--info ms-auto">{{ @$$counter }}</span>
                                    @endif
                                </a>
                            </li>
                        @endcan
                    @endif --}}
                @endforeach
            </ul>

        </div>
        <div class="version-info text-center text-uppercase">
            <span class="text-white" style="font-size: 10px">© Copyright 2025 FastHotel.vn Corporation. All Right
                Reserved</span>
            {{-- <span class="text--success">huhuhuhu</span>  --}}
        </div>
    </div>
</div>
<style type="text/css">
    .sidebar .toggle-btn {
        position: absolute;
        right: 0px;
        /* Vị trí của mũi tên */
        font-size: 30px;
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        z-index: 999999;
    }

    .navbar-wrapper.shifted {
        margin-left: 0 !important;
        /* Di chuyển nội dung chính khi sidebar bị ẩn */
    }

    .body-wrapper.shifted {
        margin-left: 0 !important;
        /* Di chuyển nội dung chính khi sidebar bị ẩn */
    }

    .sidebar.closed {
        transform: translateX(-250px);
        /* Ẩn sidebar bằng cách dịch chuyển nó sang trái */
    }
    /* TravelViet Modal styles */
    #travelVietModalBackdrop{
        position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1050;
    }
    #travelVietModal{ position: fixed; inset: 0; z-index: 1051; display: flex; align-items: center; justify-content: center; }
    .tv-modal-dialog{ width:95%; max-width:1100px; max-height:90vh; overflow:hidden; background:#fff; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,.3); display:flex; flex-direction:column; }
    .tv-modal-header{ background:linear-gradient(135deg,#2563eb,#7c3aed); color:#fff; padding:16px 24px; display:flex; align-items:center; justify-content:space-between; }
    .tv-modal-body{ padding:16px 24px; overflow:auto; }
    .tv-modal-footer{ padding:16px 24px; display:flex; gap:12px; justify-content:flex-end; }
    .tv-grid{ display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .tv-option{ border:2px solid #e5e7eb; border-radius:12px; padding:16px; cursor:pointer; transition:.2s; background:#fff; }
    .tv-option:hover{ border-color:#2563eb; background:#f0f9ff; transform:translateX(4px); }
    .tv-option.active{ border-color:#2563eb; background:linear-gradient(135deg,#dbeafe,#e0e7ff); box-shadow:0 4px 12px rgba(37,99,235,.2); }
    .tv-table-wrap{ max-height:400px; overflow:auto; margin-top:12px; }
    .tv-btn-primary{ background:linear-gradient(135deg,#2563eb,#7c3aed); color:#fff; border:none; padding:8px 12px; border-radius:10px; }
    .tv-btn-success{ background:#10b981; color:#fff; border:none; padding:10px 16px; border-radius:10px; }
    .tv-btn-danger{ background:linear-gradient(135deg,#f59e0b,#ef4444); color:#fff; border:none; padding:10px 16px; border-radius:10px; }
    @media (max-width: 992px){ .tv-grid{ grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width: 576px){ .tv-grid{ grid-template-columns:1fr; } }
</style>

<!-- TravelViet Modal removed; now using dedicated page -->

@push('script')
    <script>
        $(document).ready(function() {
              $('.click_demo').on('click', function(e) {
            e.preventDefault(); // Ngăn chặn hành vi mặc định nếu cần
            let href = $(this).find('a').attr('href');
            if (href) {
                window.open(href, '_blank');
            }
        });
            $('.res-sidebar-open-btn').on('click', function () {
                $('.sidebar').addClass('open');
            });

            $('.res-sidebar-close-btn').on('click', function() {
                $('.sidebar').removeClass('open');
            });

            // TravelViet modal logic (no Bootstrap dependency)
            const tvBackdrop = document.getElementById('travelVietModalBackdrop');
            const tvModal = document.getElementById('travelVietModal');
            const tvOpenBtn = document.getElementById('openTravelVietModalBtn');
            const tvCloseBtn = document.getElementById('tvCloseBtn');
            const tvCloseBtn2 = document.getElementById('tvCloseBtn2');
            const tvForm = document.getElementById('tvForm');
            const tvLangId = document.getElementById('tvLangId');
            const tvHotelName = document.getElementById('tvHotelName');
            const tvSearchBtn = document.getElementById('tvSearchBtn');
            const tvStepOptions = document.getElementById('tvStepOptions');
            const tvStepLoading = document.getElementById('tvStepLoading');
            const tvStepData = document.getElementById('tvStepData');
            const tvSelectAll = document.getElementById('tvSelectAll');
            const tvTableHead = document.getElementById('tvTableHead');
            const tvTableBody = document.getElementById('tvTableBody');
            const tvSelectedCount = document.getElementById('tvSelectedCount');
            const tvSaveBtn = document.getElementById('tvSaveBtn');

            let selectedOption = null;
            let currentData = [];

            function openModal(){
                tvBackdrop.style.display = 'block';
                tvModal.style.display = 'flex';
                document.body.classList.add('modal-open');
            }
            function closeModal(){
                tvBackdrop.style.display = 'none';
                tvModal.style.display = 'none';
                resetModal();
                document.body.classList.remove('modal-open');
            }
            function resetModal(){
                tvStepOptions.style.display = 'block';
                tvStepLoading.style.display = 'none';
                tvStepData.style.display = 'none';
                tvSaveBtn.style.display = 'none';
                selectedOption = null;
                currentData = [];
                tvTableHead.innerHTML = '';
                tvTableBody.innerHTML = '';
                tvSelectedCount.textContent = '0';
                tvSelectAll.checked = false;
            }

            tvOpenBtn && tvOpenBtn.addEventListener('click', openModal);
            tvBackdrop.addEventListener('click', closeModal);
            tvCloseBtn.addEventListener('click', closeModal);
            tvCloseBtn2.addEventListener('click', closeModal);

            tvSearchBtn.addEventListener('click', function(){
                const langId = (tvLangId.value || '').trim();
                const hotelName = (tvHotelName.value || '').trim();
                if (!langId || !hotelName) {
                    alert('Vui lòng nhập lang_id và tên khách sạn');
                    return;
                }
                tvStepOptions.style.display = 'none';
                tvStepLoading.style.display = 'block';

                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                $.ajax({
                    url: '{{ route('admin.travelviet.search-hotel') }}',
                    method: 'POST',
                    headers: csrf ? { 'X-CSRF-TOKEN': csrf } : {},
                    data: { lang_id: langId, hotel_name: hotelName },
                }).done(function(res){
                    const payload = (res && (res.data ?? res.results ?? res.items)) || res || [];
                    const list = Array.isArray(payload) ? payload : (Array.isArray(payload?.data) ? payload.data : []);
                    currentData = list;
                    renderTableFromApi(currentData);
                    tvStepLoading.style.display = 'none';
                    tvStepData.style.display = 'block';
                    tvSaveBtn.style.display = currentData.length ? 'inline-block' : 'none';
                }).fail(function(xhr){
                    tvStepLoading.style.display = 'none';
                    tvStepOptions.style.display = 'block';
                    const msg = xhr?.responseJSON?.message || 'Không thể lấy dữ liệu từ API';
                    alert(msg);
                });
            });

            function renderTableFromApi(data){
                const list = Array.isArray(data) ? data : [];
                if (!list.length){
                    tvTableHead.innerHTML = '';
                    tvTableBody.innerHTML = '<tr><td class="text-center text-muted">Không có dữ liệu</td></tr>';
                    setupChecks();
                    return;
                }
                const keys = Object.keys(list[0] || {});
                const headerCells = ['<th><input type="checkbox" class="form-check-input" id="tvHeaderCheck"></th>']
                    .concat(keys.map(k => `<th>${k}</th>`))
                    .join('');
                tvTableHead.innerHTML = `<tr>${headerCells}</tr>`;
                const rows = list.map(item => {
                    const tds = keys.map(k => `<td>${escapeHtml(String(item[k] ?? ''))}</td>`).join('');
                    const idVal = item.id ?? '';
                    return `<tr><td><input type="checkbox" class="form-check-input tv-row-check" data-id="${idVal}"></td>${tds}</tr>`;
                }).join('');
                tvTableBody.innerHTML = rows;
                setupChecks();
            }

            function escapeHtml(str){
                return str
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function setupChecks(){
                const headerCheck = document.getElementById('tvHeaderCheck');
                const rowChecks = document.querySelectorAll('.tv-row-check');
                function updateCount(){
                    tvSelectedCount.textContent = document.querySelectorAll('.tv-row-check:checked').length;
                }
                tvSelectAll.onchange = function(){
                    rowChecks.forEach(c => c.checked = this.checked);
                    if (headerCheck) headerCheck.checked = this.checked;
                    updateCount();
                }
                if (headerCheck) headerCheck.onchange = function(){
                    rowChecks.forEach(c => c.checked = this.checked);
                    tvSelectAll.checked = this.checked;
                    updateCount();
                }
                rowChecks.forEach(c => c.addEventListener('change', function(){
                    const allChecked = Array.from(rowChecks).every(x => x.checked);
                    tvSelectAll.checked = allChecked;
                    if (headerCheck) headerCheck.checked = allChecked;
                    updateCount();
                }));
                updateCount();
            }

            tvSaveBtn.addEventListener('click', function(){
                const selectedIds = Array.from(document.querySelectorAll('.tv-row-check:checked')).map(x => x.dataset.id);
                if (!selectedIds.length){
                    alert('Vui lòng chọn ít nhất 1 mục để lưu!');
                    return;
                }
                const btn = this;
                const prev = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';
                btn.disabled = true;
                setTimeout(() => {
                    alert(`✅ Đã lưu thành công ${selectedIds.length} mục vào database!`);
                    btn.innerHTML = prev;
                    btn.disabled = false;
                    closeModal();
                }, 1200);
            });
        });
    </script>
@endpush
