<div class="accordion-item">
    <div class="d-flex justify-content-between mt-2 mb-2">
        <h2 class="accordion-header" id="premiumServiceHeading">
            <button aria-controls="premiumService" aria-expanded="false" class="accordion-button"
                data-bs-target="#premiumService" data-bs-toggle="collapse" type="button">
                @lang('Sản phẩm')
            </button>
        </h2>
        <div>
            <a href="javascript:void(0)" class=" btn-primary-service add_product_room"> <i
                    class="las la-plus-circle"></i> Thêm sản phẩm</a>
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
                            <th>@lang('Sản phẩm')</th>
                            <th>@lang('Số lượng')</th>
                            <th>@lang('Giá trị')</th>
                            <th>@lang('Tổng')</th>
                        </tr>
                    </thead>

                    <tbody id="user_product">

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>