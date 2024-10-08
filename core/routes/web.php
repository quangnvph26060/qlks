<?php

use App\Events\EventRegisterUser;
use App\Jobs\JobSendMail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
//test
Route::get('/send-test-email', function () {
    Mail::raw('Test email', function ($message) {
        $message->to('quang3011003@gmail.com')
                ->subject('Test Email');
    });

    return 'Email sent successfully!';
});



Route::get('demo', function () {

    $data = ['type'=>'EMAIL_REGISTER','name' => 'John Doe', 'email' => 'quang3011003@gmail.com','password'=>'12345678'];
    event(new EventRegisterUser($data));
});


Route::get('/test-notify', function () {
    $user = (object)[
        'id' => 1,
        'email' => 'quang3011003@gmail.com',
        'fullname' => 'văn quang',
        'username' => 'văn quang',
    ];

    $subject = 'Test Notify Email';
    $message = 'This is a test email using the notify function.';

    notify($user, 'DEFAULT', [
        'subject' => $subject,
        'message' => $message,
    ], ['email'], false);

    return 'Notify email sent!';
});
// end test
Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


// Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('room-type-filter', 'filterRoomType')->name('room.type.filter');

    Route::get('updates', 'blog')->name('blog');
    Route::get('full-article/{slug}', 'blogDetails')->name('blog.details');
    Route::get('book-online', 'roomTypes')->name('room.types');
    Route::get('basic-room-filter', 'basicRoomFilter')->name('basic.room.filter');
    Route::get('room-type/{room_number}', 'roomTypeDetails')->name('room.type.details');
    Route::get('room-search', 'checkRoomAvailability')->name('room.available.search');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::post('send-booking-request', 'sendBookingRequest')->name('request.booking');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
    Route::post('subscribe', 'subscribe')->name('subscribe');
});
