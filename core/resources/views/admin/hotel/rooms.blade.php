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
                                    <th>@lang('Mã loại phòng')</th>
                                    <th>@lang('Tên loại phòng')</th>
                                    <th>@lang('Trạng thái')</th>
                                    @can(['admin.hotel.room.status', 'admin.hotel.room.add'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roomTypes as  $room)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td> {{ $room->code ?? 'Chưa có mã phòng'}}</td>
                                        <td>{{ __($room->name) }}</td>
                                      
                                     
                                        <td> @php echo $room->statusBadge @endphp </td>
                                        @can(['admin.hotel.room.status', 'admin.hotel.room.add'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.hotel.room.add')
                                                        <button class="btn btn-sm btn-outline--primary editBtn"
                                                            data-resource="{{ $room }}"><i class="las la-pencil-alt"></i>
                                                            @lang('Edit')</button>
                                                    @endcan

                                                    @if ($room->status == Status::ENABLE)
                                                        <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                            data-action="{{ route('admin.hotel.room.status', $room->id) }}"
                                                            data-question="@lang('Bạn có chắc chắn ngưng hoạt động không ?')" type="button">
                                                            <i class="la la-eye-slash"></i> @lang('Ngưng hoạt động')
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                            data-action="{{ route('admin.hotel.room.status', $room->id) }}"
                                                            data-question="@lang('Bạn có muốn kích hoạt không ?')" type="button">
                                                            <i class="la la-eye"></i> @lang('Cho phép')
                                                        </button>
                                                    @endif
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
                @if ($rooms->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($rooms) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @can('admin.hotel.room.add')
        <div class="modal fade" id="addModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Thêm mới phòng')</h5>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.hotel.room.add') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Loại phòng')</label>
                                <select class="form-control" name="room_type_id" required>
                                    <option disabled selected value="">@lang('Select One')</option>
                                    @foreach ($roomTypes as $roomType)
                                        <option value="{{ $roomType->id }}">{{ __($roomType->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Số phòng')</label>

                                <div class="d-flex">
                                    <div class="input-group row gx-0">
                                        <input type="text" class="form-control" name="room_numbers" required>
                                    </div>
                                    {{-- <button class="btn btn--success input-group-text border-0 addItem flex-shrink-0 ms-4" type="button"><i class="las la-plus me-0"></i></button> --}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('Mã phòng')</label>
                                <input class="form-control" name="room_id" type="text">
                            </div>

                            <div class="form-group">
                                <label for="">Chọn giá</label>
                                <select class="select2-multi-select" multiple="multiple" name="prices[]">
                                    @foreach ($prices as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                    <!-- Thêm các tùy chọn khác nếu cần -->
                                </select>
                            </div>

                            <div class="append-item d-none"></div>
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
                    <form action="" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Loại phòng')</label>
                                <select class="form-control" name="room_type_id" required>
                                    <option disabled selected value="">@lang('Select One')</option>
                                    @foreach ($roomTypes as $roomType)
                                        <option value="{{ $roomType->id }}">{{ __($roomType->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Số phòng')</label>
                                <input class="form-control" name="room_number" required type="text">
                            </div>
                            <div class="form-group">
                                <label>@lang('Mã phòng')</label>
                                <input class="form-control" name="room_id" type="text">
                            </div>
                            <div class="form-group">
                                <label for="">Chọn giá</label>
                                <select class="select2-multi-select" multiple="multiple" name="prices[]">
                                    @foreach ($prices as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
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

    @can('admin.hotel.room.status')
        <x-confirmation-modal />
    @endcan
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary" data-bs-target="#addModal" data-bs-toggle="modal"><i
            class="las la-plus"></i>
        @lang('Thêm mới')</button>
    <x-search-form filter='yes' />
@endpush

@push('style')
    <style>
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

            let route = `{{ route('admin.hotel.room.update', '') }}/${resource.id}`;

            modal.find('form').attr('action', route);
            modal.find('[name=room_type_id]').val(resource.room_type_id);
            modal.find('[name=room_number]').val(resource.room_number);
            modal.find('[name=room_id]').val(resource.room_id);
            let priceIds = resource.prices.map(price => price.pivot.price_id); // Adjust this if necessary
            modal.find('.select2-multi-select').val(priceIds).trigger('change');
            modal.modal('show');
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
    </script>
@endpush
