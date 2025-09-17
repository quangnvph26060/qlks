@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    @can(['admin.staff.*'])
                                        <th class="w-10">@lang('Hành động')</th>
                                    @endcan
                                    <th>@lang('Tên người dùng')</th>
                                    <th>@lang('Tên')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Vai trò')</th>
                                    <th>@lang('Trạng thái')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allStaff as $staff)
                                    <tr>
                                        <td>{{ $loop->index + $allStaff->firstItem() }}</td>
                                        @can(['admin.staff.*'])
                                            <td class="d-none-mobi">
                                                <div class="">
                                                    @if ($staff->id > 1)
                                                        <div class="dropdown text-end">
                                                            <!-- Nút ba chấm tùy chỉnh -->
                                                            <button class="menu-toggle-btn" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="la la-ellipsis-v"></i>
                                                            </button>

                                                            <!-- Menu xổ ra -->
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                @can('admin.staff.save')
                                                                    <button class="dropdown-item cuModalBtn"
                                                                        data-modal_title="@lang('Cập nhật nhân viên')"
                                                                        data-resource="{{ $staff }}">
                                                                        <i class="la la-pencil"></i> @lang('Sửa')
                                                                    </button>
                                                                @endcan

                                                                @can('admin.staff.status')
                                                                    @if ($staff->status)
                                                                        <button class="dropdown-item confirmationBtn text-danger"
                                                                            data-action="{{ route('admin.staff.status', $staff->id) }}"
                                                                            data-question="@lang('Bạn có chắc chắn cấm nhân viên này?')">
                                                                            <i class="las la-user-alt-slash"></i> @lang('Cấm')
                                                                        </button>
                                                                    @else
                                                                        <button class="dropdown-item confirmationBtn text-success"
                                                                            data-action="{{ route('admin.staff.status', $staff->id) }}"
                                                                            data-question="@lang('Bạn có chắc chắn bỏ lệnh cấm nhân viên này không?')">
                                                                            <i class="las la-user-check"></i> @lang('Bỏ cấm')
                                                                        </button>
                                                                    @endif
                                                                @endcan

                                                                @can('admin.staff.login')
                                                                    {{-- <a class="dropdown-item"
                                                                        href="{{ route('admin.staff.login', $staff->id) }}"
                                                                        target="_blank">
                                                                        <i class="las la-sign-in-alt"></i> @lang('Đăng nhập')
                                                                    </a> --}}
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        @endcan
                                        <td data-label="Tên người dùng">{{ $staff->username }}</td>
                                        <td data-label="Tên">{{ $staff->name }}</td>
                                        <td data-label="Email">{{ $staff->email }}</td>
                                        <td data-label="Vai trò">
                                            @if ($staff->role)
                                                {{ $staff->role->name }}
                                            @else
                                                @lang('Super Admin')
                                            @endif
                                        </td>

                                        <td data-label="Trạng thái">
                                            @php
                                                echo $staff->statusBadge;
                                            @endphp
                                        </td>
                                        <td class="d-block-mobi">
                                            <div class="action-buttons gap-2">
                                                @can('admin.staff.save')
                                                    <button class="dropdown-item cuModalBtn"
                                                        data-modal_title="@lang('Cập nhật nhân viên')"
                                                        data-resource="{{ $staff }}">
                                                        <i class="la la-pencil"></i> @lang('Sửa')
                                                    </button>
                                                @endcan

                                                @can('admin.staff.status')
                                                    @if ($staff->status)
                                                        <button class="dropdown-item confirmationBtn text-danger"
                                                            data-action="{{ route('admin.staff.status', $staff->id) }}"
                                                            data-question="@lang('Bạn có chắc chắn cấm nhân viên này?')">
                                                            <i class="las la-user-alt-slash"></i> @lang('Cấm')
                                                        </button>
                                                    @else
                                                        <button class="dropdown-item confirmationBtn text-success"
                                                            data-action="{{ route('admin.staff.status', $staff->id) }}"
                                                            data-question="@lang('Bạn có chắc chắn bỏ lệnh cấm nhân viên này không?')">
                                                            <i class="las la-user-check"></i> @lang('Bỏ cấm')
                                                        </button>
                                                    @endif
                                                @endcan

                                                @can('admin.staff.login')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.staff.login', $staff->id) }}" target="_blank">
                                                        <i class="las la-sign-in-alt"></i> @lang('Đăng nhập')
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>

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
                @if ($allStaff->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($allStaff) }}

                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />

    @can('admin.staff.save')
        <!-- Create Update Modal -->
        <div class="modal fade" id="cuModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.staff.save') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Tên đăng nhập')</label>
                                <input class="form-control" name="name" required type="text">
                            </div>

                            <div class="form-group">
                                <label>@lang('Họ và tên')</label>
                                <input class="form-control" name="username" required type="text">
                            </div>

                            <div class="form-group">
                                <label>@lang('Email')</label>
                                <input class="form-control" name="email" required type="email">
                            </div>

                            <div class="form-group">
                                <label>@lang('Vai trò')</label>
                                <select class="form-control" name="role_id" required>
                                    <option disabled selected value="">@lang('Chọn')</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>@lang('Mật khẩu')</label>
                                <div class="input-group">
                                    <input class="form-control" name="password" required type="text">
                                    <button class="input-group-text generatePassword" type="button">@lang('Tạo')</button>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Xác nhận')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('breadcrumb-plugins')
    {{--    <x-search-form placeholder="Username" style="padding: .375rem .75rem;height:auto"/> --}}
    <!-- Modal Trigger Button -->
    <div class="d-flex" style="gap: 5px;">
        @can('admin.staff.save')
            <button class="btn btn-sm mt-1 btn--primary cuModalBtn" data-modal_title="@lang('Thêm mới nhân viên')" type="button">
                <i class="las la-plus"></i>
            </button>
        @endcan
        <a class="btn mt-1 btn-sm btn--primary btn-submit-sync-staff">
            <i class="las la-sync"></i>
        </a>
    </div>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.generatePassword').on('click', function() {
                $(this).siblings('[name=password]').val(generatePassword());
            });
            $('.btn-submit-sync-staff').on('click', function() {
                location.reload();
            });
            $('.cuModalBtn').on('click', function() {
                let passwordField = $('#cuModal').find($('[name=password]'));
                let label = passwordField.parents('.form-group').find('label')
                if ($(this).data('resource')) {
                    passwordField.removeAttr('required');
                    label.removeClass('required')
                } else {
                    passwordField.attr('required', 'required');
                    label.addClass('required')
                }
            });

            function generatePassword(length = 12) {
                let charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+<>?/";
                let password = '';

                for (var i = 0, n = charset.length; i < length; ++i) {
                    password += charset.charAt(Math.floor(Math.random() * n));
                }

                return password
            }
        })(jQuery);
    </script>
@endpush
<style>
    
            .d-block-mobi {
            display: none;
        }

        /* Mobile: các nút nằm ngang trong 1 hàng */
        @media (max-width: 768px) {
            .d-block-mobi .action-buttons {
                display: flex;
                flex-wrap: nowrap;
                justify-content: space-between;
            }

            .d-block-mobi .action-buttons .btn {
                flex: 1;
                /* nút tự dàn đều */
                margin: 0 2px;
                /* khoảng cách nhỏ */
            }

            .d-block-mobi {
                display: block;
            }
        }
    .menu-toggle-btn {
        background: none;
        border: none;
        padding: 0;
        margin: 0;
        color: inherit;
        cursor: pointer;
    }

    .la-ellipsis-v {
        font-size: 30px;
    }

    .menu-toggle-btn:hover,
    .menu-toggle-btn:focus {
        background: none;
        box-shadow: none;
        outline: none;
    }
</style>
