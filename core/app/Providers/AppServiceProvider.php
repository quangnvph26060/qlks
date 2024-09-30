<?php

namespace App\Providers;

use App\Http\Resources\Room\RoomCollection;
use App\Models\User;
use App\Lib\Searchable;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Wishlist;
use App\Constants\Status;
use App\Models\SupportTicket;
use App\Models\BookingRequest;
use App\Models\AdminNotification;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::mixin(new Searchable);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!cache()->get('SystemInstalled')) {
            $envFilePath = base_path('.env');
            if (!file_exists($envFilePath)) {
                header('Location: install');
                exit;
            }
            $envContents = file_get_contents($envFilePath);
            if (empty($envContents)) {
                header('Location: install');
                exit;
            } else {
                cache()->put('SystemInstalled', true);
            }
        }


        $activeTemplate = activeTemplate();
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['emptyMessage'] = 'Không tìm thấy dữ liệu';
        view()->share($viewShare);


        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'             => User::banned()->count(),
                'emailUnverifiedUsersCount'    => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount'   => User::mobileUnverified()->count(),
                'pendingTicketCount'           => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount'         => Deposit::pending()->count(),
                'delayedCheckoutCount'         => Booking::delayedCheckout()->count(),
                'refundableBookingCount'       => Booking::refundable()->count(),
                'pendingCheckInsCount'         => Booking::active()->keyNotGiven()->whereDate('check_in', '<=', now())->count(),
                //       'updateAvailable'              => version_compare(gs('available_version'), systemDetails()['version'], '>') ? 'v' . gs('available_version') : false,
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'bookingRequestCount'    => BookingRequest::initial()->count(),
                'adminNotifications' => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if (gs('force_ssl')) {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();
        // Paginator::defaultView('vendor.pagination.custom');

        View::composer('*', function ($view) {
            $wishLists = Wishlist::with('room.roomType', 'room.roomPricesActive')
                ->where('user_id', Auth::id())
                ->get();

            $wishListRooms = new RoomCollection($wishLists);

            dd($wishListRooms);

            $view->with([
                'wishLists' => $wishListRooms,
            ]);
        });


    }
}
