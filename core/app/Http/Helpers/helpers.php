<?php

use App\Constants\Status;
use App\Lib\Captcha;
use App\Lib\ClientInfo;
use App\Lib\CurlRequest;
use App\Lib\FileManager;
use App\Models\Admin;
use App\Models\BookingActionHistory;
use App\Models\EmailTemplate;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\HotelFacility;
use App\Models\Language;
use App\Models\Role;
use App\Notify\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

// function systemDetails() {
//     $system['name']          = 'viserhotel';
//     $system['version']       = '3.0';
//     $system['build_version'] = '5.0.7';
//     return $system;
// }

function slug($string)
{
    return Str::slug($string);
}

function verificationCode($length)
{
    if ($length == 0) {
        return 0;
    }

    $min = pow(10, $length - 1);
    $max = (int) ($min - 1) . '9';
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters       = '1234567890';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function activeTemplate($asset = false)
{
    $template = session('template') ?? gs('active_template');
    if ($asset) {
        return 'assets/templates/' . $template . '/';
    }

    return 'templates.' . $template . '.';
}

function activeTemplateName()
{
    $template = session('template') ?? gs('active_template');
    return $template;
}

function siteLogo($type = null)
{
    $name = $type ? "/logo_$type.png" : '/logo.png';
    return getImage(getFilePath('logoIcon') . $name);
}
function siteFavicon()
{
    return getImage(getFilePath('logoIcon') . '/favicon.png');
}

function loadReCaptcha()
{
    return Captcha::reCaptcha();
}

function loadCustomCaptcha($width = '100%', $height = 46, $bgColor = '#003')
{
    return Captcha::customCaptcha($width, $height, $bgColor);
}

function verifyCaptcha()
{
    return Captcha::verify();
}

function loadExtension($key)
{
    $extension = Extension::where('act', $key)->where('status', Status::ENABLE)->first();
    return $extension ? $extension->generateScript() : '';
}

function getTrx($length = 12)
{
    $characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function getCode($prefix, $length = 12)
{
    $characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    
    $randomLength = $length - strlen($prefix);
    if ($randomLength <= 0) {
        return substr($prefix, 0, $length); 
    }

    $randomString = '';
    for ($i = 0; $i < $randomLength; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $prefix . $randomString;
}
function getAmount($amount, $length = 2)
{
    $amount = round($amount ?? 0, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = 0, $separate = true, $exceptZeros = false, $currencyFormat = true)
{ //ban đầu $decimal = 2;
    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }
    if ($currencyFormat) {
        if (gs('currency_format') == Status::CUR_BOTH) {
            return gs('cur_sym') . $printAmount . ' ' . __(gs('cur_text'));
        } else if (gs('currency_format') == Status::CUR_TEXT) {
            return $printAmount . ' ' . __(gs('cur_text'));
        } else {
            return gs('cur_sym') . $printAmount;
        }
    }
    return $printAmount;
}

function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet)
{
    return "https://api.qrserver.com/v1/create-qr-code/?data=$wallet&size=300x300&ecc=m";
}

function keyToTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}

function keyBookingAction($text)
{
    switch ($text) {
        case "added_extra_service":
            return "Đã thêm dịch vụ bổ sung";
            break;
        case "key_handover":
            return "Bàn giao chìa khóa";
            break;
        case "payment_received":
            return "Đã nhận thanh toán";
            break;
        case "book_room":
            return "Đặt phòng";
            break;
        case "checked_out":
            return "Trả phòng";
            break;
        case "cancel_booking":
            return "Hủy đặt phòng";
            break;
        case "approve_booking_request":
            return "Yêu cầu phê duyệt đặt phòng";
            break;
        default:
            return "Hành động không xác định";
            break;
    }
}




function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}

function strLimit($title = null, $length = 10)
{
    return Str::limit($title, $length);
}

function getIpInfo()
{
    $ipInfo = ClientInfo::ipInfo();
    return $ipInfo;
}

function osBrowser()
{
    $osBrowser = ClientInfo::osBrowser();
    return $osBrowser;
}

// function getTemplates() {
//     $param['purchasecode'] = env("PURCHASECODE");
//     $param['website']      = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
//     $url                   = VugiChugi::gttmp() . systemDetails()['name'];
//     $response              = CurlRequest::curlPostContent($url, $param);
//     if ($response) {
//         return $response;
//     } else {
//         return null;
//     }
// }

function getPageSections($arr = false)
{
    $jsonUrl  = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}

function getImage($image, $size = null)
{
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }

    if ($size) {
        return route('placeholder.image', $size);
    }
    return asset('assets/images/default.png');
}

function notify($user, $templateName, $shortCodes = null, $sendVia = null, $createLog = true, $pushImage = null)
{
    $globalShortCodes = [
        'site_name'       => gs('site_name'),
        'site_currency'   => gs('cur_text'),
        'currency_symbol' => gs('cur_sym'),
    ];

    if (gettype($user) == 'array') {
        $user = (object) $user;
    }

    $shortCodes = array_merge($shortCodes ?? [], $globalShortCodes);

    $notify               = new Notify($sendVia);
    $notify->templateName = $templateName;
    $notify->shortCodes   = $shortCodes;
    $notify->user         = $user;
    $notify->createLog    = $createLog;
    $notify->pushImage    = $pushImage;
    $notify->userColumn   = isset($user->id) ? $user : 'user_id';
    $notify->send();
}

