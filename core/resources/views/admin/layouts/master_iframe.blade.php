@extends('admin.layouts.master')
@section('content')
    @php
        $sidenav = file_get_contents(resource_path('views/admin/partials/sidenav.json'));
    @endphp    
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        <div class="container-fluid no-padding-mobile px-sm-0 main_content" style="margin-bottom: 50px">
            <div class="menu-header" style="margin-left: 250px">
                <div class="bg-white menu-item-container" id="menu-item-container" style="border-radius: 2px">


                </div>
            </div>

            <div class="main">
                <div id="frame" style="">
                </div>
            </div>
            <div class="bodywrapper__inner" s>
                @stack('topBar')
                @include('admin.partials.breadcrumb')
                @yield('panel')
            </div><!-- bodywrapper__inner end -->
        </div>
        <button class="contact-button" aria-label="Liên hệ">
            <i class="fas fa-phone"></i>
        </button>
        <div id="loading-overlay">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z"
                    opacity=".5" />
                <path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z">
                    <animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite"
                        to="360 12 12" type="rotate" />
                </path>
            </svg>
        </div>
    </div>
@endsection
<style>
    .contact-button {
        position: fixed;
        bottom: 90px;
        right: 20px;
        background-color: #007bff;
        /* màu xanh bootstrap */
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .contact-button:hover {
        background-color: #0056b3;
    }

    .main_content {
        height: auto !important;
        /* chiều cao tự động theo nội dung */
        max-height: none !important;
        /* bỏ giới hạn chiều cao tối đa */
        overflow: visible !important;
        /* hiển thị hết phần nội dung */
    }

    .menu-item-container {
        position: relative;
        overflow-x: hidden;
        overflow-y: hidden;
        margin-left: 1%;
    }

    .bodywrapper__inner {
        height: auto !important;
        max-height: auto !important;

    }

    button:focus {
        outline: 0 !important;
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
