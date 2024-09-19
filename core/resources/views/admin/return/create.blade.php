@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Danh sách sản phẩm bị hủy</h6>
        </div>
        <div class="card-body">
            <form id="cancellation-form" action="" method="POST">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Lý do hủy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ showAmount($item->product->selling_price) }}</td>
                                <td><input id="products.{{ $item->product->id }}.quantity" value="{{ $item->quantity }}"
                                        type="number" name="products[{{ $item->product->id }}][quantity]"
                                        class="form-control p-0" value="{{ $item->quantity }}"></td>
                                <td>
                                    <input type="text" name="products[{{ $item->product->id }}][reason]"
                                        class="form-control reason-input" id="products.{{ $item->product->id }}.reason"
                                        placeholder="Nhập lý do hủy">

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary">Xác nhận hoàn trả</button>
                    <a href="" class="btn btn-secondary " id="btn-close">Quay lại</a>
                </div>
            </form>
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

                var url = window.location.href;

                // Tách URL thành mảng
                var parts = url.split('/');

                // Lấy phần thứ tư (số 16)
                var id = parts[5]; // Chỉ số 5 vì chỉ số bắt đầu từ 0

                var url = "{{ route('admin.warehouse.show', ':id') }}".replace(':id', id);
                $("#btn-close").attr('href', url);

                $('#cancellation-form').on('submit', function(e) {
                    e.preventDefault();


                    $.ajax({
                        url: "{{ route('admin.return.store', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: $(this).serializeArray(),
                        success: function(response) {
                            if (response.status) {
                                window.location.href = '{{ route('admin.return.index') }}';

                            } else {
                                const firstKey = Object.keys(response.errors ?? {})[0];


                                const firstError = firstKey == undefined ?
                                    response.message : response.errors[firstKey];

                                var escapedKey = firstKey == undefined ? firstKey : firstKey
                                    .replace(/\./g, '\\.');

                                $("input").css('border', '1px solid #ddd');



                                $(`#${escapedKey}`).css('border', '1px solid red');

                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    },
                                    customClass: {
                                        container: 'custom-toast' // Áp dụng lớp CSS tùy chỉnh
                                    }
                                });
                                Toast.fire({
                                    icon: "error",
                                    title: `<p>${firstError}</p>`,
                                });
                            }

                        }
                    })
                })

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
    </style>
@endpush
