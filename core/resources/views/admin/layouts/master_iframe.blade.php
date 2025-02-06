@extends('admin.layouts.master')
@section('content')
    @php
        $sidenav = file_get_contents(resource_path('views/admin/partials/sidenav.json'));
    @endphp
        <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        <div class="container-fluid px-3 px-sm-0">
            <div class="menu-header" style="margin-left: 250px">
                <div class="bg-white menu-item-container" id="menu-item-container" style="border-radius: 2px">
                    <div id="menu">
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
                </div>
            </div>

            <div class="main">
                <div class="container-fluid" id="frame" style="">
                </div>
            </div>
            <div class="bodywrapper__inner">
                @stack('topBar')
                @include('admin.partials.breadcrumb')
                @yield('panel')
            </div><!-- bodywrapper__inner end -->
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
        overflow-y: scroll;
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
