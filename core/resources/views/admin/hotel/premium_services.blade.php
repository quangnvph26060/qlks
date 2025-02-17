@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <div class="d-flex justify-content-between mb-3" style="float: right;">
                    {{-- <div class=" input-group " style="justify-content: end;">
                        <form id="search-premium" action="{{route('admin.hotel.premium.service.all')}}" method="GET">
                            <input class="searchInput" name="name"  value="{{$input}}"id="searchInput"  type="search" placeholder="Tìm kiếm...">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-search"></i>
                            </button>
                        </form>
                    </div> --}}

                    @can('admin.hotel.service.search')
                        @push('breadcrumb-plugins')
                                <!-- Form tìm kiếm trực tiếp -->
                                <form action="{{ route('admin.hotel.premium.service.all') }}" method="GET" id="searchForm" class="mx-5">
                                    <div class="input-group mt-1">
                                        <input
                                            type="search"
                                            class="searchInput"
                                            name="name"
                                            id="searchInput"
                                            value="{{$input}}"
                                            placeholder="Tìm kiếm..."
                                            onsearch="handleSearchClear()" style="padding: .375rem .75rem;height:auto">
                                        <!-- Nút tìm kiếm -->
                                        <button type="button" class="btn btn-primary">
                                            <i class="las la-search"></i>
                                        </button>
                                    </div>
                                </form>
                            @endpush
                        @endcan
                    {{-- <div class="input-group" style="justify-content: end;">
                        <input class="searchInput"
                        type="search" placeholder="Tìm kiếm...">
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-search"></i>
                        </button>
                    </div> --}}
                </div>
            </div>
            <div id="pagination" class="mt-3">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive" style="overflow-x: visible;">
                        <table class="table--light table">
                            <thead>
                                <tr>
                                @can(['admin.hotel.premium.service.save', 'admin.hotel.premium.service.status'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                    <th>@lang('STT')</th>
                                    <th>@lang('Mã dịch vụ')</th>
                                    <th>@lang('Tên dịch vụ')</th>
                                    <th>@lang('Giá')</th>
                                    <th>@lang('Trạng thái')</th>
                              
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($premiumServices as $premiumService)
                                    <tr>
                                    <td style="width:20px;">
                                    <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
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
                                        <td style="text-align:right">{{ $loop->iteration }}</td>
                                        <td>{{ $premiumService->code ?? 'Chưa có mã dịch vụ' }}</td>
                                        <td>{{ __($premiumService->name) }}
                                        </td>

                                        <td>
                                            {{ showAmount($premiumService->cost) }}
                                        </td>

                                        <td style="width:50px;text-align: center" class="status-hotel">
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
                @if ($premiumServices->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($premiumServices) }}
                    </div>
                @endif
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
    @push('breadcrumb-plugins')
        <button class="btn btn-sm btn-outline--primary p-2 cuModalBtn" data-modal_title="@lang('Thêm mới dịch vụ cao cấp')" type="button">
            <i class="las la-plus"></i>
        </button>
    @endpush
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
