<div class="modal fade" id="cancelBookingModal" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content cancel-booking">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input name="booked_for" type="hidden" value="">
                    <div class="row justify-content-center">
                        <div class="col-10 bg--danger p-3 rounded">
                            <div class="d-flex flex-wrap justify-content-between gap-2">
                                <h6 class="text-white">@lang('Giá')</h6>
                                <span class="text-white totalFare"></span>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between gap-2 mt-2">
                                <h6 class="text-white">@lang('Số tiền hoàn lại')</h6>
                                <span class="text-white refundableAmount"></span>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <h6 class="w-100">@lang('Bạn có chắc chắn hủy đặt phòng này không?')</h6>
                    <button aria-label="Close" class="btn btn--dark" data-bs-dismiss="modal"
                        type="button">@lang('Không')</button>
                    <button class="btn btn--primary" type="submit">@lang('Có ')</button>
                </div>
            </form>
        </div>
    </div>
</div>