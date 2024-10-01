<div class="accordion-item">
    <div class="d-flex justify-content-between mt-2 mb-2">
        <h2 class="accordion-header" id="premiumServiceHeading">
            <button aria-controls="premiumService" aria-expanded="false" class="accordion-button"
                data-bs-target="#premiumService" data-bs-toggle="collapse" type="button">
                @lang('Dịch vụ cao cấp ')
            </button>
        </h2>
        <div>
            <a href="javascript:void(0)" class=" btn-primary-service add_premium_service"> <i
                    class="las la-plus-circle"></i> Thêm dịch vụ cao cấp</a>
        </div>
    </div>
    <div aria-labelledby="premiumServiceHeading" class="accordion-collapse collapse show"
        data-bs-parent="#s" id="premiumService">
        <div class="accordion-body p-0">

            <div class="table-responsive--sm">
                <table class="custom--table head--base table table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Ngày')</th>
                            <th>@lang('Phòng số')</th>
                            <th>@lang('Dịch vụ')</th>
                            <th>@lang('Số lượng')</th>
                            <th>@lang('Giá trị')</th>
                            <th>@lang('Tổng')</th>
                        </tr>
                    </thead>

                    <tbody id="user_services">

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>