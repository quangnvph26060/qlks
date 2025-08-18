<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ gs()->siteName($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/detail.css') }}">

    @stack('style')
    <style>
        html,
        body {
            touch-action: manipulation;
        }
    </style>
</head>

<body>
    @yield('content')
    <div>

    </div>
    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/bootstrap-toggle.min.js') }}"></script>

    @include('partials.notify')
    @stack('script-lib')

    <script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>

    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    <script
        src="{{ asset('assets/admin/js/cu-modal.js') }}?v={{ filemtime(public_path('assets/admin/js/cu-modal.js')) }}">
    </script>
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- LOAD NIC EDIT --}}
    <script>
        "use strict";
        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });

        (function($) {
            document.addEventListener('gesturestart', function(e) {
                e.preventDefault();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
                if ($(this).hasClass('nicEdit-main')) {
                    $(this).focus();
                } else {
                    $(this).parent().next('div').find('.nicEdit-main').focus();
                }
            });

            $('.breadcrumb-nav-open').on('click', function() {
                $(this).toggleClass('active');
                $('.breadcrumb-nav').toggleClass('active');
            });

            $('.breadcrumb-nav-close').on('click', function() {
                $('.breadcrumb-nav').removeClass('active');
            });

            if ($('.topTap').length) {
                $('.breadcrumb-nav-open').removeClass('d-none');
            }

        })(jQuery);

        $('#arrow-right').on('click', function() {
            const scrollContainer = document.querySelector('.p-globalNavi__list');
            scrollContainer.scrollBy({
                left: window.innerWidth / 3, // Cuộn sang phải một nửa chiều rộng cửa sổ
                behavior: 'smooth' // Cuộn mượt mà
            });
        });

        $('#arrow-left').on('click', function() {
            const scrollContainer = document.querySelector('.p-globalNavi__list');
            scrollContainer.scrollBy({
                left: -window.innerWidth / 3, // Cuộn sang phải một nửa chiều rộng cửa sổ
                behavior: 'smooth' // Cuộn mượt mà
            });
        });
        const sidebar = document.getElementById('sidebar');
        const mainMenu = document.querySelector('.navbar-wrapper');
        const mainContent = document.querySelector('.body-wrapper');


        const toggleButton = document.getElementById('toggle-btn');
        $('#toggle-btn').on('click', () => {
            sidebar.classList.toggle('closed');
            mainContent.classList.toggle('shifted');
            mainMenu.classList.toggle('shifted');

            // Thay đổi hướng mũi tên khi sidebar ẩn hiện
            if (sidebar.classList.contains('closed')) {
                $('iframe').css({
                    'width': '100%'
                });
                $('.menu-header').css('margin-left', '0px');
                toggleButton.innerHTML = '&#8594;'; // Mũi tên sang trái khi sidebar ẩn
            } else {
                $('iframe').css('width', 'calc(100% - 265px)');
                $('.menu-header').css('margin-left', '250px');

                toggleButton.innerHTML = '&#8592;'; // Mũi tên sang phải khi sidebar hiện

            }
        });

        const scrollLeftButton = document.getElementById('arrow-left');
        const scrollRightButton = document.getElementById('arrow-right');
        const scrollContent = document.getElementById('menu');



        // Kiểm tra lại khi trang được tải và khi nội dung thay đổi
        $('.paddle').on('click', function() {
            const maxScrollLeft = scrollContent.scrollWidth - scrollContent.clientWidth;
            const currentScrollLeft = scrollContent.scrollLeft;

            // Nếu cuộn đến đầu (không thể cuộn trái nữa), ẩn mũi tên trái
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

        });
        $('#btn-menu').on('click', function() {
            var menu = document.getElementById('list-menu');
            // Kiểm tra trạng thái hiển thị và toggle
            if (menu.style.display === 'none') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        });
    </script>
    <script type="text/javascript">
        $('.iziToast-title').html('<p>Thông báo</p>');
    </script>
    @stack('script')

</body>

</html>
