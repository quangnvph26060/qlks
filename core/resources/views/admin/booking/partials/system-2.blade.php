<div class="modal fade serviceModal" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content add-serve-mobi">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalLabel">Thêm dịch vụ cao cấp </h5>
                <button type="button" class="close close_model" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.premium.service.save') }}" class="add-service-form" method="POST">
                    @csrf
                    <div class="row append-service">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="service_date">Ngày phục vụ</label>
                                <input type="text" class="form-control" name="service_date"
                                    value="{{ todaysDate() }}" required readonly>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="room_number">Số phòng</label>
                                <input type="text" class="form-control room_serive" name="room_number" required
                                    readonly>
                            </div>
                        </div>
                    </div>


                        {{-- <div class="form-group d-flex justify-content-end">
                            <button type="button" class="btn btn-success addServiceBtn"><i class="las la-plus"></i>
                                Thêm</button>
                        </div> --}}
                        <label for="services">Dịch vụ</label>
                        <div class="row service-wrapper">
                            <div class="first-service-wrapper row">
                                <div id="services" class="col-md-5 mb-3 row">

                                </div>
                                <div class="service-item position-relative mb-3 flex-wrap-mobi col-md-7" id="list-service">

                                    {{-- <div class="row align-items-center mb-3">
                                        <!-- Cột cho input tên dịch vụ -->
                                        <div class="col-md-8 col-sm-12 mb-2 mb-md-0">
                                            <input type="text" class="form-control" name="services[]" placeholder="Tên dịch vụ" required>
                                        </div>

                                        <!-- Cột cho input số lượng -->
                                        <div class="col-md-4 col-sm-12">
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, -1)">-</button>
                                                <input type="number" class="form-control text-center" name="qty[]" value="1" min="1" required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, 1)">+</button>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                        </div>



                    <div class="form-group" style="float: inline-end">
                        <button type="submit" class=" btn-primary-1 w-10">Xác nhận</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function changeQuantity(button, delta) {
        const input = button.parentElement.querySelector('input[type="number"]');
        const currentValue = parseInt(input.value, 10);
        const newValue = currentValue + delta;
        input.value = newValue > 0 ? newValue : 1; // Không cho phép giá trị âm
    }

</script>

<style>
    /* .d-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    } */
    .w-50 {
        width: 50%;
    }
    .w-auto {
        width: auto;
    }
    .me-2 {
        margin-right: 0.5rem;
    }
    .input-group button {
        width: 40px;
    }

</style>
