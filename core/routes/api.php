<?php
  use App\Models\SetupPricing;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ApipublicController;
use App\Http\Controllers\Api\AutoLoginController;
use App\Models\Room;
use App\Models\RoomTypePrice;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->name('api.')->group(function () {





    Route::controller('AppController')->group(function () {
        Route::get('general-setting', 'generalSetting');
        Route::get('get-countries', 'getCountries');
        Route::get('language/{key}', 'getLanguage');
        Route::get('policies', 'policies');
        Route::get('faq', 'faq');
    });

    Route::namespace('Auth')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::post('login', 'login');
            Route::post('check-token', 'checkToken');
            Route::post('social-login', 'socialLogin');
        });
        Route::post('register', 'RegisterController@register');

        Route::controller('ForgotPasswordController')->group(function () {
            Route::post('password/email', 'sendResetCodeEmail');
            Route::post('password/verify-code', 'verifyCode');
            Route::post('password/reset', 'reset');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('user-data-submit', 'UserController@userDataSubmit');

        //authorization
        Route::middleware('registration.complete')->controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode');
            Route::post('verify-email', 'emailVerification');
            Route::post('verify-mobile', 'mobileVerification');
            Route::post('verify-g2fa', 'g2faVerification');
        });

        Route::middleware(['check.status'])->group(function () {

            Route::middleware('registration.complete')->group(function () {
                Route::get('dashboard', function () {
                    return auth()->user();
                });


                Route::controller('UserController')->group(function () {

                    Route::post('profile-setting', 'submitProfile');
                    Route::post('change-password', 'submitPassword');

                    Route::get('user-info', 'userInfo');

                    //Report
                    Route::any('deposit/history', 'depositHistory');
                    Route::get('transactions', 'transactions');

                    Route::post('add-device-token', 'addDeviceToken');
                    Route::get('push-notifications', 'pushNotifications');
                    Route::post('push-notifications/read/{id}', 'pushNotificationsRead');

                    //2FA
                    Route::get('twofactor', 'show2faForm');
                    Route::post('twofactor/enable', 'create2fa');
                    Route::post('twofactor/disable', 'disable2fa');

                    Route::post('delete-account', 'deleteAccount');
                });

                // Payment
                Route::controller('PaymentController')->group(function () {
                    Route::get('deposit/methods', 'methods');
                    Route::post('deposit/insert', 'depositInsert');
                    Route::post('app/payment/confirm', 'appPaymentConfirm');
                });

                Route::controller('TicketController')->prefix('ticket')->group(function () {
                    Route::get('/', 'supportTicket');
                    Route::post('create', 'storeSupportTicket');
                    Route::get('view/{ticket}', 'viewTicket');
                    Route::post('reply/{id}', 'replyTicket');
                    Route::post('close/{id}', 'closeTicket');
                    Route::get('download/{attachment_id}', 'ticketDownload');
                });
            });
        });

        Route::get('logout', 'Auth\LoginController@logout');
    });
    // api public
    Route::middleware('check.api.token')->group(function () {
        // routes/api.php
        Route::get('/rooms/{hotel}', [ApipublicController::class, 'getRooms']);
        Route::get('/get-hotels', [ApipublicController::class, 'getHotels']);
        Route::get('/get-invoice', [ApipublicController::class, 'getInvoice']);
      
        Route::get('/roomss', function () {
            return '123';
        });
    });
});

 Route::get('/get-payment/{bookingCode}', [ApipublicController::class, 'getPayment']);


Route::post('/user/store', [UserController::class, 'store']);
Route::post('/user/delete', [UserController::class, 'deleteAdmin']);
Route::post('/user/status', [UserController::class, 'statusAdmin']);
Route::post('/user/resetPassword', [UserController::class, 'resetPassword']);

Route::get('/demo', function () {
    $today = "2025-06-07";
    $carbonWeekday = \Illuminate\Support\Carbon::parse($today)->dayOfWeek;
    $customWeekday = $carbonWeekday === 0 ? 8 : $carbonWeekday + 1;
    $subdomain = 'quangdev';

    // Lấy SetupPricing phù hợp
    $setupPricings = SetupPricing::withoutTenant()
        ->where('subdomain', $subdomain)
        ->get()
        ->filter(function ($config) use ($customWeekday, $today) {
            $requirement = json_decode($config->price_requirement, true);
            if (!is_array($requirement)) return false;

            $normalized = collect($requirement)
                ->flatMap(fn($item) => explode(',', $item))
                ->map(fn($item) => trim($item))
                ->filter()
                ->unique()
                ->values()
                ->all();

            return in_array($today, $normalized) || in_array((string) $customWeekday, $normalized);
        })
        ->values();

    // Lấy danh sách setup_pricing_id theo ngày cụ thể (có $today)
    $setupPricingIdsForToday = $setupPricings->filter(function ($config) use ($today) {
        $requirement = json_decode($config->price_requirement, true);
        return in_array($today, $requirement);
    })->pluck('id')->all();

    // Lấy tất cả RoomTypePrice theo setup_pricing_id hợp lệ
    $roomTypePrices = RoomTypePrice::whereIn('setup_pricing_id', $setupPricings->pluck('id')->all())
        ->get();

    // Nhóm theo room_type_id
    $grouped = $roomTypePrices->groupBy('room_type_id');

    $filtered = $grouped->map(function ($prices, $roomTypeId) use ($setupPricingIdsForToday) {
        // Tìm bản có setup_pricing_id thuộc ngày cụ thể trước
        $priceForToday = $prices->first(fn($price) => in_array($price->setup_pricing_id, $setupPricingIdsForToday));

        if ($priceForToday) {
            return $priceForToday;
        }

        // Nếu không có bản ngày cụ thể thì lấy bản đầu tiên (theo setup_pricing_id khác)
        return $prices->first();
    })->values();

    return response()->json([
        'success' => true,
        'message' => 'Danh sách loại phòng áp dụng cấu hình giá hôm nay (đã lọc 1 bản cho mỗi room_type_id)',
        'data' => [
            'today'          => $today,
            'customWeekday'  => $customWeekday,
            'setup_pricings' => $setupPricings,
            'room_types'     => $filtered,
        ]
    ]);
});

Route::get('/login-by-subdomain/{subdomain}', [AutoLoginController::class, 'loginBySubdomain']);


