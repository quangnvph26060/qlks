<?php

use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->middleware('admin')->withoutMiddleware('admin.guest')->name('logout');
        });

        // Admin Password Reset
        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function () {
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
            Route::post('password/reset/change', 'reset')->name('password.change');
        });
    });
});

Route::middleware('admin', 'adminPermission')->group(function () {
    Route::prefix('fee')->name('fee.')->group(function () {
        Route::get('', [FeeController::class, 'index'])->name('index');
        Route::post('update', [FeeController::class, 'update'])->name('update');
    });
    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('chart/booking-report', 'bookingReport')->name('chart.booking');
        Route::get('chart/payment-report', 'paymentReport')->name('chart.payment');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAllNotification')->name('notifications.read.all');
        Route::post('notifications/delete-all', 'deleteAllNotification')->name('notifications.delete.all');
        Route::post('notifications/delete-single/{id}', 'deleteSingleNotification')->name('notifications.delete.single');

        //Report Bugs
        Route::get('request-report', 'requestReport')->name('request.report');
        Route::post('request-report', 'reportSubmit')->name('request.report.submit');

        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });

    // Manage Staff
    Route::controller('StaffController')->prefix('staff')->name('staff.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('save/{id?}', 'save')->name('save');
        Route::post('switch-status/{id}', 'status')->name('status');
        Route::get('login/{id}', 'login')->name('login');
    });

    // Manage Roles
    Route::controller('RolesController')->prefix('roles')->name('roles.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('save/{id?}', 'save')->name('save');
    });

    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('users')->group(function () {
        Route::get('/', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-single-notification/{id}', 'sendNotificationSingle')->name('notification.single.send');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('list', 'list')->name('list');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
    });

    // manage category
    Route::controller('CategoryController')->prefix('categories')->name('category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('create', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::put('update-status', 'updateStatus')->name('update.status');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
    });

    // manage brand
    Route::controller('BrandController')->prefix('brand')->name('brand.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('create', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::put('update-status', 'updateStatus')->name('update.status');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
    });

    // manage supplier
    Route::controller('SupplierController')->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::put('update-status', 'updateStatus')->name('update.status');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
    });

    // manage representative
    Route::controller('RepresentativeController')->prefix('representative')->name('representative.')->group(function () {
        Route::post('create', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
    });

    // manage product
    Route::controller('ProductController')->prefix('product')->name('product.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('{id}/destroy', 'destroy')->name('destroy');
        Route::put('{id}/status', 'updateStatus')->name('status');
        Route::get('filter', 'filter')->name('filter');
    });

    // manage warehouse
    Route::controller('WarehouseController')->prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('{id}/show', 'show')->name('show');
        Route::put('{id}/update', 'update')->name('update');
    });

    // manage return
    Route::controller('ReturnController')->prefix('return')->name('return.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{id}/create', 'create')->name('create');
        Route::post('{id}/store', 'store')->name('store');
        Route::get('{id}/show', 'show')->name('show');
    });

    // inventory
    Route::controller('InventoryController')->prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::name('hotel.')->prefix('hotel')->group(function () {
        Route::controller('AmenitiesController')->name('amenity.')->prefix('amenities')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('save/{id?}', 'save')->name('save');
            Route::post('status/{id}', 'status')->name('status');
        });
        Route::controller('GeneralSettingController')->group(function () {
            Route::get('setup-hotel', 'setupHotel')->name('setting.setup.hotel');
            Route::post('setup-hotel', 'addHotel')->name('setting.setup.add.hotel');
            Route::post('edit-setup-hotel/{id}', 'editHotel')->name('setting.setup.edit.hotel');
            Route::post('update-setup-hotel/{id}', 'updateHotel')->name('setting.setup.update.hotel');
            Route::post('status-setup-hotel/{id}', 'statusHotel')->name('setting.setup.status.hotel');
            Route::post('delete-setup-hotel/{id}', 'deleteHotel')->name('setting.setup.delete.hotel');
        });
        //Bed Type
        Route::controller('BedTypeController')->name('bed.')->prefix('bed-list')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('save/{id?}', 'save')->name('save');
            Route::post('delete/{id}', 'delete')->name('delete');
        });

        //facility
        Route::controller('FacilityController')->name('facility.')->prefix('facilities')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('save/{id?}', 'save')->name('save');
            Route::post('status/{id}', 'status')->name('status');
        });

        //Room Type
        Route::controller('RoomTypeController')->name('room.type.')->prefix('room-type')->group(function () {
            Route::get('', 'index')->name('all');
            Route::get('create', 'create')->name('create');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('save/{id?}', 'save')->name('save');
            Route::post('status/{id}', 'status')->name('status');

            Route::get('check-slug', 'checkSlug')->name('check.slug');
            Route::delete('delete/{id}', 'destroy')->name('destroy');
            Route::get('/deleted', 'roomDeleted')->name('all.deleted');
            Route::get('/restore/{id}', 'restore')->name('restore');
            Route::get('search', 'search')->name('search');
            Route::get('ajax', 'ajax')->name('ajax');

        });

        //Room
        Route::controller('RoomController')->name('room.')->prefix('room')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('add', 'addRoom')->name('add');
            Route::post('update/{id}', 'addRoom')->name('update');
            Route::post('update/status/{id}', 'status')->name('status');
            //delete
            Route::post('delete/{id}', 'delete')->name('delete');
        });

        //Premium Services
        Route::controller('PremiumServiceController')->name('premium.service.')->prefix('premium-service')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('save/{id?}', 'save')->name('save');
            Route::post('status/{id}', 'status')->name('status');
        });
        //Manage amenities with room
        Route::controller('ManageRoomAmenitiesController')->name('room.amenities.')->prefix('roomAmenities')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('/rooms/add-amenity', 'addAmenitiesToTheRoom')->name('store');
            Route::get('room/edit-amenity/{id}', 'edit')->name('edit');
            Route::post('/rooms/update-amenity', 'update')->name('update');
        });
        //Manage Facilities with room
        Route::controller('ManageRoomFacilitiesController')->name('room.facilities.')->prefix('roomFacilities')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('/rooms/add-facility', 'store')->name('store');
            Route::get('room/edit-facility/{id}', 'edit')->name('edit');
            Route::post('/rooms/update-facility', 'update')->name('update');
        });

        Route::controller('StatusCodeController')->name('status.code.')->prefix('status-codes')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('/status/add-status', 'store')->name('store');
            Route::get('status/edit-status/{id}', 'edit')->name('edit');
            Route::post('/status/update-status/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
            Route::post('delete-status/{id}', 'delete')->name('delete');
        });

        Route::controller('CustomerSourceController')->name('customer.source.')->prefix('customer-sources')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('/source/add-source', 'store')->name('store');
            Route::get('source/edit-source/{id}', 'edit')->name('edit');
            Route::put('/source/update-source/{id}', 'update')->name('update');
            Route::post('delete-source/{id}', 'delete')->name('delete');
            Route::get('search', 'search')->name('search');

        });

        Route::controller('CustomerGroupController')->name('customer.group.')->prefix('customer-groups')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('/source/add-group', 'store')->name('store');
            Route::get('source/edit-group/{id}', 'edit')->name('edit');
            Route::put('/source/update-group/{id}', 'update')->name('update');
            Route::post('delete-group/{id}', 'delete')->name('delete');
            Route::get('search', 'search')->name('search');

        });


        Route::controller('CustomerController')->name('customer.')->prefix('customers')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('/add-customer', 'store')->name('store');
            Route::get('edit-customer/{id}', 'edit')->name('edit');
            Route::put('/update-customer/{id}', 'update')->name('update');
            Route::post('delete-customer/{id}', 'delete')->name('delete');
            Route::post('status/{id}', 'status')->name('status');
            Route::get('search', 'search')->name('search');
            Route::get('check-code','checkCode')->name('check');
            Route::get('get/{id}','getCustomer')->name('get');


        });

        Route::controller('StatusCodeController')->name('status.code.')->prefix('status-code')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('/status/add-status', 'store')->name('store');
            Route::get('status/edit-status/{id}', 'edit')->name('edit');
            Route::post('/status/update-status/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
            Route::post('delete-status/{id}', 'delete')->name('delete');
            Route::get('search', 'search')->name('search');

        });

        Route::controller('ManageRoomProductController')->name('room.product.')->prefix('roomProduct')->group(function () {
            Route::get('', 'index')->name('all');
            Route::post('/rooms/add-product', 'store')->name('store');
            Route::get('room/edit-product/{id}', 'edit')->name('edit');
            Route::post('/rooms/update-product', 'update')->name('update');
        });
        Route::controller('CustomerSourceController')->name('room.customer_customer.')->prefix('CustomerSource')->group(function () {
            Route::get('', 'index')->name('all');
        });
        Route::controller('CustomerController')->name('customer.')->prefix('Customer')->group(function () {
            Route::get('', 'index')->name('all');
        });
    });

    Route::controller('BookRoomController')->group(function () {
        Route::get('book-room', 'room')->name('book.room');
        Route::post('room-book', 'book')->name('room.book');
        Route::post('room-book-edit', 'bookEdit')->name('room.book.edit');
        Route::post('room-book-delete', 'deleteRoomBooking')->name('room.booking.delete');
        Route::get('room/search', 'searchRoom')->name('room.search');

        Route::get('search/customer', 'searchCustomer')->name('search.customer');
        Route::get('find/customer', 'findCustomer')->name('find.customer');
        Route::get('customer-staff', 'StaffAndCustomerSource')->name('get.customer.staff');
        Route::post('room-note', 'updatenote')->name('room.note');
        Route::post('room-check-in/{id}', 'checkIn')->name('room.check.in');
        Route::post('room-booking/{id}', 'roomBookingEdit')->name('room.booking.edit');
        Route::get('room-booking', 'getBooking')->name('room.booking');
    });

    //Manage Reservation
    Route::controller('BookingController')->group(function () {
        Route::name('booking.')->prefix('booking')->group(function () {
            Route::post('booking-merge/{id}', 'mergeBooking')->name('merge');

            Route::get('bill-payment/{id}', 'paymentView')->name('payment');
            Route::post('bill-payment/{id}', 'payment')->name('payment');

            Route::get('booking-checkout/{id}', 'checkOutPreview')->name('checkout');
            Route::post('booking-checkout/{id}', 'checkOut')->name('checkout');

            Route::get('booked-rooms/{id}', 'bookedRooms')->name('booked.rooms');

            Route::get('details/{id}', 'bookingDetails')->name('details');
            Route::get('check-in-details/{id}', 'CheckInDetails')->name('check.in.details');
            Route::get('booking-invoice/{id}', 'generateInvoice')->name('invoice');

            Route::post('key/handover/{id}', 'handoverKey')->name('key.handover');

            Route::post('write-cccd', 'writeCccd')->name('writeCccd');

            Route::post('get-room-type', 'getRoomType')->name('getRoomType');


            Route::post('show-room', 'showRoom')->name('showRoom');


            Route::get('serviceproduct/{id}', 'bookingserviceproduct')->name('serviceproduct');

            Route::get('show-room', 'showRoom')->name('showRoom');


            Route::post('check-room-booking', 'checkRoomBooking')->name('checkRoomBooking');
            // xóa đặt phòng
            Route::post('delete-booked-room/{id}', 'deleteRoomBooking')->name('delete-booked-room');


        });
    });

    Route::controller('ManagePriceListController')->group(function () {
        Route::get('priceList', 'priceList')->name('manage.price.all');
        Route::get('priceListRoomType', 'priceListRoomType')->name('manage.priceroomtype.all');
        Route::get('modalAdd', 'modalAdd')->name('manage.modalAdd');
        Route::post('modalEdit/{id}', 'modalEdit')->name('manage.modalEdit');
        Route::post('addPriceRoomType', 'addPriceRoomType')->name('manage.addPriceRoomType');
        Route::post('addPrice', 'addPrice')->name('manage.addPrice');
        Route::post('editPriceRoomType/{id}', 'editPriceRoomType')->name('manage.editPriceRoomType');
        Route::get('setupPriceRoomType', 'setupPriceRoomType')->name('manage.setupPriceRoomType');
        Route::post('updateRoomTypePrice', 'updateRoomTypePrice')->name('manage.updateRoomTypePrice');
        Route::post('deleteRoomTypePrice/{id}', 'deleteRoomTypePrice')->name('manage.deleteRoomTypePrice');
        Route::get('showRoomTypePrice', 'showRoomTypePrice')->name('manage.showRoomTypePrice');
        Route::post('deletePriceRoomType/{id}', 'deletePriceRoomType')->name('manage.deletePriceRoomType');
        Route::post('priceList', 'store')->name('manage.price.store');
        Route::get('priceList/{id}', 'edit')->name('manage.price.edit');
        Route::put('priceList/{id}/update', 'update')->name('manage.price.update');
        Route::put('priceList/{id}/updateStatus', 'updateStatus')->name('manage.price.updateStatus');
        Route::delete('priceList/{id}', 'destroy')->name('manage.price.destroy');
    });

    Route::name('booking.')->prefix('booking')->group(function () {
        Route::controller('BookingController')->group(function () {
            Route::get('all-bookings', 'allBookingList')->name('all');
            Route::get('approved', 'activeBookings')->name('active');
            Route::get('canceled-bookings', 'canceledBookingList')->name('canceled.list');
            Route::get('checked-out-booking', 'checkedOutBookingList')->name('checked.out.list');
            Route::get('todays/booked-room', 'todaysBooked')->name('todays.booked');
            Route::get('todays/check-in', 'todayCheckInBooking')->name('todays.checkin');
            Route::get('todays/checkout', 'todayCheckoutBooking')->name('todays.checkout');
            Route::get('refundable', 'refundableBooking')->name('refundable');
            Route::get('checkout/delayed', 'delayedCheckout')->name('checkout.delayed');
            Route::get('details/{id}', 'bookingDetails')->name('details');
            Route::get('booked-rooms/{id}', 'bookedRooms')->name('booked.rooms');
            Route::post('list-room-booking', 'listRoomBooking')->name('listRoomBooking');
            Route::post('list-room-booked', 'listRoomBooked')->name('listRoomBooked');
            Route::post('get-room-check-in', 'getRoomCheckIn')->name('getRoomCheckIn');

            Route::get('all-check-in', 'getBooking')->name('all.check.in');
        });

        Route::controller('ManageBookingController')->group(function () {
            Route::post('key/handover/{id}', 'handoverKey')->name('key.handover');
            Route::post('booking-merge/{id}', 'mergeBooking')->name('merge');
            Route::get('bill-payment/{id}', 'paymentView')->name('payment');
            Route::post('bill-payment/{id}', 'payment')->name('payment');// thanh toán trước
            Route::post('add-charge/{id}', 'addExtraCharge')->name('extra.charge.add'); // thêm phụ phí
            Route::post('subtract-charge/{id}', 'subtractExtraCharge')->name('extra.charge.subtract'); // trừ chi phí
            Route::get('booking-checkout/{id}', 'checkOutPreview')->name('checkout');
            Route::post('booking-checkout/{id}', 'checkOut')->name('checkout');
            Route::get('premium-service/details/{id}', 'premiumServiceDetail')->name('service.details');
            Route::get('booking-invoice/{id}', 'generateInvoice')->name('invoice');
        });

        Route::controller('CancelBookingController')->group(function () {
            Route::get('cancel/{id}', 'cancelBooking')->name('cancel');
            Route::post('cancel-full/{id}', 'cancelFullBooking')->name('cancel.full');
            Route::post('booked-room/cancel/{id}', 'cancelSingleBookedRoom')->name('booked.room.cancel');
            Route::post('cancel-booking/{id}', 'cancelBookingByDate')->name('booked.day.cancel');
        });
    });

    // Manage booking information
    Route::controller('BookingController')->prefix('booking')->group(function () {
        Route::get('upcoming/check-in', 'upcomingCheckIn')->name('upcoming.booking.checkin');
        Route::get('upcoming/checkout', 'upcomingCheckout')->name('upcoming.booking.checkout');
        Route::get('pending/check-in', 'pendingCheckIn')->name('pending.booking.checkin');
        Route::get('delayed/checkout', 'delayedCheckouts')->name('delayed.booking.checkout');
        Route::get('receptionist', 'Receptionist')->name('receptionist.booking.receptionist');
        Route::get('user-clean-room','listUserCleanRoom')->name('listUserCleanRoom.booking.listUserCleanRoom');
        Route::post('delete-clean-room/{id}','delCleanRoom')->name('delCleanRoom.booking.delCleanRoom');
        Route::get('searchrooms', 'searchRooms')->name('searchrooms.booking.searchrooms');
        Route::post('room-clean', 'changeCleanRoom')->name('roomclean.booking.roomclean');
        Route::get('get-premium-services', 'getPremiumServices')->name('services.booking.services');
        Route::get('get-product', 'getProduct')->name('product.booking.product');
    });

    // Manage booking premium services
    Route::controller('BookingPremiumServiceController')->prefix('premium-service')->name('premium.service.')->group(function () {
        Route::get('all', 'all')->name('list');
        Route::get('add-new', 'addNew')->name('add');
        Route::post('add', 'addService')->name('save');
        Route::post('add-product', 'addProduct')->name('save-product');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Manage Booking Request
    Route::controller('ManageBookingRequestController')->prefix('booking')->name('request.booking.')->group(function () {
        Route::get('requests', 'index')->name('all');
        Route::get('request/canceled', 'canceledBookings')->name('canceled');
        Route::get('request/approve/{id}', 'approve')->name('approve');
        Route::post('request/cancel/{id}', 'cancel')->name('cancel');
        Route::post('assign-room', 'assignRoom')->name('assign.room');
    });

    // Subscriber
    Route::controller('SubscriberController')->prefix('subscriber')->name('subscriber.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('send-email', 'sendEmailForm')->name('send.email');
        Route::post('remove/{id}', 'remove')->name('remove');
        Route::post('email-send', 'sendEmail')->name('email.send');
    });

    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {
        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->prefix('automatic')->name('automatic.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });

        // Manual Methods
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'create')->name('create');
            Route::post('new', 'store')->name('store');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::get('', [TransactionController::class, 'index'])->name('index');
        Route::get('add', [TransactionController::class, 'add'])->name('add');
        Route::post('store', [TransactionController::class, 'store'])->name('store');
        Route::get('detail/{id}', [TransactionController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [TransactionController::class, 'update'])->name('update');
        Route::get('delete/{id}', [TransactionController::class, 'delete'])->name('delete');
        Route::post('change-status/{id}', [TransactionController::class, 'changeTransactionStatus'])->name('changeStatus');
    });
    // price in room
    Route::prefix('price')->name('price.')->group(function () {
        Route::post('/switchPrice', [PriceController::class, 'switchPrice'])->name('switchPrice');
        Route::get('/roomPricePerDay', [PriceController::class, 'roomPricePerDay'])->name('roomPricePerDay');
        Route::get('/roomPricePerDayOfWeek', [PriceController::class, 'roomPricePerDayOfWeek'])->name('roomPricePerDayOfWeek');
        Route::post('/updatePriceDate', [PriceController::class, 'updatePriceDate'])->name('updatePriceDate');
        Route::get('/priceHours', [PriceController::class, 'priceHours'])->name('priceHours');
        Route::get('/priceweek', [PriceController::class, 'priceweek'])->name('priceweek');
        Route::get('/roomPricePerDayNew', [PriceController::class, 'roomPricePerDayNew'])->name('roomPricePerDayNew');
        Route::get('/rooms', [PriceController::class, 'rooms'])->name('rooms');
        // Route::get('add', [TransactionController::class, 'add'])->name('add');
        // Route::post('store', [TransactionController::class, 'store'])->name('store');
        // Route::get('detail/{id}', [TransactionController::class, 'edit'])->name('edit');
        // Route::put('update/{id}', [TransactionController::class, 'update'])->name('update');
        // Route::get('delete/{id}', [TransactionController::class, 'delete'])->name('delete');
        // Route::post('change-status/{id}', [TransactionController::class, 'changeTransactionStatus'])->name('changeStatus');
    });


    // DEPOSIT SYSTEM
    Route::controller('DepositController')->prefix('payment')->name('deposit.')->group(function () {
        Route::get('all/{user_id?}', 'deposit')->name('list');
        Route::get('pending/{user_id?}', 'pending')->name('pending');
        Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
        Route::get('approved/{user_id?}', 'approved')->name('approved');
        Route::get('successful/{user_id?}', 'successful')->name('successful');
        Route::get('initiated/{user_id?}', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');
        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });

    // Report
    Route::controller('ReportController')->prefix('report')->name('report.')->group(function () {
        Route::get('login/history', 'loginHistory')->name('login.history');
        Route::get('login/ipHistory/{ip}', 'loginIpHistory')->name('login.ipHistory');
        Route::get('notification/history', 'notificationHistory')->name('notification.history');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');

        Route::get('booking-actions', 'bookingSituationHistory')->name('booking.history');
        Route::get('payments/received/history', 'paymentsReceived')->name('payments.received');
        Route::get('payment/returned/history', 'paymentReturned')->name('payments.returned');
    });

    // Admin Support
    Route::controller('SupportTicketController')->prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/', 'tickets')->name('index');
        Route::get('pending', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete');
    });

    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function () {
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
        Route::get('get-keys', 'getKeys')->name('get.key');
    });

    Route::controller('GeneralSettingController')->group(function () {

        Route::get('system-setting', 'systemSetting')->name('setting.system');

        // General Setting
        Route::get('general-setting', 'general')->name('setting.general');
        Route::post('general-setting-update', 'generalUpdate')->name('setting.update');

        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');

        //configuration
        Route::get('setting/system-configuration', 'systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration-submit', 'systemConfigurationSubmit')->name('setting.system.configuration.submit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting-update/logo-icon', 'logoIconUpdate')->name('setting.update.logo.icon');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css-submit', 'customCssSubmit')->name('setting.custom.css.submit');

        Route::get('sitemap', 'sitemap')->name('setting.sitemap');
        Route::post('sitemap-submit', 'sitemapSubmit')->name('setting.sitemap.submit');

        Route::get('robot', 'robot')->name('setting.robot');
        Route::post('robot-submit', 'robotSubmit')->name('setting.robot.submit');

        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie');
        Route::post('cookie-submit', 'cookieSubmit')->name('setting.cookie.submit');

        //maintenance_mode
        Route::get('maintenance-mode', 'maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode-submit', 'maintenanceModeSubmit')->name('maintenance.mode.submit');

        // Route::get('setup-hotel', 'setupHotel')->name('setting.setup.hotel');
        // Route::post('setup-hotel', 'addHotel')->name('setting.setup.add.hotel');
        // Route::post('edit-setup-hotel/{id}', 'editHotel')->name('setting.setup.edit.hotel');
        // Route::post('update-setup-hotel/{id}', 'updateHotel')->name('setting.setup.update.hotel');
        // Route::post('status-setup-hotel/{id}', 'statusHotel')->name('setting.setup.status.hotel');
        // Route::post('delete-setup-hotel/{id}', 'deleteHotel')->name('setting.setup.delete.hotel');
    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function () {
        //Template Setting
        Route::get('global/email', 'globalEmail')->name('global.email');
        Route::post('global/email/update', 'globalEmailUpdate')->name('global.email.update');

        Route::get('global/sms', 'globalSms')->name('global.sms');
        Route::post('global/sms/update', 'globalSmsUpdate')->name('global.sms.update');

        Route::get('global/push', 'globalPush')->name('global.push');
        Route::post('global/push/update', 'globalPushUpdate')->name('global.push.update');

        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{type}/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{type}/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting/update', 'emailSettingUpdate')->name('email.update');
        Route::post('email/test', 'emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting/update', 'smsSettingUpdate')->name('sms.update');
        Route::post('sms/test', 'smsTest')->name('sms.test');

        Route::get('notification/push/setting', 'pushSetting')->name('push');
        Route::post('notification/push/setting/update', 'pushSettingUpdate')->name('push.update');
        Route::post('notification/push/setting/upload', 'pushSettingUpload')->name('push.upload');
        Route::get('notification/push/setting/download', 'pushSettingDownload')->name('push.download');
    });

    // Plugin
    Route::controller('ExtensionController')->prefix('extensions')->name('extensions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });

    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('server-info', 'systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
        Route::get('system-update', 'systemUpdate')->name('update');
        Route::post('system-update', 'systemUpdateProcess')->name('update.process');
        Route::get('system-update/log', 'systemUpdateLog')->name('update.log');
    });

    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');
    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key?}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::get('frontend-slug-check/{key}/{id?}', 'frontendElementSlugCheck')->name('sections.element.slug.check');
            Route::get('frontend-element-seo/{key}/{id}', 'frontendSeo')->name('sections.element.seo');
            Route::post('frontend-element-seo-update/{key}/{id}', 'frontendSeoUpdate')->name('sections.element.seo.update');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->group(function () {
            Route::get('manage-pages', 'managePages')->name('manage.pages');
            Route::get('manage-pages/check-slug/{id?}', 'checkSlug')->name('manage.pages.check.slug');
            Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');

            Route::get('manage-seo/{id}', 'manageSeo')->name('manage.pages.seo');
            Route::post('manage-seo/{id}', 'manageSeoStore')->name('manage.pages.seo.store');
        });
    });

    Route::get('/checkhours', [GeneralSettingController::class, 'checkhours'])->name('checkhours');

    // Report room status
    Route::controller('ManageReportController')->prefix('manage')->name('manage.')->group(function () {

            Route::get('room-status', action: 'rommStatus')->name('room.status');
            Route::get('room-status-history', 'roomStatusHistory')->name('room.status.history');
    });
});
