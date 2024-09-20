<div class="row">
    <div class="accordion-item">
        <h2 class="accordion-header" id="bookedRoomsHeading">
            <button aria-controls="bookedRooms" aria-expanded="true" class="accordion-button"
                data-bs-target="#bookedRooms" data-bs-toggle="collapse" type="button">
                @lang('Phòng đã đặt')
            </button>
        </h2>
        <div aria-labelledby="bookedRoomsHeading" class="accordion-collapse collapse show"
            data-bs-parent="#s" id="bookedRooms">
            <div class="accordion-body p-0">
                <div class="table-responsive--sm">
                    <table class="custom--table table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('Hành động')</th>
                                <th class="text-center">@lang('Đã đặt chỗ')</th>
                                <th>@lang('Loại phòng')</th>
                                <th>@lang('Phòng số')</th>
                                <th class="text-end">@lang('Giá') / @lang('Đêm')</th>
                            </tr>
                        </thead>

                        <tbody id="bookings-table-body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>