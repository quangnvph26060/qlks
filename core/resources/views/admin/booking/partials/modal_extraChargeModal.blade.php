<div class="modal fade" id="extraChargeModal" role="dialog" tabindex="-1">
    <div class="modal-dialog justify-content-center" role="document">
        <div class="modal-content css_extraChargeModal">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="post">
                @csrf
                <input name="type" type="hidden">
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Tổng')</label>
                        <div class="input-group">
                            <input class="form-control" id="minus_fee" required step="any" type="text">
                                <input class="form-control" min="0" id="minus_fee_key"  name="amount" required step="any"
                                type="hidden">
                            <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Lý do')</label>
                        <textarea class="form-control" name="reason" required rows="4"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn--primary h-45 w-100" type="submit">@lang('Xác nhận')</button>
                </div>
            </form>
        </div>
    </div>
</div>
