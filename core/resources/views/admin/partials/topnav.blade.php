@php
    $sidenav = json_decode($sidenav);

    $settings = file_get_contents(resource_path('views/admin/setting/settings.json'));
    $settings = json_decode($settings);

    $routesData = [];
    foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
        $name = $route->getName();
        if (strpos($name, 'admin') !== false) {
            $routeData = [
                $name => url($route->uri()),
            ];

            $routesData[] = $routeData;
        }
    }
@endphp

<!-- navbar-wrapper start -->
<nav class="navbar-wrapper bg--dark d-flex flex-wrap top-menu">
    {{-- <div class="navbar__left">
        <button type="button" class="res-sidebar-open-btn me-3"><i class="las la-bars"></i></button>
<nav class="navbar-wrapper bg--dark d-flex ">
    <div class="col-md-8 navbar__left">
        {{-- <button type="button" class="res-sidebar-open-btn me-3"><i class="las la-bars"></i></button>

        <form class="navbar-search">
            <input type="search" name="#0" class="navbar-search-field" id="searchInput" autocomplete="off" placeholder="@lang('Tìm kiếm ở đây...')">
            <i class="las la-search"></i>
            <ul class="search-list"></ul>
        </form> --}}
        <div class="nav-tabss">
            <nav>
                <ul class="d-flex main__tabs-list">
                </ul>
            </nav>
        </div>
    </div>
    <div class=" col-md-4 navbar__right" style="display: flex; align-items: baseline;">

        <button type="button" class="res-sidebar-open-btn me-3"><i class="las la-bars"></i></button>
        <ul class="navbar__action-list">
            {{-- @if (version_compare(gs('available_version'), systemDetails()['version'], '>'))
                @can('admin.system.update')
                    <li><button type="button" class="primary--layer" data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('Update Available')"><a href="{{ route('admin.system.update') }}" class="primary--layer"><i class="las la-download text--warning"></i></a> </button></li>
                @endcan
            @endif --}}
            @can('admin.request.booking.all')
                <li>
                    <a class="btn btn--danger booking-req me-2 me-md-3" href="{{ route('admin.request.booking.all') }}">
                        @lang('Yêu cầu đặt phòng') <small
                            class="fw-bold px-2 rounded bg-light text--danger">{{ $bookingRequestCount }}</small>
                    </a>
                </li>
            @endcan

            <li>
                <button type="button" class="primary--layer" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="@lang('Visit Website')">
                    <a href="{{ route('home') }}" target="_blank"><i class="las la-globe"></i></a>
                </button>
            </li>
            <li class="dropdown">
                <button type="button" class="primary--layer notification-bell" data-bs-toggle="dropdown"
                    data-display="static" aria-haspopup="true" aria-expanded="false">
                    <span data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('Unread Notifications')">
                        <i class="las la-bell @if ($adminNotificationCount > 0) icon-left-right @endif"></i>
                    </span>
                    @if ($adminNotificationCount > 0)
                        <span
                            class="notification-count">{{ $adminNotificationCount <= 9 ? $adminNotificationCount : '9+' }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu--md p-0 border-0 box--shadow1 dropdown-menu-right">
                    <div class="dropdown-menu__header">
                        <span class="caption">@lang('Notification')</span>
                        @if ($adminNotificationCount > 0)
                            <p>@lang('You have') {{ $adminNotificationCount }} @lang('unread notification')</p>
                        @endif
                    </div>
                    <div
                        class="dropdown-menu__body @if (blank($adminNotifications)) d-flex justify-content-center align-items-center @endif">
                        @forelse($adminNotifications as $notification)
                            <a href="{{ can('admin.notification.read') ? route('admin.notification.read', $notification->id) : 'javascript:void(0)' }}"
                                class="dropdown-menu__item">
                                <div class="navbar-notifi">
                                    <div class="navbar-notifi__right">
                                        <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                                        <span class="time"><i class="far fa-clock"></i>
                                            {{ diffForHumans($notification->created_at) }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="empty-notification text-center">
                                <img src="{{ getImage('assets/images/empty_list.png') }}" alt="empty">
                                <p class="mt-3">@lang('Không tìm thấy thông báo chưa đọc')</p>
                            </div>
                        @endforelse
                    </div>
                    @can('admin.notifications')
                        <div class="dropdown-menu__footer">
                            <a href="{{ route('admin.notifications') }}" class="view-all-message">@lang('Xem tất cả thông báo')</a>
                        </div>
                    @endcan
                </div>
            </li>
            @can('admin.setting.system')
                <li>
                    <button type="button" class="primary--layer" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="@lang('Thiết lập hệ thống')">
                        <a href="{{ route('admin.setting.system') }}"><i class="las la-wrench"></i></a>
                    </button>
                </li>
            @endcan
            <li class="dropdown d-flex profile-dropdown">
                <button type="button" data-bs-toggle="dropdown" data-display="static" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="navbar-user">
                        <span class="navbar-user__thumb"><img
                                src="{{ getImage(getFilePath('adminProfile') . '/' . auth()->guard('admin')->user()->image, getFileSize('adminProfile')) }}"
                                alt="image"></span>
                        <span class="navbar-user__info">
                            <span class="navbar-user__name">{{ auth()->guard('admin')->user()->username }}</span>
                        </span>
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
        </ul>
    </div>
</nav>

<!-- navbar-wrapper end -->
<style scoped>
    /* background-color: #4634ff59 !important; */
    .nav-item {
        border: 1px solid;
        position: relative;
        border-radius: 6px;
    }

    .nav-tabss {
        position: relative;
        bottom:  -16px;
    }

    .nav-link-tabs {
        padding: 4px 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: aliceblue;
        cursor: pointer;
    }

    .close-tab {
        position: absolute;
        top: -8px;
        right: -4px;
        background: red;
        color: white;
        font-size: 12px;
        font-weight: bold;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
    }

    .nav-item.active {
        background-color: #4634ff59 !important;
        color: white;
    }
    .main__tabs-list {
    display: flex;
    gap: 10px;
    width: 1000px;
    overflow-x: auto;
    overflow-y: hidden; 
    white-space: nowrap; 
    padding-bottom: 5px;
    scrollbar-width: thin; 
    scrollbar-color: #071251 transparent;
}

.nav-item {
    flex: 0 0 auto; /* Đảm bảo các <li> không bị co lại */
}

</style>
@push('script')
    <script>
        "use strict";
        var routes = @json($routesData);

        var settingsData = Object.assign({}, @json($settings), @json($sidenav));

        $('.navbar__action-list .dropdown-menu').on('click', function(event) {
            event.stopPropagation();
        });
    </script>
    <script src="{{ asset('assets/admin/js/search.js') }}"></script>
    <script>
        "use strict";
        var currentUrl = "{{ request()->url() }}";
        var currentPath = "{{ request()->path() }}";
        var routeName = "{{ Route::currentRouteName() }}";

        function getEmptyMessage() {
            return `<li class="text-muted">
                <div class="empty-search text-center">
                    <img src="{{ getImage('assets/images/empty_list.png') }}" alt="empty">
                    <p class="text-muted">Không tìm thấy kết quả tìm kiếm</p>
                </div>
            </li>`
        }
        function renderTabs() {
            if (localStorage.getItem('activeDataIds')) {
                activeDataIds = JSON.parse(localStorage.getItem('activeDataIds'));
                activeDataIds.forEach(function(item) {
                    Object.keys(item).forEach(function(key) {

                        var html = `<li class="nav-item click-tabs mt-2 ${routeName === key ?'active':''}" role="presentation">
                        <a class="nav-link-tabs" data-key="${key}">${item[key]}</a>
                        <span class="close-tab" data-key="${key}">X</span>
                        </li>`;
                        $('.main__tabs-list').append(html);
                    });
                });
            }
        };
        renderTabs();
        $('.close-tab').on('click', function(event) {
            event.stopPropagation();
            let dataKey = $(this).attr('data-key'); // Lấy giá trị data-key từ tab
            if (localStorage.getItem('activeDataIds')) {
                let activeDataIds = JSON.parse(localStorage.getItem('activeDataIds'));
                activeDataIds = activeDataIds.filter(item => {
                    let key = Object.keys(item)[0]; // Lấy key của object
                    return key !== dataKey; // Chỉ giữ lại những object KHÔNG có key trùng dataKey
                });
                localStorage.setItem('activeDataIds', JSON.stringify(activeDataIds));
                $(this).closest('.nav-item').remove();
            }
        });
        $('.nav-link-tabs').on('click', function() {
            var dataKey = $(this).data('key');
            routes.forEach(function(item) {
                Object.keys(item).forEach(function(key) {
                    if (key === dataKey) {
                        window.location.href = item[key];
                    }
                })
            })
        });
    </script>
@endpush
