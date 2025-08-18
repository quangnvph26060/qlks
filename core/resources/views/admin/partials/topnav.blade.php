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
    <div style="align-items: baseline;" class="navbar__right">


        <ul class="navbar__action-list d-flex justify-content-between">
            <button type="button" class="res-sidebar-open-btn me-3 mb-3"><i class="las la-bars"></i></button>
            <div id="tabs" class="tab-container" style="display: flex; gap: 8px; margin-bottom: 10px;"></div>
            <div id="frame" style="position: relative;"></div>
            <div class="d-flex" style="margin-bottom: 12px">
                <li>
                    <a class="btn btn--danger booking-req me-2 me-md-3" style="white-space: nowrap;"
                        href="{{ route('admin.receptionist.booking.receptionist') }}">
                        Yêu cầu đặt phòng
                    </a>
                </li>
                <li>
                    <a class="btn btn--danger booking-req me-2 me-md-3"target="_blank" style="white-space: nowrap;"
                        href="{{ route('admin.receptionist.booking.receptionist') }}">
                        Lễ tân
                    </a>
                </li>
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
            </div>
        </ul>
    </div>
</nav>
<!-- navbar-wrapper end -->
<style scoped>
    .tab-btn {
        border: 1px solid #fff !important;
        background: #333;
        color: white;
        padding: 6px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .la-times {
        font-size: 13px !important;
    }

    .tab-btn.active-tab {
        background: #004C87;
    }

    .tab-btn:hover {
        background: #004C87;
    }

    .nav-item {
        border: 1px solid;
        position: relative;
        border-radius: 6px;
    }

    .nav-tabss {
        position: relative;
        bottom: -16px;
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
        background: red;
        color: white;
        font-weight: bold;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
    }

    .iframe-main {
        width: calc(100% - 265px);
        right: 1;
        position: fixed;
        z-index: 999;
        margin-top: 33px;
        border: none;
        display: none;
    }

    @media (max-width: 768px) {
        .iframe-main {
            width: 100%;
        }
    }

    .tab-container {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 4px;
        /* Để không bị cắt shadow/tab */
        scrollbar-width: none;
        /* Firefox */
    }

    /* Mobile (dưới 768px) */
    @media (max-width: 768px) {
        .tab-container {
            display: none !important;
        }
    }

    /* Để ẩn scrollbar nếu muốn, hoặc tùy chỉnh cho đẹp */
    .tab-container::-webkit-scrollbar {
        display: none;
    }

    .tab-container::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 10px;
    }




    .nav-item.active {
        background-color: #4634ff59 !important;
        color: white;
    }

    .main__tabs-list {
        display: flex;
        /*    width: 90%;
*/
        overflow-x: auto;
        /*    white-space: nowrap;
*/
        padding-bottom: 5px;
        scrollbar-width: thin;
        scrollbar-color: #071251 transparent;
    }

    .nav-item {
        flex: 0 0 auto;
        /* Đảm bảo các <li> không bị co lại */
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
        // var currentUrl = "{{ request()->url() }}";
        //   var currentPath = "{{ request()->path() }}";
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

                        var html = `<li class="nav-item click-tabs mt-2 ${routeName === key ? 'active': ''}" role="presentation">
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
                let activeDataIds = JSON.parse(localStorage.getItem('activeDataIds') ?? "");
                activeDataIds = activeDataIds.filter(item => {
                    let key = Object.keys(item)[0]; // Lấy key của object
                    return key !== dataKey; // Chỉ giữ lại những object KHÔNG có key trùng dataKey
                });
                localStorage.setItem('activeDataIds', JSON.stringify(activeDataIds));
                $(this).closest('.nav-item').remove();
                // const menu = document.querySelector('.p-globalNavi__list');
                // const navItemExists = menu.querySelector('.p-globalNavi__item') !== null;

                // if (navItemExists) {
                //   $(this).closest('.p-globalNavi__item').remove();
                // } else {
                //   $('.navbar__action-list').css('display','flex');
                //   $('.navbar__right').css({'display':'flex','width':'70%','margin-top':'20px'});
                //   $('.btn-menu').css('display','none');
                // }
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

        $('ul > li > a.nav-link').click(function(e) {
            $('.menu-header').css('display', 'block');

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

                    $("#home").after(
                        " <li class=\"p-globalNavi__item m-active\"><a class=\"p-globalNavi__link text-white tabs\" id=" +
                        getURL + " >" + getItem +
                        "<i class=\"close-tab fa fa-close\"></i><span class=\"sr-only\">(current)<\/span><\/a><\/li> "
                    );
                } else {
                    document.getElementById(getURL).click();
                    document.getElementById(getURL).scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
            // $('.top-menu').css('display', 'none');
            $('.top-menu').removeClass('d-flex');
            $('#dropdownButton').css('display', 'block');
            // const scrollLeftButton = document.getElementById('arrow-left');
            // const scrollRightButton = document.getElementById('arrow-right');
            // const scrollContent = document.getElementById('menu');
            // const maxScrollLeft = scrollContent.scrollWidth - scrollContent.clientWidth;
            // const currentScrollLeft = scrollContent.scrollLeft;

            // if (currentScrollLeft === 0) {
            //     scrollLeftButton.style.display = 'none';
            // } else {
            //     scrollLeftButton.style.display = 'block';
            // }

            // // Nếu cuộn đến cuối (không thể cuộn phải nữa), ẩn mũi tên phải
            // if (currentScrollLeft === maxScrollLeft) {
            //     scrollRightButton.style.display = 'none';
            // } else {
            //     scrollRightButton.style.display = 'block';
            // }
            // $('.btn-menu').css('display', 'block');
        });


        // luôn hiển thị ra thống kê
        $(document).ready(function() {
            // Kiểm tra nếu chưa có tab dashboard thì tạo mới
            if ($('#tab-dashboard').length === 0) {
                const menuTitle = "Thống kê"; // hoặc lấy từ đâu đó nếu cần
                $('#tabs').css({
                    'position': 'absolute',
                    'margin-bottom': '10px',
                    'left': '34px'
                });
                const baseUrl = `${window.location.protocol}//${window.location.hostname}`;
                const dashboardUrl = `${baseUrl}/admin/dashboard`;

                // Thêm tab dashboard
                $('#tabs').append(`
                        <button id="tab-dashboard" class="tab-btn" onclick="switchTab('dashboard')"style="height:35px">
                            ${menuTitle} <span onclick="closeTab(event, 'dashboard')" style="margin-left: 4px; cursor: pointer;"> <i class="las la-times"></i></span>
                        </button>
                    `);

                // Thêm iframe dashboard width: calc(100% - 265px);
                $('#frame').append(`
                    <iframe name="main" id="dashboard" class="frame vh-100 iframe-main" 
                        src="${dashboardUrl}" 
                        >
                      
                    </iframe>
                `);
            }

            // Hiển thị tab dashboard
            switchTab('dashboard');

            // Nếu bạn muốn load lại các tab khác từ localStorage thì làm tiếp ở đây...
        });

        function loadIframe(url) {
            if (url === "javascript:void(0)") {
                return; // Nếu URL là "javascript:void(0)", không xử lý
            }
            $('#tabs').css({
                'position': '',
                'margin-bottom': '',
                'left': ''
            });

            $('.sidebar').removeClass('open'); // click chuyển trang duới mobi thì ẩn sibar đi 
            const parts = url.split('/');


            const tabId = parts.pop() || parts.pop();
            const iframeId = tabId;
            const menuTitle = $("a[href='" + url + "']").find(".menu-title").text();

            // Kiểm tra tab tồn tại chưa
            if ($('#tab-' + tabId).length === 0) {
                // Thêm tab mới
                $('#tabs').append(`
                    <button id="tab-${tabId}" class="tab-btn" onclick="switchTab('${iframeId}')" style="height:35px">
                        ${menuTitle} <span onclick="closeTab(event, '${iframeId}')" style="margin-left: 4px; cursor: pointer;"> <i class="las la-times"></i></span>
                    </button>
                `);

                // Thêm iframe mới width: calc(100% - 265px);
                $('#frame').append(`
                    <iframe name="main" id="${iframeId}" class="frame vh-100 iframe-main" 
                        src="${url}"
                       >
                    </iframe>
                `);


            }



            // Chuyển sang tab đó
            switchTab(iframeId);
        }

        function switchTab(iframeId) {
            $('.frame').hide();
            $('iframe#' + iframeId).show();
            // Đổi style active cho tab
            $('.tab-btn').removeClass('active-tab');
            $('#tab-' + iframeId).addClass('active-tab');
        }

        function closeTab(event, iframeId) {
            event.stopPropagation(); // Ngăn chặn sự kiện bọt biển

            $('#' + iframeId).remove(); // Xóa iframe
            $('#tab-' + iframeId).remove(); // Xóa tab
        }




        $(document).on('click', '.p-globalNavi__link', function() {
            $('.p-globalNavi__item').removeClass('m-active');
            var id = $(this).attr('id');
            $(this).parent().addClass('m-active');
            $('iframe').css({
                'z-index': '999',
                'display': 'none'
            });
            var parts = id.split('/');
            var lastSegment = parts.pop() || parts.pop();
            $('iframe#' + lastSegment + '').css({
                'z-index': '10000',
                'display': 'block'
            });
        });
        $(document).on('click', '.close-tab', function() {
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

                $('.top-menu').css('display', 'flex');
                //   $('.navbar__right').css({'display':'flex','width':'70%','margin-top':'20px'});
                $('.btn-menu').css('display', 'none');
                $('.p-globalNavi__list').css('display', 'none');
            }
        });
        $(".sidebar-submenu li").on("click", function() {
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
        const tabContainer = document.querySelector('.tab-container');

        let isDown = false;
        let startX;
        let scrollLeft;

        tabContainer.addEventListener('mousedown', (e) => {
            isDown = true;
            tabContainer.classList.add('dragging');
            startX = e.pageX - tabContainer.offsetLeft;
            scrollLeft = tabContainer.scrollLeft;
        });

        tabContainer.addEventListener('mouseleave', () => {
            isDown = false;
            tabContainer.classList.remove('dragging');
        });

        tabContainer.addEventListener('mouseup', () => {
            isDown = false;
            tabContainer.classList.remove('dragging');
        });

        tabContainer.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - tabContainer.offsetLeft;
            const walk = (x - startX) * 2; // tốc độ kéo
            tabContainer.scrollLeft = scrollLeft - walk;
        });
    </script>
@endpush