function getPaginate($paginate = null)
{
    if (!$paginate) {
        $paginate = gs('paginate_number');
    }
    return $paginate;
}

function paginateLinks($data)
{
    return $data->appends(request()->all())->links();
}

function menuActive($routeName, $type = null, $param = null)
{
    if ($type == 3) {
        $class = 'side-menu--open';
    } else if ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } else if (request()->routeIs($routeName)) {
        if ($param) {
            $routeParam = array_values(@request()->route()->parameters ?? []);
            if (strtolower(@$routeParam[0]) == strtolower($param)) {
                return $class;
            } else {
                return;
            }
        }
        return $class;
    }
}

function fileUploader($file, $location, $size = null, $old = null, $thumb = null, $filename = null)
{
    $fileManager           = new FileManager($file);
    $fileManager->path     = $location;
    $fileManager->size     = $size;
    $fileManager->old      = $old;
    $fileManager->thumb    = $thumb;
    $fileManager->filename = $filename;
    $fileManager->upload();
    return $fileManager->filename;
}

function fileManager()
{
    return new FileManager();
}

function getFilePath($key)
{
    return fileManager()->$key()->path;
}

function getFileSize($key)
{
    return fileManager()->$key()->size;
}

function getThumbSize($key)
{
    return fileManager()->$key()->thumb;
}

function getFileExt($key)
{
    return fileManager()->$key()->extensions;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'Y-m-d h:i A')
{
    if (!$date) {
        return '-';
    }
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

function getContent($dataKeys, $singleQuery = false, $limit = null, $orderById = false)
{

    $templateName = activeTemplateName();
    if ($singleQuery) {
        $content = Frontend::where('tempname', $templateName)->where('data_keys', $dataKeys)->orderBy('id', 'desc')->first();
    } else {
        $article = Frontend::where('tempname', $templateName);
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id', 'desc')->get();
        }
    }
    return $content;
}

function urlPath($routeName, $routeParam = null)
{
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path     = str_replace($basePath, '', $url);
    return $path;
}

