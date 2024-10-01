<div class="modal fade" id="premium_serviceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card b-radius--10">
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table--light style--two table">
                                    <thead>
                                        <tr>
                                            <th>@lang('STT')</th>
                                            <th>@lang('Ngày')</th>
                                            <th>@lang('Số phòng')</th>
                                            <th>@lang('Dịch vụ')</th>
                                            <th>@lang('Số lượng')</th>
                                            <th>@lang('Giá trị')</th>
                                            <th>@lang('Tổng cộng')</th>
                                            <th>@lang('Thêm bởi')</th>
                                            @can('admin.premium.service.delete')
                                                <th>@lang('Hành động')</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody id="table_service"></tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
