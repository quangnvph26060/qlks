@extends('admin.layouts.master_iframe')

@section('panel')
    <div class="row">
    @push('breadcrumb-plugins')

        <div class="col-md-12 d-flex">
       
            <a class="mr-1" href="{{ route('admin.hotel.premium.service.all') }}">
            <button class="btn btn--primary" data-modal_title="Làm mới">
                                <i class="fa fa-repeat p-1"></i>
            </button>
                </a>
                <a>
                    <button class="btn btn--primary cuModalBtn" data-modal_title="@lang('Thêm mới dịch vụ cao cấp')" type="button"
                            style="margin-left:10px">
                            <i class="las la-plus p-1"></i>
                        </button>
                </a>
                <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.premium.service.all')}}" method="GET" id="searchForm">
                        <div class="form-group position-relative mb-0">
                             <input placeholder="Nhập tên dịch vụ"
                                            style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                            name="name"
                                id="searchInput"
                                value="{{ $input}}">
                                    
                                <a>
                                    <button type="submit" class="btn btn--primary">
                                            <i class="las la-search p-1"></i>
                                    </button>
                                </a>
                         </div>
                 </form>      

       
            </div>
            <div class="pager-wrap">
                            <div class="k-widget d-flex">
                                <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                                    {{ $premiumServices->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
            @endpush

          
        </div>
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                <div class="table-responsive--md table-responsive" style="overflow-x: auto;">
                <table class="table--light style--two table" >
                            <thead>
                                <tr>
                                @can(['admin.hotel.premium.service.save', 'admin.hotel.premium.service.status'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                    <th style="width:50px">@lang('STT')</th>
                                    <th>@lang('Mã dịch vụ')</th>
                                    <th>@lang('Tên dịch vụ')</th>
                                    <th>@lang('Giá')</th>
                                    <th>@lang('Trạng thái')</th>
                              
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($premiumServices as $id => $premiumService)
                                    <tr>
                                    <td style="width:20px;">
                                    <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu" style="position:fixed">
                                            <div class="dropdown-item">
                                                 <button class="btn btn-sm btn-outline--primary cuModalBtn edit-service"
                                                            data-has_status="1" data-modal_title="@lang('Update Premium Service')"
                                                            data-resource="{{ $premiumService }}" type="button" style="    color: black !important;border: none;">
                                                            Sửa dịch vụ
                                                </button>
                                            </a>

                                        </div>
                                          
                                         <div class="dropdown-item booked_room_detail"> <button class="btn-delete icon-delete-room"
                                                data-id="{{ $premiumService->id }}" data-modal_title="@lang('Xóa khách hàng')" type="button"
                                                data-pro="0">Xóa dịch vụ</div>
                              
                                        </div>
                                     </td>
                                        <td style="text-align:right">
                                        @php
                                        $stt = $premiumServices->total() - ($premiumServices->currentPage() - 1) * $premiumServices->perPage() - $id;
                                        @endphp
                                        {{ $stt }}</td>
                                        <td>{{ $premiumService->code ?? 'Chưa có mã dịch vụ' }}</td>
                                        <td>{{ __($premiumService->name) }}
                                        </td>

                                        <td>
                                            {{ showAmount($premiumService->cost) }}
                                        </td>

                                        <td style="width:50px;text-align: center">
                                        @if($premiumService->status == 1)
                                            <i class="fa fa-check" style="color:green;text-align: center"></i>
                                        @else
                                            <i class="fa fa-close" style="color:red;text-align: center"></i>
                                        @endif
                                        </td>
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
              
            </div><!-- card end -->
        </div>
    </div>

    {{-- Add METHOD MODAL --}}
    @can('admin.hotel.premium.service.save')
        <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.hotel.premium.service.save') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label> @lang('Mã dịch vụ')</label>
                                <input class="form-control" name="code" required type="text" value="{{ old('code') }}">
                            </div>
                            <div class="form-group">
                                <label> @lang('Tên dịch vụ')</label>
                                <input class="form-control" name="name" required type="text" value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label> @lang('Giá')</label>
                                <div class="input-group">
                                    <input class="form-control" name="cost" required step="0.01" type="number"
                                        value="{{ old('cost') }}">
                                    <span class="input-group-text"> {{ gs()->cur_text }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Lưu')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <x-confirmation-modal />
@endsection

@can('admin.hotel.premium.service.save')
  
@endcan
@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
    
    <style>
            .navbar__right{
                display: none;
            }
            #navbar-wrapper{
                padding: 0px 30px 20px;
            }
            .edit-service:hover{
                background-color: white !important;
            }
            .pagination .page-item .page-link, .pagination .page-item span{
                width: 22px !important;
                height: auto !important;
                background-color: #4634ff !important;
                color: white !important;
            }
            .pagination .page-item.active .page-link{
                background-color: #071251 !important;
            }
    </style>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            $('input[name="code"]').on('input', function() {
                this.value = this.value.toUpperCase();
            });
            $('#searchInput').on('blur', function () {
                const inputValue = $(this).val();

                    $('#search-premium').submit();

            });
            $('#search-premium').on('submit', function () {
                console.log('Submitting form with value:', $('#searchInput').val());
            });
            
        });
        $(document).ready(function() {

            $(document).on('click', '.svg-icon', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown');
                $('.menu_dropdown').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });
            $(document).on('click', '.svg_menu_check_in', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown_check_in');
                $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown_check_in').removeClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });

        });
    </script>

    <script>
        function handleSearchClear() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput.value === '') {
                window.location.href = '{{ route('admin.hotel.premium.service.all') }}';
            }
        }
    </script>
@endpush
@push('style')
   <style>
     @media (max-width: 768px) {
        #searchForm{
            order: 2;
            width: 100% !important;
            margin-top: 15px !important;

        }
        #searchForm .input-group{
            justify-content: center !important;
        }
        .breadcrumb-plugins>button{
            order: 1;
            width: 100% !important;
            margin-right: 3rem !important;
            margin-left: 3rem !important;
        }
    }
   </style>
@endpush
