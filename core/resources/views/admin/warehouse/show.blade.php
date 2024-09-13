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
                                <td>{{ $warehouse->supplier->name }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-phone me-2"></i> Số điện thoại</th>
                                <td>{{ $warehouse->supplier->phone }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-envelope me-2"></i> Email</th>
                                <td>{{ $warehouse->supplier->email }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ</th>
                                <td>{{ $warehouse->supplier->address }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-md-3 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="w-25"><i class="fas fa-receipt me-2"></i> Mã đơn hàng</th>
                                <td>{{ $warehouse->reference_code }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-money-bill-wave me-2"></i> Tổng tiền</th>
                                <td>{{ showAmount($warehouse->total) }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-calendar-alt me-2"></i> Ngày giao dịch</th>
                                <td>{{ \Carbon\Carbon::parse($warehouse->date)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-credit-card me-2"></i> Phương thức thanh toán</th>
                                <td>{{ $warehouse->payments->payment_method->name }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Chi tiết sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table" id="data-table">
                            <thead>
                                <tr>
                                    <th> Mã sản phẩm</th>
                                    <th> Ảnh</th>
                                    <th> Tên sản phẩm</th>
                                    <th> Số lượng nhập</th>
                                    <th> Giá nhập</th>
                                    <th> Giá bán</th>
                                    <th> Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouse->entries as $entryItem)
                                    <tr>
                                        <td data-label="Mã sản phẩm">{{ $entryItem->product->sku }}</td>
                                        <td data-label="Ảnh">
                                            <img src="{{ \Storage::url($entryItem->product->image_path) }}"
                                                alt="{{ $entryItem->product->name }}" width="50px">
                                        </td>
                                        <td data-label="Tên sản phẩm">{{ $entryItem->product->name }}</td>
                                        <td data-label="Số lượng nhập">{{ $entryItem->quantity }}</td>
                                        <td data-label="Giá nhập">{{ showAmount($entryItem->product->import_price) }}</td>
                                        <td data-label="Giá bán">{{ showAmount($entryItem->product->selling_price) }}</td>
                                        <td data-label="Tổng tiền">
                                            {{ showAmount($entryItem->quantity * $entryItem->product->selling_price) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--danger" href="{{ route('admin.warehouse.index') }}"><i
                class="las la-list"></i>@lang('Danh sách đơn hàng')</a>
    @endpush
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/ckeditor.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        @media (max-width: 992px) {
            .mt-md-3 {
                margin-top: 1.5rem !important;
            }
        }

        .table-bordered tr td {
            text-align: left
        }
    </style>
@endpush
