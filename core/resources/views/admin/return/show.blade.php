@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Danh sách hoàn trả của đơn hàng <a
                    href="{{ route('admin.warehouse.show', $products->warehouse_entry->id) }}"><strong>{{ $products->warehouse_entry->reference_code }}</strong></a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive--sm table-responsive">
                <form id="cancellation-form" action="" method="POST">
                    <table class="table--light style--two table table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá nhập</th>
                                <th>Số lượng hoàn</th>
                                <th>Lý do hủy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products->return_items as $item)
                                <tr>
                                    <td data-label="Sản phẩm">
                                        <p class="ellipsis">{{ $item->name }}</p>
                                    </td>
                                    <td data-label="Giá nhập">{{ showAmount($item->import_price) }}</td>
                                    <td data-label="Số lượng hoàn"><input disabled type="number" class="form-control p-0"
                                            value="{{ $item->pivot->quantity }}">
                                    </td>
                                    <td data-label="Lý do hủy">
                                        <input type="text" value="{{ $item->pivot->reason }}"
                                            class="form-control reason-input" placeholder="Nhập lý do hủy">
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary">Chế độ chỉnh sửa</button>
                        <a href="{{ route('admin.return.index') }}" class="btn btn-outline--primary" id="btn-close">Danh
                            sách</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(document).on('click', '.reason-input', function(e) {
                    e.preventDefault();
                });

                $(document).on('dblclick', '.reason-input', function() {
                    var $input = $(this);
                    var currentValue = $input.val();
                    var $textarea = $('<textarea class="form-control reason-textarea"></textarea>').val(
                        currentValue);
                    var inputName = $input.attr('name');

                    $input.replaceWith($textarea);
                    $textarea.focus();

                    $textarea.on('blur', function() {
                        var newValue = $textarea.val();
                        var $newInput = $('<input type="text" name="' + inputName +
                            '" class="form-control reason-input" placeholder="Nhập lý do hủy">'
                        ).val(newValue);
                        $textarea.replaceWith($newInput);
                    });
                });



            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

    <style>
        /* Tùy chỉnh ô input number thành hình vuông và ẩn nút tăng giảm */
        input[type="number"] {
            display: flex;
            width: 45px;
            display: inline;
            text-align: center;
        }

        /* Ẩn nút tăng giảm của ô input number */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            /* Ẩn cho Firefox */
        }

        /* Style cho textarea khi input được chuyển đổi */
        textarea {
            resize: both;
            /* Cho phép kéo dãn textarea */
            width: 100%;
            /* Đảm bảo chiều rộng */
        }

        .ellipsis {
            max-width: 250px;
            /* Chiều rộng tối đa của phần tử */
            white-space: nowrap;
            /* Không cho văn bản xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản bị tràn */
            text-overflow: ellipsis;/
        }

        @media (max-width: 992px) {
            .ellipsis {
                max-width: 190px;
            }
        }
    </style>
@endpush
