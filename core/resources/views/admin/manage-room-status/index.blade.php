@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Tên phòng')</th>
                                    <th>@lang('Mã cơ sở')</th>
                                    <th>@lang('Ngày bắt đầu')</th>
                                    <th>@lang('Ngày kết thúc')</th>
                                    <th>@lang('Trạng thái')</th>

                                    @can(['admin.hotel.room.status', 'admin.hotel.room.add'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roomStatusHistory as  $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td> {{ $item->room->room_number ?? 'Chưa có mã phòng' }}</td>
                                        <td>{{ $item->unit_code }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}</td>
                                        <td>{{ $item->roomStatus->status_name }}</td>


                                        {{-- <td> @php echo $room->statusBadge @endphp </td> --}}
                                        @can()
                                            <td>
                                                <div class="button--group">
                                                    @can()
                                                        <button class="btn btn-sm btn-outline--primary editBtn"
                                                            data-resource="{{ $item }}"><i class="las la-pencil-alt"></i>
                                                            @lang('Edit')</button>
                                                    @endcan

                                                    @if ($item->status == Status::ENABLE)
                                                    {{-- confirmationBtn --}}
                                                        <button class="btn btn-sm btn-outline--danger "
                                                            data-action=""
                                                            data-question="@lang('Bạn có chắc chắn ngưng hoạt động không ?')" type="button">
                                                            <i class="la la-eye-slash"></i> @lang('Ngưng hoạt động')
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-outline--success "
                                                            data-action=""
                                                            data-question="@lang('Bạn có muốn kích hoạt không ?')" type="button">
                                                            <i class="la la-eye"></i> @lang('Cho phép')
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-sm btn-outline--danger " data-id="{{$item->id}}" data-modal_title="Xóa" type="button">
                                                        <i class="fas fa-trash"></i>Xóa
                                                    </button>
                                                    {{-- btn-delete --}}
                                                </div>
                                            </td>
                                        @endcan
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
                @if ($roomStatusHistory->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($roomStatusHistory) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- @can('admin.hotel.room.add')
        <div class="modal fade" id="addModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Thêm loại phòng mới')</h5>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.hotel.room.add') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Mã loại phòng')</label>
                                <input class="form-control" name="code" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Tên loại phòng')</label>
                                <input class="form-control" name="name" type="text" required>
                            </div>
                            <div class="form-group">
                                <div class="upload-box">
                                    <input type="file" id="add_main_image" name="main_image" accept="image/*" required>
                                    <label for="add_main_image" class="upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Ảnh loại phòng</span>
                                    </label>
                                    <img id="add_preview" class="preview-image" src="" alt="Preview Image"
                                        style="display: none;">
                                    <small class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('admin.hotel.room.add')
        <div class="modal fade" id="editModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Update Room')</h5>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Mã loại phòng')</label>
                                <input class="form-control" name="code" type="text">
                            </div>
                            <div class="form-group">
                                <label>@lang('Tên loại phòng')</label>
                                <input class="form-control" name="name" type="text">
                            </div>
                            <div class="form-group">
                                <div class="upload-box">
                                    <input type="file" id="edit_main_image" name="main_image" accept="image/*">
                                    <label for="edit_main_image" class="upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Ảnh loại phòng</span>
                                    </label>
                                    <img id="showImage" class="preview-image" src="" alt="Preview Image"
                                        style="display: none;">
                                    <small class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan --}}

    @can('admin.hotel.room.search')
    @push('breadcrumb-plugins')
        <!-- Form tìm kiếm trực tiếp -->
        <form action="{{ route('admin.manage.room.status') }}" method="GET" id="searchForm">
            <div class="input-group">
                <input
                    type="search"
                    class="form-control"
                    name="keyword"
                    id="searchInput"
                    value="{{ request('keyword') }}"
                    placeholder="Tìm kiếm theo tiêu đề hoặc mô tả..."
                    onsearch="handleSearchClear()">
                <!-- Nút tìm kiếm -->
                <button type="submit" class="btn btn-primary">
                    <i class="las la-search"></i>
                </button>
            </div>
        </form>
    @endpush
@endcan

    @can('admin.hotel.room.status')
        <x-confirmation-modal />
    @endcan
@endsection

{{-- @push('breadcrumb-plugins')
    <button class="btn btn-outline--primary" data-bs-target="#addModal" data-bs-toggle="modal"><i
            class="las la-plus"></i>
        @lang('Thêm mới')</button>

@endpush --}}


@push('style')
    <style>
        .upload-box {
            width: 100%;
            height: 200px;
            border: 2px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            position: relative;
            margin: 20px auto;
            transition: border-color 0.3s ease;
            overflow: hidden;
        }

        .upload-box:hover {
            border-color: #999;
        }

        .upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 16px;
            color: #666;
            z-index: 2;
        }

        .upload-label i {
            font-size: 40px;
            margin-bottom: 10px;
            color: #666;
        }

        .upload-box input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
            /* Đảm bảo input file nằm trên cùng để có thể click vào */
        }

        .upload-box span {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            text-align: center;
        }

        .preview-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }

        .modal {
            z-index: 1070;
            /* Giá trị mặc định cho Bootstrap */
        }

        .select2-container {
            z-index: 1080;
            /* Đảm bảo select2 hiển thị trên modal */
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        $('.select2-multi-select').select2({
            placeholder: "Select options",
            // tags: false,
            tokenSeparators: [','],
            // dropdownParent: $('.append-item')
        })
        //Chuyển mọi ký tự trong input room_id thành uppercase
        $(document).ready(function() {
            $('input[name="room_id"]').on('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

        $(document).on('click', '.addItem', function() {
            var modal = $(this).parents('.modal');
            var div = modal.find('.append-item');
            div.append(`
                    <div class="form-group">
                        <div class="d-flex">
                            <div class="input-group row gx-0">
                                <input type="text" class="form-control" name=room_numbers[]" required>
                            </div>
                            <button type="button" class="btn btn--danger input-group-text border-0 removeRoomBtn flex-shrink-0 ms-4"><i class="las la-times me-0"></i></button>
                        </div>
                    </div>
                    `);
            div.removeClass('d-none');
        });


        $('.editBtn').on('click', function() {
            let modal = $('#editModal');
            let resource = $(this).data('resource');
            console.log(resource);

            let route = `{{ route('admin.hotel.room.update', '') }}/${resource.id}`;
            modal.find('form').attr('action', route);
            modal.find('[name=code]').val(resource.code);
            modal.find('[name=name]').val(resource.name);

            // Hiển thị hình ảnh cũ nếu có
            let showImage = modal.find('#showImage');
            showImage.attr('src', 'http://quanlykhachsan.test/storage/' + resource.main_image);
            showImage.show();

            // Đặt lại file input
            $('#edit_main_image').val(''); // Đặt lại file input cho modal chỉnh sửa

            modal.modal('show');
        });

        // Sự kiện để hiển thị ảnh mới khi người dùng chọn cho modal chỉnh sửa
        $('#edit_main_image').on('change', function(event) {
            const input = event.target;
            const showImage = document.getElementById('showImage');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    showImage.src = e.target.result; // Gán src cho img
                    showImage.style.display = 'block'; // Hiện ảnh mới
                };
                reader.readAsDataURL(input.files[0]); // Đọc file dưới dạng URL base64
            } else {
                showImage.style.display = 'none'; // Ẩn ảnh nếu không có file
            }
        });

        // Sự kiện để hiển thị ảnh mới khi người dùng chọn cho modal thêm
        $('#add_main_image').on('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('add_preview');
            const labelIcon = document.querySelector('#addModal .upload-label i'); // Icon tải lên
            const labelText = document.querySelector('#addModal .upload-label span'); // Text tải lên

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    labelIcon.style.display = 'none'; // Ẩn icon
                    labelText.style.display = 'none'; // Ẩn văn bản
                };

                reader.readAsDataURL(input.files[0]); // Đọc file dưới dạng URL base64
            } else {
                // Nếu không có tệp nào được chọn, đặt lại preview
                preview.src = '';
                preview.style.display = 'none';
                labelIcon.style.display = 'block'; // Hiện icon
                labelText.style.display = 'block'; // Hiện văn bản
            }
        });

        $(document).on('click', '.removeRoomBtn', function() {
            $(this).parents('.form-group').remove();
        });

        $('#editModal').on('shown.bs.modal', function(e) {
            $(document).off('focusin.modal');
        });

        $('#addModal').on('shown.bs.modal', function(e) {
            $(document).off('focusin.modal');
        });
        $('#addModal').on('hidden.bs.modal', function(e) {
            $(this).find('.append-item').html('');
        });

        $('#main_image').on('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const labelIcon = document.querySelector('.upload-label i'); // Icon tải lên
            const labelText = document.querySelector('.upload-label span'); // Text tải lên

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    labelIcon.style.display = 'none'; // Ẩn icon
                    labelText.style.display = 'none'; // Ẩn văn bản
                };

                reader.readAsDataURL(input.files[0]); // Đọc file dưới dạng URL base64
            } else {
                // Nếu không có tệp nào được chọn, đặt lại preview
                preview.src = '';
                preview.style.display = 'none';
                labelIcon.style.display = 'block'; // Hiện icon
                labelText.style.display = 'block'; // Hiện văn bản
            }
        });
        $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xóa loại phòng',
                    text: 'Bạn có chắc chắn không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.hotel.room.delete', ':id') }}"
                                .replace(':id', id),
                            success: function(response) {
                                if (response.status === 'success') {
                                    notify('success', response.message);
                                    location.reload();
                                } else {

                                }
                            }
                        });
                    }
                })
            })
    </script>

<script>
    function handleSearchClear() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput.value === '') {
            window.location.href = '{{ route('admin.manage.room.status') }}';
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
        .breadcrumb-plugins>button{
            order: 1;
            width: 100% !important;
            margin-right: 3rem !important;
            margin-left: 3rem !important;
        }
    }
   </style>
@endpush