function showMobileNumber($number)
{
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function showEmailAddress($email)
{
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}

function getRealIP()
{
    $ip = $_SERVER["REMOTE_ADDR"];
    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    return $ip;
}

function appendQuery($key, $value)
{
    return request()->fullUrlWithQuery([$key => $value]);
}

function dateSort($a, $b)
{
    return strtotime($a) - strtotime($b);
}

function dateSorting($arr)
{
    usort($arr, "dateSort");
    return $arr;
}
// setting and setup hotels
function gs($key = null)
{
    $general = Cache::get('GeneralSetting');
    if (!$general) {
        $general = GeneralSetting::first();
        Cache::put('GeneralSetting', $general);
    }
    if ($key) {
        return @$general->$key;
    }

    return $general;
}
function hf($key = null) // hotel_facilities
{
    $general = Cache::get('HotelFacility');
    if (!$general) {
        $general = HotelFacility::active()->first();
        Cache::put('HotelFacility', $general);
    }
    if ($key) {
        return @$general->$key;
    }

    return $general;
}
// ma cơ sở 
function unitCode(){
    $unit = Cache::get('Unit_code');
    if (!$unit) {
        $admin = Auth::guard('admin')->user(); 
            $unit_code = $admin->unit_code;
        Cache::put('Unit_code', $unit_code);
        $unit = $unit_code;
    }
    return $unit; 
}
// end setting and setup hotels
function isImage($string)
{
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $fileExtension     = pathinfo($string, PATHINFO_EXTENSION);
    if (in_array($fileExtension, $allowedExtensions)) {
        return true;
    } else {
        return false;
    }
}

function isHtml($string)
{
    if (preg_match('/<.*?>/', $string)) {
        return true;
    } else {
        return false;
    }
}

function convertToReadableSize($size)
{
    preg_match('/^(\d+)([KMG])$/', $size, $matches);
    $size = (int) $matches[1];
    $unit = $matches[2];

    if ($unit == 'G') {
        return $size . 'GB';
    }

    if ($unit == 'M') {
        return $size . 'MB';
    }

    if ($unit == 'K') {
        return $size . 'KB';
    }

    return $size . $unit;
}

function frontendImage($sectionName, $image, $size = null, $seo = false)
{
    if ($seo) {
        return getImage('assets/images/frontend/' . $sectionName . '/seo/' . $image, $size);
    }
    return getImage('assets/images/frontend/' . $sectionName . '/' . $image, $size);
}

function actionTakenBy($item)
{
    return @Admin::find($item->admin_id)->name;
}

function bookingActionRecord($bookingId, $admin, $remark)
{
    $action             = new BookingActionHistory();
    $action->booking_id = $bookingId;
    $action->remark     = $remark;
    $action->admin_id   = $admin;
    $action->save();
}

function getSelectedRooms($bookedRooms, $numberOfRooms)
{
    asort($bookedRooms);

    $selectedRooms = [];
    $i             = 0;

    foreach ($bookedRooms as $key => $value) {
        if ($i == $numberOfRooms) {
            break;
        } else {
            $i++;
            $selectedRooms[] = $key;
        }
    }
    return $selectedRooms;
}

function can($code)
{
    return Role::hasPermission($code);
}

function todaysDate()
{
    return Carbon::now()->toDateString();
}

function authAdmin()
{
    return auth()->guard('admin')->user();
}
function authCleanRoom()
{
    return authAdmin()->role_id == Status::ROLE_DON_PHONG ? true : false;
}
function authRoleAdmin()
{
    return authAdmin()->role_id  == Status::ROLE_ADMIN ? true : false;
}
function authRoleLeTan()
{
    return authAdmin()->role_id == Status::ROLE_LE_TAN ? true : false;
}
function getLanguages($local = false)
{
    $language = Language::all();
    if (!$local) {
        return $language;
    }

    $default = $language->where('code', session('lang'))->first();
    if ($default) {
        return $default;
    }

    return $language->where('is_default', Status::YES)->first();
}
function updateMail($data)
{

    $envFilePath = base_path('.env');
    $envContent = file_get_contents($envFilePath);

    $mail = gs('mail_config');
    $data_mail = json_encode($mail);
    $mail_array = json_decode($data_mail, true);
    $newMailMailer = $data['name'] ?? $mail_array['smtp'];
    $newMailHost = $data['host'] ?? $mail_array['name'];
    $newMailPort = $data['port'] ?? $mail_array['port'];
    $newMailUsername = $data['username'] ?? $mail_array['username'];
    $newMailPassword = $data['password'] ?? $mail_array['password'];
    $newMailEncryption = $data['enc'] ?? $mail_array['enc'];

    $envContent = preg_replace('/(MAIL_MAILER=)(.*)/', 'MAIL_MAILER=' . $newMailMailer, $envContent);
    $envContent = preg_replace('/(MAIL_HOST=)(.*)/', 'MAIL_HOST=' . $newMailHost, $envContent);
    $envContent = preg_replace('/(MAIL_PORT=)(.*)/', 'MAIL_PORT=' . $newMailPort, $envContent);
    $envContent = preg_replace('/(MAIL_USERNAME=)(.*)/', 'MAIL_USERNAME=' . $newMailUsername, $envContent);
    $envContent = preg_replace('/(MAIL_PASSWORD=)(.*)/', 'MAIL_PASSWORD=' . $newMailPassword, $envContent);
    $envContent = preg_replace('/(MAIL_ENCRYPTION=)(.*)/', 'MAIL_ENCRYPTION=' . $newMailEncryption, $envContent);

    file_put_contents($envFilePath, $envContent);
}


/**
 * Lưu hình ảnh và trả về đường dẫn.
 *
 * @param string $inputName
 * @param string $directory
 * @return string|null
 */
function saveImages($request, string $inputName, string $directory = 'images', $width = 150, $height = 150): ?array
{
    $paths = [];

    // Kiểm tra xem có file không
    if ($request->hasFile($inputName)) {
        // Lấy tất cả các file hình ảnh
        $images = $request->file($inputName);

        if (!is_array($images)) {
            $images = [$images]; // Đưa vào mảng nếu chỉ có 1 ảnh
        }

        // Tạo instance của ImageManager
        $manager = new ImageManager(new Driver());

        foreach ($images as $image) {
            // Đọc hình ảnh từ đường dẫn thực
            $img = $manager->read($image->getRealPath());

            // Thay đổi kích thước
            $img->resize($width, $height);

            // Tạo tên file duy nhất
            $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();

            // Lưu hình ảnh đã được thay đổi kích thước vào storage
            Storage::put($directory . '/' . $filename, $img->encode());

            // Lưu đường dẫn vào mảng
            $paths[] = $directory . '/' . $filename;
        }

        // Trả về danh sách các đường dẫn
        return $paths;
    }

    return null;
}

function getRandomColor()
{
    // $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info','dark'];
    $colors = ['primary'];
    return 'bg-' . $colors[array_rand($colors)];
}



function showImageStorage($path)
{
    if (Storage::exists($path)) {
        return Storage::url($path);
    }

    return asset('assets/images/default.png');
}

function findTemplateEmail ($data)
{
    $emailTempalte =   EmailTemplate::active()->where('act', $data)->first();

    return $emailTempalte;
}

function writeCccd($imagePath)
{
    $curl = curl_init();

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $imagePath);

    $cFile = curl_file_create($imagePath, $mime, basename($imagePath));
    $data = array("image" => $cFile, "filename" => $cFile->postname);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.fpt.ai/vision/idr/vnm",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "api-key: mSFO5W71CkGvdF4Xx3hua9F9WCSTHMiv"
        ),
        CURLOPT_RETURNTRANSFER => true,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $decodedData = json_decode(stripslashes($response), true);
    if ($err) {
        return ['error' => "cURL Error #" . $err];
    } else {
        return $decodedData;
    }
}
