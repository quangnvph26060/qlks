@php
    $contactContent = getContent('contact_us.content', true);
    $socialElements = getContent('social_icon.element', false, null, true);
@endphp

<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row gy-2 align-items-center">
                <div class="col-lg-5 d-sm-block d-none">
                    <ul class="header-info-list justify-content-lg-start justify-content-center">
                        <li>
                            <a href="mailto:{{ $contactContent->data_values->email_address }}"><i
                                    class="fas fa-envelope"></i> {{ $contactContent->data_values->email_address }}</a>
                        </li>

                        <li>
                            <a href="tel:{{ $contactContent->data_values->contact_number }}"><i
                                    class="fas fa-phone-alt"></i> +{{ $contactContent->data_values->contact_number }}</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-7">
                    <div class="header-top-right justify-content-lg-end justify-content-center">
                        <div class="header-top-action-wrapper">
                            {{-- @if (gs('multi_language'))
                                @php
                                    $language = getLanguages();
                                    $default = getLanguages(true);
                                @endphp
                                <div class="language dropdown">
                                    <button class="language-wrapper" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="language-content">
                                            <div class="language_flag">
                                                <img src="{{ getImage(getFilePath('language') . '/' . $default->image, getFileSize('language')) }}" alt="">
                                            </div>
                                            <p class="language_text_select">{{ __($default->name) }}</p>
                                        </div>
                                        <span class="collapse-icon"><i class="las la-angle-down"></i></span>
                                    </button>
                                    <div class="dropdown-menu langList_dropdow py-2" style="">
                                        <ul class="langList">
                                            @foreach ($language->where('code', '!=', $default->code) as $lang)
                                                <li>
                                                    <a href="{{ route('lang', $lang->code) }}" class="language-list">
                                                        <div class="language_flag">
                                                            <img src="{{ getImage(getFilePath('language') . '/' . $lang->image, getFileSize('language')) }}" alt="flag">
                                                        </div>
                                                        <p class="language_text">{{ __($lang->name) }}</p>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif --}}
                            @guest
                                <a class="header-user-btn" href="{{ route('user.login') }}"><i
                                        class="las la-sign-in-alt"></i> @lang('Đăng nhập')</a>
                                @if (gs('registration'))
                                    <a class="header-user-btn ms-2" href="{{ route('user.register') }}">
                                        <i class="las la-user"></i> @lang('Đăng ký')
                                    </a>
                                @endif
                            @endguest

                            @auth
                                <a class="header-user-btn" href="{{ route('user.home') }}"><i class="la la-dashboard"></i>
                                    @lang('Thống kê')</a>
                                <a class="header-user-btn ms-2" href="{{ route('user.logout') }}"><i
                                        class="las la-sign-out-alt"></i> @lang('Đăng xuất')</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}">
                    <img alt="logo" src="{{ siteLogo('dark') }}">
                </a>
                <button aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
                    class="navbar-toggler ms-auto" data-bs-target="#navbarSupportedContent" data-bs-toggle="collapse"
                    type="button">
                    <div class="navbar-toggler__icon">
                        <i class="las la-bars"></i>
                    </div>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto">
                        <li><a class="{{ menuActive('home') }}" href="{{ route('home') }}">@lang('Trang chủ')</a></li>
                        @if (@$pages)
                            @foreach ($pages as $data)
                                <li>
                                    <a class="@if (request()->url() == route('pages', [$data->slug])) active @endif"
                                        href="{{ route('pages', [$data->slug]) }}">{{ __(strtoupper($data->name)) }}</a>
                                </li>
                            @endforeach
                        @endif
                        <li>
                            <a class="{{ menuActive('blog') }}" href="{{ route('blog') }}">@lang('Cập nhật')</a>
                        </li>
                        <li>
                            <a class="{{ menuActive('contact') }}" href="{{ route('contact') }}">@lang('Liên hệ')</a>
                        </li>
                        <li>
                            <a class="{{ menuActive('contact') }}" href="#" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                aria-controls="offcanvasRight">
                                <i class="far fa-heart fa-lg"></i>

                                <span class="notification-badge">{{ $wishLists->count() ?? 0 }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="nav-right justify-content-xl-end ps-0 ps-xl-5">
                        <a class="btn btn-sm btn--base" href="{{ route('room.types') }}"><i
                                class="las la-hand-point-right"></i> ĐẶT PHÒNG</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>


<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">Danh sách yêu thích</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="list-group append-child">
            @forelse ($wishLists as $data)
                <div class="border rounded p-2 d-flex align-items-center mb-3 rooms room-{{ $data->room->id }} ">
                    <input type="checkbox" class="form-check-input me-2 room-checkbox" data-price="1500000"
                        id="room-{{ $loop->index }}">

                    <img src="{{ \Storage::url($data->room->roomType->main_image) }}" alt="Room Image" class="rounded"
                        style="max-width: 20%; object-fit: cover;">

                    <div class="ms-3">
                        <h5 class="mb-1">{{ $data->room->room_number }}</h5>
                        <p class="mb-0 text-muted">Giá: {{ $data->room->prices[0]['price'] }}</p>
                    </div>
                </div>
            @empty
                <div id="wishlist-message" class="alert alert-warning text-center">
                    <i class="las la-exclamation-circle"></i> Chưa có phòng nào được yêu thích.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Khu vực tổng giá -->
    <div class="total-price d-flex justify-content-between align-items-center">
        <div>
            <input type="checkbox" class="form-check-input" id="select-all">
            <label for="select-all" class="ms-2">Tất cả</label>
        </div>
        <div class="d-flex align-items-center">
            <p class="">Tổng thanh toán:</p>
            <p id="total-price" class="fw-bold mx-3">0 VNĐ</p>
            <button class="btn btn-sm btn--base">ĐẶT NGAY</button>
        </div>
    </div>
</div>
