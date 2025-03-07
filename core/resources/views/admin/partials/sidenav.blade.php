@php
    $sideBarLinks = json_decode($sidenav);
@endphp

<div class="sidebar bg--dark" id="sidebar">
     <button class="toggle-btn" id="toggle-btn">&#8592;</button>
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('admin.dashboard') }}" class="sidebar__main-logo"><img src="{{ siteLogo() }}"
                    alt="image"></a>
        </div>
        <div class="sidebar__menu-wrapper">
            <ul class="sidebar__menu">
                @foreach ($sideBarLinks as $key => $data)
                    @if (@$data->header && auth()->guard('admin')->id() == 1)
                        <li class="sidebar__menu-header">{{ __($data->header) }}</li>
                    @endif

                    @if (@$data->submenu)
                        @can(array_column($data->submenu, 'route_name'))
                            <li class="sidebar-menu-item sidebar-dropdown">
                            <a href="javascript:void(0)" onclick="return loadIframe(this.href);" class="{{ menuActive(@$data->menu_active, 3) }}" >

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
                                            <li class="sidebar-menu-item {{ menuActive(@$menu->menu_active) }} "
                                                data-route="{{ $menu->route_name }}">

                                                
                                                <!-- <a href="{{ route(@$menu->route_name, $submenuParams) }}" class="nav-link"> -->
                                                <a href="{{ route(@$menu->route_name, $submenuParams) }}" onclick="return loadIframe(this.href);" class="nav-link">


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
                        if (@$data->params) {
                            foreach ($data->params as $paramVal) {
                                $mainParams[] = array_values((array) $paramVal)[0];
                            }
                        }
                    @endphp
                    @can(@$data->route_name)
                        <li class="sidebar-menu-item {{ menuActive(@$data->menu_active) }}">
                            <a href="{{ route(@$data->route_name, $mainParams) }}" class="nav-link">
                                <i class="menu-icon {{ $data->icon }}"></i>
                                <span class="menu-title">{{ __(@$data->title) }}</span>
                                @php $counter = @$data->counter; @endphp
                                @if (@$$counter)
                                    <span class="menu-badge bg--info ms-auto">{{ @$$counter }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan
                @endif
            @endforeach
        </ul>
    </div>
    <div class="version-info text-center text-uppercase">
        {{-- <span class="text--primary">{{ __(systemDetails()['name']) }}</span> --}}
        {{-- <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span> --}}
    </div>
</div>
</div>
<style type="text/css">
    .sidebar .toggle-btn {
  position: absolute;
  right: 0px; /* Vị trí của mũi tên */
  font-size: 30px;
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  z-index: 999999;
}
.navbar-wrapper.shifted {
  margin-left: 0 !important; /* Di chuyển nội dung chính khi sidebar bị ẩn */
}
.body-wrapper.shifted {
  margin-left: 0 !important; /* Di chuyển nội dung chính khi sidebar bị ẩn */
}
.sidebar.closed {
  transform: translateX(-250px); /* Ẩn sidebar bằng cách dịch chuyển nó sang trái */
}

</style>

@push('script')

<script>
    $('ul > li > a.nav-link').click(function (e) {
        $('.menu-header').css('display','block');

        e.preventDefault();
            var seen = {};
            var getItem = $(this).text();
            var getURL = $(this).attr('href');
            if (seen[getURL]) {
                ($this).empty();
            } else {
                $('.p-globalNavi__item').removeClass('m-active');
                var myEle = document.getElementById(getURL);
                var parts = getURL.split('/');
                var lastSegment = parts.pop() || parts.pop();
                if (myEle == null) {
                    
                    $("#home").after(" <li class=\"p-globalNavi__item m-active\"><a class=\"p-globalNavi__link text-white tabs\" id=" + getURL + " >" + getItem + "<i class=\"close-tab fa fa-close\"></i><span class=\"sr-only\">(current)<\/span><\/a><\/li> ");
                } else {
                    document.getElementById(getURL).click();
                    document.getElementById(getURL).scrollIntoView({ behavior: 'smooth' });
                }
            }
            $('.top-menu').css('display','none');
            $('.top-menu').removeClass('d-flex');
            $('#dropdownButton').css('display','block');
            const scrollLeftButton = document.getElementById('arrow-left');
            const scrollRightButton = document.getElementById('arrow-right');
            const scrollContent = document.getElementById('menu');
            const maxScrollLeft = scrollContent.scrollWidth - scrollContent.clientWidth;
            const currentScrollLeft = scrollContent.scrollLeft;

                if (currentScrollLeft === 0) {
                scrollLeftButton.style.display = 'none';
            } else {
                scrollLeftButton.style.display = 'block';
            }

            // Nếu cuộn đến cuối (không thể cuộn phải nữa), ẩn mũi tên phải
            if (currentScrollLeft === maxScrollLeft) {
                scrollRightButton.style.display = 'none';
            } else {
                scrollRightButton.style.display = 'block';
            }
            $('.btn-menu').css('display','block');
        });
    function loadIframe(url) {
        var myEle = document.getElementById(url);
        $('iframe').css({'z-index': '999', 'display': 'none'});
        var parts = url.split('/');
        var lastSegment = parts.pop() || parts.pop();
        if (myEle == null) {
            $("#frame").append('<iframe name="main" id="' + lastSegment + '" class="frame" src="' + url + '" style="width:calc(100% - 265px);position: fixed;z-index: 999; height: 100%;margin-top: 1px;border: none"></iframe>');
        } else {
            $('iframe#' + lastSegment + '').css('z-index', '10000');
      }
            
    }
    $(document).on('click', '.p-globalNavi__link', function () {
        $('.p-globalNavi__item').removeClass('m-active');
        var id = $(this).attr('id');
        $(this).parent().addClass('m-active');
        $('iframe').css({'z-index': '999', 'display': 'none'});
        var parts = id.split('/');
        var lastSegment = parts.pop() || parts.pop();
        $('iframe#' + lastSegment + '').css({'z-index': '10000', 'display': 'block'});
    });
    $(document).on('click', '.close-tab', function () {
        var parent = $(this).parent().prop('id');
        var parts = parent.split('/');
        var lastSegment = parts.pop() || parts.pop();
        $('iframe#' + lastSegment + '').remove();
        document.getElementById(parent).parentElement.remove();
        const menu = document.querySelector('.p-globalNavi__list');
                const navItemExists = menu.querySelector('.p-globalNavi__item') !== null;

                if (navItemExists) {
                  $(this).closest('.p-globalNavi__item').remove();
                } else {
                  $('.top-menu').css('display','flex');
                //   $('.navbar__right').css({'display':'flex','width':'70%','margin-top':'20px'});
                  $('.btn-menu').css('display','none');
                  $('.p-globalNavi__list').css('display','none');
                }
    });
    $(".sidebar-submenu li").on("click", function () {
        if ($(this).hasClass('m-active')) {
            $(this).removeClass('m-active');
            $('.has-arrow').removeClass('active');
        } else {
            $('.has-arrow').removeClass('active');
            $(".sidebar-submenu li").removeClass('m-active');
            $(this).addClass('m-active');
            $(this).find('.has-arrow').addClass('active');
        }
       
    });

    // Hàm tính toán và áp dụng chiều rộng

</script>
@endpush