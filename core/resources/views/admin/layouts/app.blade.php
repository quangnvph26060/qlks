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
        <div class="menu-header" style="margin-left: 250px;display:none">
                <div class="bg--dark menu-item-container" id="menu-item-container" style="border-radius: 2px;display: grid;grid-template-columns: 96% 4%">
                <div>
                    <div class="paddles">
                        <button class="left-paddle paddle" id="arrow-left">
                            &lt;
                        </button>
                    </div>
                        <div class="p-globalNavi__list" id="menu" style="padding: 15px 0px 15px 0px;margin: 0 auto;width: 95%">
                            
                                <li class="p-globalNavi__item" id="home" style="display: none !important;">
                                </li>
                        

                            </div>
                            <div class="paddles">
                            <button class="right-paddle paddle" id="arrow-right">
                                &gt;                    
                            </button>
                            </div>
                        </div>
                        <div>
                    <button data-toggle="menu" class="btn btn-primary btn-menu" id="btn-menu" style="margin-top: 15px;display:none">
                        <i class="fa fa-list"></i></button>
                          <div id="list-menu" class="main_menu_top">
                        <ul>
                        {{-- <li class="border-bottom p-1"><a href="{{ route('admin.system.update') }}">Update Available</a></li>
                        <li class="border-bottom p-1"><a href="{{ route('admin.request.booking.all') }}">Yêu cầu đặt phòng</a></li>
                        <li class="border-bottom p-1"><a href="{{ route('admin.request.booking.all') }}">Services</a></li>
                        <li class="border-bottom p-1"><a  href="{{ route('home') }}">Visit Website</a></li>
                        <li class="border-bottom p-1"><a  href="{{ route('admin.profile') }}">Hồ sơ</a></li>
                        <li class="border-bottom p-1"><a  href="{{ route('admin.password') }}">Mật khẩu</a></li> --}}
                        <li class="p-1"><a class="text-white" href="{{ route('admin.logout') }}">Đăng xuất</a></li>

                        </ul>
                    </div>
                    </div>
                
                    </div>
                   
                    </div>
            
                </div>
                   
               </div>
             
            </div>
      
            <div class="body-wrapper">
                <div class="main">
                    <div id="frame" style="">
                    </div>
                </div>
                <div>

                    @stack('topBar')
                    @include('admin.partials.breadcrumb')

                    @yield('panel')

                </div><!-- bodywrapper__inner end -->
            </div><!-- body-wrapper end -->
        </div>
    </div>
@endsection
<style>
    .main_menu_top li {
        color: white !important;
        background:#005AA1;
        border-radius: 5px;
    }
    .main_menu_top{
        width: 150px;
      
         position: fixed;
        z-index: 9999999;
        margin-top:15px;
        display: none;
       right: 20px;
    }
    .menu-item-container {
        position: relative;
        overflow-x: hidden;
        overflow-y: hidden;
        margin-left: 1%;
        gap: 5px;

        scrollbar-width: thin; 
        scrollbar-color: #071251 transparent;
    }
    button:focus {
        outline: 0 !important;
    }
    #menu {
        white-space: nowrap;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
    }
    .paddle {
        position: absolute;
        top: 0;
        border: none;
        cursor: pointer;
        background-color: transparent;
        font-size: 40px;
        font-weight: bold;
        color:white;
    }
    .left-paddle {
        left: 0;
    }
    .right-paddle {
        right: 0;
        margin-right: 4%
    }
    .hidden {
        display: none;
    }
    .p-globalNavi__item{
        border: 1px solid;
    }
    .p-globalNavi__link{
        margin: 6px;
    }
</style>
