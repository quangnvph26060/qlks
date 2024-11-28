@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <div class="d-flex justify-content-between mb-3" style="float: right;">
                    <div class=" input-group " style="justify-content: end;">
                        <form id="search-premium" action="{{route('admin.hotel.premium.service.all')}}" method="GET">
                            <input class="searchInput" name="name"  value="{{$input}}"id="searchInput"  type="search" placeholder="Tìm kiếm...">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-search"></i>
                            </button>
                        </form>
                    </div>
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
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light table">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Mã dịch vụ')</th>
                                    <th>@lang('Tên dịch vụ')</th>
                                    <th>@lang('Giá')</th>
                                    <th>@lang('Trạng thái')</th>
                                    @can(['admin.hotel.premium.service.save', 'admin.hotel.premium.service.status'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($premiumServices as $premiumService)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $premiumService->code ?? 'Chưa có mã dịch vụ' }}</td>
                                        <td>{{ __($premiumService->name) }}
                                        </td>

                                        <td>
                                            {{ showAmount($premiumService->cost) }}
                                        </td>

                                        <td>
                                            @php echo $premiumService->statusBadge @endphp
                                        </td>
                                        @can(['admin.hotel.premium.service.save', 'admin.hotel.premium.service.status'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.hotel.premium.service.save')
                                                        <button class="btn btn-sm btn-outline--primary cuModalBtn"
                                                            data-has_status="1" data-modal_title="@lang('Update Premium Service')"
                                                            data-resource="{{ $premiumService }}" type="button">
                                                            <i class="la la-pencil"></i>@lang('Sửa')
                                                        </button>
                                                    @endcan

                                                    @can('admin.hotel.premium.service.status')
                                                        @if ($premiumService->status == Status::DISABLE)
                                                            <button class="btn btn-sm btn-outline--success me-1 confirmationBtn"
                                                                data-action="{{ route('admin.hotel.premium.service.status', $premiumService->id) }}"
                                                                data-question="@lang('Are you sure to enable this premium service?')" type="button">
                                                                <i class="la la-eye"></i> @lang('Cho phép')
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                                data-action="{{ route('admin.hotel.premium.service.status', $premiumService->id) }}"
                                                                data-question="@lang('Are you sure to disable this premium service?')" type="button">
                                                                <i class="la la-eye-slash"></i> @lang('Ngưng hoạt động')
                                                            </button>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        @endcan
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
        <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Thêm mới dịch vụ cao cấp')" type="button">
            <i class="las la-plus"></i>@lang('Thêm mới')
        </button>
    @endpush
@endcan
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
    </script>
@endpush
