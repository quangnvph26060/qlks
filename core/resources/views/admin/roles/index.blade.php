@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">

                            <thead>
                                <tr>
                                    @can('admin.roles.edit')
                                        <th class="w-10">@lang('Hành động')</th>
                                    @endcan
                                    <th>@lang('Tên quyền')</th>
                                    <th>@lang('Ngày tạo')</th>

                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        @can('admin.roles.*')
                                            <td>
                                                <div class="dropdown">
                                                    <!-- Nút ba chấm -->
                                                    <button class="btn btn-link p-0 border-0 text-dark" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="las la-ellipsis-v fs-5"></i>
                                                    </button>

                                                    <!-- Dropdown menu -->
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @can('admin.roles.edit')
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.roles.edit', $role->id) }}">
                                                                    Sửa
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('admin.roles.delete')
                                                            <li>
                                                                <a class="dropdown-item  btn-delete-role"
                                                                    data-id="{{ $role->id }}" href="javascript:void(0);">
                                                                    Xoá
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        {{-- Thêm các hành động khác nếu cần --}}
                                                    </ul>
                                                </div>
                                            </td>
                                        @endcan
                                        <td class="text-left">{{ $role->name }}</td>
                                        {{-- <td>{{ showDateTime($role->created_at)  }}</td> --}}
                                        <td>{{ \Carbon\Carbon::parse($role->created_at)->format('d/m/Y') }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex" style="gap:5px">  
        <a class="btn mt-1 btn-sm btn--primary btn-submit-sync-roles">
            <i class="las la-sync"></i>
        </a>
        @can('admin.roles.add')
            <a class="btn btn-sm mt-1 btn--primary" href="{{ route('admin.roles.add') }}"><i class="las la-plus"></i></a>
        @endcan
      
    </div>
@endpush
<style scoped>
    /* Bỏ hover trong dropdown-item */
    .dropdown-menu .dropdown-item:hover,
    .dropdown-menu .dropdown-item:focus {
        background-color: transparent !important;
        color: inherit !important;
    }
</style>
@push('script')
    <script>
        $(document).ready(function() {

            $('.btn-submit-sync-roles').on('click', function() {
                location.reload();
            });

            $(document).on('click', '.btn-delete-role', function() {
                let id = $(this).data('id');
                console.log(id);
                
                let baseUrl = "{{ route('admin.roles.delete', ':id') }}";
                let url = baseUrl.replace(':id', id);
                Swal.fire({
                    title: 'Xác nhận xoá vai trò?',
                    text: 'Bạn có chắc chắn muốn xóa vai trò  này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },

                            success: function(response) {
                                try {
                                    location
                                        .reload(); // hoặc cập nhật UI mà không cần reload
                                } catch (error) {
                                    console.error('Lỗi trong xử lý phản hồi:', error);
                                    alert(
                                        'Đã xảy ra lỗi khi xử lý phản hồi từ máy chủ.'
                                        );
                                }
                            },
                            error: function(xhr) {
                                alert('Đã xảy ra lỗi khi xoá. Vui lòng thử lại.');
                            }
                        });


                    }
                });
            });
        });
    </script>
@endpush
