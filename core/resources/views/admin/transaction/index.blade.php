@extends('admin.layouts.app')

@section('panel')
    @push('topBar')
        @include('admin.gateways.top_bar')
    @endpush

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quản lý phương thức thanh toán</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive" id="table-content">
                        @include('admin.transaction.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('admin.transaction.status')
        <x-confirmation-modal />
    @endcan

    <!-- Modal thêm phương thức thanh toán -->
    {{-- <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">Thêm phương thức thanh toán</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-transaction-form">
                        <div class="mb-3">
                            <label for="transaction_name" class="form-label">Tên phương thức</label>
                            <input type="text" class="form-control" id="transaction_name" name="name" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Thêm</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
    <!-- jQuery và các script cần thiết -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Sự kiện click vào nút xóa
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();

                let url = $(this).data('url');
                let row = $(this).closest('tr'); // Xác định hàng chứa nút xóa

                if (confirm('Bạn có chắc chắn muốn xóa?')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Thành công');
                                row.remove();

                                if ($('table tbody tr').length === 0) {
                                    $('table tbody').append(`
                    <tr>
                        <td class="text-muted text-center" colspan="100%">Không có dữ liệu</td>
                    </tr>
                `);
                                }
                            } else {
                                toastr.error(response.message, 'Lỗi');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText); // Kiểm tra lỗi từ server
                            toastr.error('Xóa phương thức thanh toán thất bại', 'Lỗi');
                        }
                    });

                }
            });
        });
    </script>
@endsection
@push('breadcrumb-plugins')
    @can('admin.transaction.add')
        <a class="btn btn-outline--primary" href="{{ route('admin.transaction.add') }}"><i
                class="las la-plus"></i>@lang('Thêm mới')</a>
    @endcan
@endpush
