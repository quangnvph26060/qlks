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
</style>

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
        });
    </script>
@endpush
