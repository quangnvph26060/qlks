@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin nhà cung cấp</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="w-25"><i class="fas fa-user me-2"></i> Tên nhà cung cấp</th>
                                <td>{{ $warehouse->supplier->name ?? "" }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-phone me-2"></i> Số điện thoại</th>
                                <td>{{ $warehouse->supplier->phone ?? ""}}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-envelope me-2"></i> Email</th>
                                <td>{{ $warehouse->supplier->email ?? ""}}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ</th>
                                <td>{{ $warehouse->supplier->address ?? "" }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-xl-0 mt-3 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="w-25"><i class="fas fa-receipt me-2"></i> Mã đơn hàng</th>
                                <td>{{ $warehouse->reference_code ?? ""}}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-money-bill-wave me-2"></i> Tổng tiền</th>
                                <td>{{ showAmount($warehouse->total) ?? "" }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-calendar-alt me-2"></i> Ngày tạo</th>
                                <td>{{ \Carbon\Carbon::parse($warehouse->date)->format('d/m/Y') ?? "" }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-credit-card me-2"></i> Phương thức thanh toán</th>
                                <td>Thanh toán khi nhận hàng</td>
                            </tr>
                            {{-- {{ $warehouse->payments->payment_method->name }} --}}
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12 mt-3">
            <div class="card">
                @php($sl = 0)
                @foreach ($warehouse->entries ?? [] as $item)
                    @php($sl += $item->quantity - $item->number_of_cancellations)
                @endforeach
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title">Chi tiết sản phẩm</h5>
                    <div id="result-btn">
                        @if ($warehouse->status == 1)
                            <p class="badge badge--success">Hoàn thành</p>
                        @elseif($sl == 0)
                            <p class="badge badge--danger">Đã hủy</p>
                        @elseif($sl > 0)
                            <a id="complete" href="javascript:void(0)"
                                class="btn btn-sm btn-outline--primary btn-return">Xác nhận đơn hàng</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table" id="data-table">
                            <thead>
                                <tr>
                                    <th> Mã sản phẩm</th>
                                    <th> Tên sản phẩm</th>
                                    <th> Số lượng nhập</th>
                                    <th> Số lượng hủy</th>
                                    <th> Giá nhập</th>
                                    <th> Giá bán</th>
                                    <th> Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($sum = 0)
                                @foreach ($warehouse->entries as $entryItem)
                                    @php($sum += ($entryItem->quantity - $entryItem->number_of_cancellations) * $entryItem->product->import_price)
                                    <tr>
                                        <td data-label="Mã sản phẩm">{{ $entryItem->product->sku }}</td>
                                        <td data-label="Tên sản phẩm">
                                            <p id="ellipsis">{{ $entryItem->product->name }}</p>
                                        </td>
                                        <td data-label="Số lượng nhập">{{ $entryItem->quantity }}</td>
                                        <td data-label="Số lượng nhập">{{ $entryItem->number_of_cancellations }}</td>
                                        <td data-label="Giá nhập">{{ showAmount($entryItem->product->import_price) }}
                                        </td>
                                        <td data-label="Giá bán">{{ showAmount($entryItem->product->selling_price) }}
                                        </td>
                                        <td data-label="Thành tiền">
                                            {{ showAmount(($entryItem->quantity - $entryItem->number_of_cancellations) * $entryItem->product->import_price) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5"></td>
                                    <td>
                                        <h6>Thành tiền:</h6>
                                    </td>
                                    <td><strong>{{ showAmount($sum) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    @push('breadcrumb-plugins')
        @if (!$warehouse->status)
            <div class="action-btn">
                <a href="{{ route('admin.return.create', $warehouse->id ?? "") }}" class="btn btn-sm btn-outline--danger"
                    id="btn-return"><i class="las la-sync"></i>Trả hàng</a>
                {{-- <a href="{{ route('admin.return.create', $warehouse->id) }}" class="btn btn-sm btn-outline--danger"
                    id="btn-cancel"><i class="las la-window-close"></i>Hủy đơn hàng</a> --}}
            </div>
        @elseif($warehouse->return && $warehouse->return->status)
            <a href="{{ route('admin.return.show', $warehouse->id) }}" class="btn btn-sm btn-outline--secondary"
                id="btn-return">chi tiết sản
                phẩm bị hoàn trả</a>
        @endif

        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.warehouse.index') }}"><i
                class="las la-list"></i>@lang('Danh sách đơn hàng')</a>
    @endpush
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

                $("#complete").on("click", function() {
                    Swal.fire({
                        title: 'Bạn chắc chắn chứ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.warehouse.update', $warehouse->id) }}",
                                type: "PUT",
                                success: function(response) {
                                    $(".action-btn").empty();
                                    $("#result-btn").empty().append(`
                                        <p class="badge badge--success">Hoàn thành</p>
                                        `);
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal
                                                .stopTimer;
                                            toast.onmouseleave = Swal
                                                .resumeTimer;
                                        },
                                        customClass: {
                                            container: 'custom-toast' // Áp dụng lớp CSS tùy chỉnh
                                        }
                                    });
                                    Toast.fire({
                                        icon: "success",
                                        title: `<p>${response.message}</p>`,
                                    });
                                }
                            })
                        }
                    })
                })

                // $('.entry').on('change', function() {
                //     if ($('.entry:checked').length > 0) {
                //         $('.form-horizontal').attr('action',
                //             '{{ route('admin.return.create', $warehouse->id) }}');
                //         $('.btn-return').prop('disabled', false);
                //     } else {
                //         $('.form-horizontal').attr('action', '#');
                //         $('.btn-return').prop('disabled', true);
                //     }

                // });
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

    <style>
        @media (max-width: 992px) {
            .mt-md-3 {
                margin-top: 1.5rem !important;
            }
        }

        .table-bordered tr td {
            text-align: left
        }

        #ellipsis {
            max-width: 250px;
            /* Chiều rộng tối đa của phần tử */
            white-space: nowrap;
            /* Không cho văn bản xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản bị tràn */
            text-overflow: ellipsis;/
        }
    </style>
@endpush
