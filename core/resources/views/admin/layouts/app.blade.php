@extends('admin.layouts.master')
@section('content')
@php
    $sidenav = file_get_contents(resource_path('views/admin/partials/sidenav.json'));
@endphp
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('admin.partials.sidenav')
        @include('admin.partials.topnav')
        <div class="container-fluid px-3 px-sm-0">
            <div class="menu-header" style="margin-left: 250px">
                <div class="bg-white menu-item-container" id="menu-item-container" style="border-radius: 2px;display: grid;grid-template-columns: 98% 2%">
                    <div class="p-globalNavi__list" id="menu" style="padding: 3px 0px 3px 0px;margin-left: 5px;width: 100%">
                        <li class="p-globalNavi__item" id="home" style="display: none !important;">
                        </li>
{{--                        <div class="paddles">--}}
{{--                            <button class="left-paddle paddle hidden">--}}
{{--                                <i class="fa fa-arrow-left"></i>--}}
{{--                            </button>--}}
{{--                            <button class="right-paddle paddle">--}}
{{--                                <i class="fa fa-arrow-right"></i>--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </div>
                    <li class="menu-toggle p-globalNavi__item" id="dropdownButton" style="right: 0;z-index: 11000;display:none;background: white !important;color: black;cursor: pointer">
                        &#9776;
                    </li>
                    <nav class="navbar" style="margin-top: 25px;position: fixed;right: 0;z-index: 100000;background: currentColor;">
                        <ul class="menu" style="display: none;" id="dropdownMenu">
                            <li class="border-bottom"><a href="#">Yêu cầu đặt phòng</a></li>
                            <li class="border-bottom"><a href="#">Visit Website</a></li>
                            <li class="border-bottom"><a href="#">Thiết lập hệ thống</a></li>
                            <li class="border-bottom"><a href="#">Hồ sơ</a></li>
                            <li><a href="#">Đổi mật khẩu</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="body-wrapper">
                <div class="main">
                    <div class="container-fluid" id="frame" style="">
                    </div>
                </div>
                <div class="bodywrapper__inner">

                    @stack('topBar')
                    @include('admin.partials.breadcrumb')

                    @yield('panel')

                </div><!-- bodywrapper__inner end -->
            </div><!-- body-wrapper end -->
        </div>
    </div>
@endsection
<style>
    .menu-item-container {
        position: relative;
        overflow-x: hidden;
        overflow-y: hidden;
        margin-left: 1%;
    }

    button:focus {
        outline: 0 !important;
    }

    #menu {
        white-space: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;

    }
    .paddle {
        position: absolute;
        top: 0;
        bottom: 0;
        border: none;
        cursor: pointer;
        height: 45px;
        background-color: transparent;
        font-size: 30px;
        font-weight: bold;
    }

    .left-paddle {
        left: 0;
    }

    .right-paddle {
        right: 0;
    }

    .hidden {
        display: none;
    }

</style>
@push('script')
    <script>
        document.getElementById('dropdownButton').addEventListener('click', function() {
            var dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        window.onclick = function(event) {
            if (!event.target.matches('#dropdownButton')) {
                var dropdowns = document.getElementsByClassName('dropdown-content');
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === 'block') {
                        openDropdown.style.display = 'none';
                    }
                }
            }
        };
    </script>
@endpush
