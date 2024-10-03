<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Page;
use App\Models\Room;
use App\Models\User;
use App\Models\Amenity;
use App\Models\Facility;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\RoomType;
use App\Models\Wishlist;
use App\Constants\Status;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\BookingRequest;
use App\Models\SupportMessage;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
    public function index()
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle = 'Home';
        $sections = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }


    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blog()
    {
        $pageTitle = 'Latest Updates';
        $blogs     = Frontend::where('tempname', activeTemplateName())->where('data_keys', 'blog.element')->orderBy('id', 'desc')->paginate(getPaginate(9));
        return view('Template::blog', compact('pageTitle', 'blogs'));
    }

    public function blogDetails($slug)
    {
        $blog = Frontend::where('tempname', activeTemplateName())->where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle = $blog->data_values->title;
        $seoContents = $blog->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('blog', $seoContents->image, '1000x700', true) : null;

        $blogLists = Frontend::where('tempname', activeTemplateName())->where('slug', '!=', $slug)->where('data_keys', 'blog.element')->latest()->limit(10)->get();
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'blogLists'));
    }


    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:255|unique:subscribers',
            ],
            [
                'email.unique' => 'You are already subscribed'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();
        $notify[] = ['success', 'Subscribed Successfully'];
        return response()->json(['success' => 'Subscribe successfully']);
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Chế độ bảo trì';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }

    public function roomTypes()
    {
        $pageTitle = 'Loại phòng';
        // $roomTypes = RoomType::active()->with('images', 'amenities')->with(['images', 'amenities', 'rooms.roomPricesActive'])->get();
        $rooms = Room::active()->has('roomPricesActive')->with(['images', 'amenities:title', 'facilities:title', 'roomPricesActive'])->where('status', 1)->get();
        $amenities = Amenity::all();
        $facilities = Facility::all();

        return view('Template::room.types', compact('pageTitle', 'rooms', 'amenities', 'facilities'));
    }

    public function basicRoomFilter(Request $request)
    {
        $query = Room::query()->active()->has('roomPricesActive')->with(['amenities:title', 'facilities:title', 'roomPricesActive']);

        // Lọc theo giá tối thiểu
        $query->when($request->priceMin && $request->priceMax, function ($q) use ($request) {
            // Loại bỏ dấu phẩy và chuỗi "VND"
            $minPrice = str_replace(['.', ',', ' VND'], '', $request->priceMin);
            $maxPrice = str_replace(['.', ',', ' VND', '+'], '', $request->priceMax);

            return $q->whereHas('roomPricesActive', function ($subQuery) use ($minPrice, $maxPrice) {
                return $subQuery->whereBetween('price', [(int)$minPrice, (int)$maxPrice]);
            });
        });



        // Lọc theo tiện ích
        $query->when($request->facilities, function ($q) use ($request) {
            return $q->whereHas('facilities', function ($subQuery) use ($request) {
                $subQuery->whereIn('facility_id', $request->facilities);
            });
        });

        // Lọc theo tiện nghi
        $query->when($request->amenities, function ($q) use ($request) {
            return $q->whereHas('amenities', function ($subQuery) use ($request) {
                $subQuery->whereIn('amenities_id', $request->amenities);
            });
        });

        $data = $query->get();

        return response()->json([
            'data' => view('templates.basic.room.results', compact('data'))->render(),
        ]);
    }

    public function filterRoomType(Request $request)
    {
        $pageTitle = 'Loại phòng';
        $roomTypes = RoomType::active();
        $date      = explode('-', $request->date);

        $request->merge([
            'check_in'  => trim(@$date[0]),
            'check_out' => trim(@$date[1]),
        ]);

        $validator = Validator::make($request->all(), [
            'check_in'  => 'required|date_format:m/d/Y|after:yesterday',
            'check_out' => 'required|date_format:m/d/Y|after:check_in',
        ]);


        if ($request->check_in || $request->check_out) {
            if ($request->check_in) {
                if (todaysDate() > Carbon::parse($request->check_in)->format('Y-m-d')) {
                    $notify[] = ['error', 'Check In date can\'t be less than current date'];
                    return back()->withNotify($notify);
                }
            }

            if ($request->check_out) {
                if (todaysDate() > Carbon::parse($request->check_out)->format('Y-m-d')) {
                    $notify[] = ['error', 'Check Out date can\'t be less than current date'];
                    return back()->withNotify($notify);
                }

                if ($request->check_in) {
                    if (Carbon::parse($request->check_out)->format('Y-m-d') < Carbon::parse($request->check_in)->format('Y-m-d')) {
                        $notify[] = ['error', 'Check Out date can\'t be less than check in date'];
                        return back()->withNotify($notify);
                    }
                } else {
                    $notify[] = ['error', 'Check In date can\'t be empty'];
                    return back()->withNotify($notify);
                }
            }

            session()->put('users_date', [
                'checkin' => $request->check_in,
                'check_out' => $request->check_out,
            ]);

            $roomTypes = $roomTypes
                ->withCount(['rooms as total_rooms' => function ($q) {
                    $q->active();
                }])
                ->addSelect(['booked_rooms' => function ($subQuery) use ($request) {
                    $subQuery->selectRaw('COUNT(DISTINCT room_id)')
                        ->from('booked_rooms')
                        ->join('rooms', 'booked_rooms.room_id', 'rooms.id')
                        ->where('rooms.status', Status::ENABLE)
                        ->where('booked_rooms.status', Status::ROOM_ACTIVE)
                        ->whereBetween('booked_for', [Carbon::parse($request->check_in)->format('Y-m-d'), Carbon::parse($request->check_out)->format('Y-m-d')])
                        ->whereColumn('booked_rooms.room_type_id', 'room.id');
                }])
                ->selectRaw('(SELECT total_rooms - booked_rooms) as available_rooms')
                ->havingRaw('(total_rooms - booked_rooms) > 0');
        }

        if ($request->total_adult) {
            $roomTypes = $roomTypes->where('total_adult', '>=', $request->total_adult);
        }
        if ($request->total_child) {
            $roomTypes = $roomTypes->where('total_child', '>=', $request->total_child);
        }

        $roomTypes    = $roomTypes->with('images', 'amenities')->paginate(getPaginate(6));

        if ($request->banner_form) {
            $roomType = RoomType::active()->with(['rooms' => function ($room) {
                $room->active();
            }])->get();

            return view('Template::room.types', compact('pageTitle', 'roomTypes'));
        }
    }

    public function roomTypeDetails($room_number)
    {
        $room = Room::with('amenities', 'facilities', 'images')->where('room_number', $room_number)->first();
        abort_if(!$room, 404);
        $pageTitle = $room->room_number;

        return view('Template::room.details', compact('pageTitle', 'room'));
    }

    public function sendBookingRequest(Request $request)
    {
        // dd($request->check_in, $request->check_out);
        $rules = [];

        if ($request->check_in) {
            // Chuyển từ Y-m-d sang m/d/Y

            $rules['check_in'] = 'required|after:today';
        }

        if ($request->check_out) {
            // Chuyển từ Y-m-d sang m/d/Y
            $rules['check_out'] = 'nullable|after_or_equal:check_in';
        }



        // Kiểm tra nếu room_id có trong request
        if ($request->room_id) {
            $rules['room_id'] = 'required|exists:rooms,id';
        }

        // Kiểm tra ngày check_in và check_out cho trường hợp thứ hai
        if ($request->check_in_1) {
            $rules['check_in_1'] = 'required|date_format:m/d/Y|after:today';
        }

        if ($request->check_out_1) {
            $rules['check_out_1'] = 'required|date_format:m/d/Y|after_or_equal:check_in_1';
        }

        $request->validate($rules);

        // Kiểm tra người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('BOOKING_REQUEST', true);
        }

        // Kiểm tra số lượng phòng khả dụng
        if ($this->getMinimumAvailableRoom($request) < 1) {
            return back()->withNotify(['error', 'Room not available']);
        }

        $user = Auth::user();
        $checkInDate = Carbon::parse($request->check_in ?? $request->check_in_1);
        $checkOutDate = Carbon::parse($request->check_out ?? $request->check_out_1);

        // Tạo BookingRequest trong transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::transaction(function () use ($request, $user, $checkInDate, $checkOutDate) {
            $bookRequest = BookingRequest::create([
                'user_id' => $user->id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
            ]);

            $totalAmount = 0;
            $bookingRequestItems = [];

            // Trường hợp khi có room_id
            if ($request->room_id) {
                $room = Room::with('roomPricesActive:price')->findOrFail($request->room_id);
                $roomPrice = $room->roomPricesActive->first()->price;

                $totalDays = $checkInDate->diffInDays($checkOutDate);
                $totalFare = $roomPrice * $totalDays;

                $taxCharge = $totalFare * config('app.tax_rate') / 100;
                $totalAmount += $totalFare + $taxCharge;

                $bookingRequestItems[] = [
                    'room_id' => $room->id,
                    'unit_fare' => $roomPrice,
                    'tax-charge' => $taxCharge,
                ];
            } else {
                /**
                 * @var User $user
                 */
                $wishLists = $user->wishlist()->where('publish', 1)
                    ->with('room.roomPricesActive:price')->get();

                foreach ($wishLists as $item) {
                    $roomPrice = $item->room->roomPricesActive->first()->price;

                    $totalDays = $checkInDate->diffInDays($checkOutDate);
                    $totalFare = $roomPrice * $totalDays;

                    $taxCharge = $totalFare * config('app.tax_rate') / 100;
                    $totalAmount += $totalFare + $taxCharge;
                    $bookingRequestItems[] = [
                        'room_id' => $item->room->id,
                        'unit_fare' => $roomPrice,
                        'tax-charge' => $taxCharge,
                    ];

                    $item->delete();
                }
            }
            // Lưu các mục đặt phòng vào BookingRequest
            $bookRequest->bookingRequestItems()->createMany($bookingRequestItems);

            // Cập nhật tổng số tiền
            $bookRequest->total_amount = $totalAmount;
            $bookRequest->save();

            // Gửi thông báo cho admin
            AdminNotification::create([
                'user_id' => $user->id,
                'title' => $user->fullname . " đã yêu cầu đặt phòng",
                'click_url' => route('admin.request.booking.approve', $bookRequest->id),
            ]);
        });

        return redirect()->route('user.booking.request.all')
            ->with('success', 'Yêu cầu đặt chỗ đã được gửi thành công');
    }


    public function checkRoomAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_in'     => 'required|date_format:m/d/Y|after:yesterday',
            'check_out'    => 'required|date_format:m/d/Y|after_or_equal:check_in'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $availableRoom = $this->getMinimumAvailableRoom($request);

        return $availableRoom
            ? response()->json(['success' => $availableRoom])
            : response()->json(['error' => 'Không có phòng trống giữa những ngày này']);
    }



    protected function getMinimumAvailableRoom($request)
    {
        $checkInDate = Carbon::parse($request->check_in);
        $checkOutDate = Carbon::parse($request->check_out);
        $dateWiseAvailableRoom = [];

        for ($date = $checkInDate; $date <= $checkOutDate; $date->addDay()) {
            $bookedRooms = Room::where('id', $request->room_id)
                ->whereHas('booked', function ($query) use ($date) {
                    $query->whereDate('booked_for', $date);
                })->count();

            $dateWiseAvailableRoom[] = 1 - $bookedRooms;  // Mỗi yêu cầu chỉ đặt một phòng
        }

        return min($dateWiseAvailableRoom);
    }

    public function amenities()
    {

        return response()->json($amenities);
    }
}
