<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegularRoomPrice;
use App\Models\Room;
use App\Models\RoomPriceDayOfWeek;
use App\Models\RoomPricePerDay;
use App\Models\RoomPricesAdditionalHour;
use App\Models\RoomPricesWeekdayHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class PriceController extends Controller
{
    public function __construct() {}

    private  function addPriceDate($date, $dateCurent, $option)
    {
        $responseMessages = [];
        $dataDateValue = $date;
        if (empty($dataDateValue)) {
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
        };
        $dataDateArray = explode(", ", $dataDateValue); // các date được chọn bên client
        if ($option === "addDate") {
            $datesFromDB = RoomPricePerDay::pluck('date')->toArray(); // các date hiện đang có trong DB
            $differentDates = array_diff($dataDateArray, $datesFromDB); // các date trong DB chưa có
            $id_room = RoomPricePerDay::pluck('room_price_id')->unique()->toArray(); // các id room trong bảng
            foreach ($id_room as $item) {
                $result = RoomPricePerDay::where('room_price_id', $item)->where('date', $dateCurent)->first();
                if ($result) {
                    foreach ($differentDates as $date) {
                        $roomPricePerDay = new RoomPricePerDay();
                        $roomPricePerDay->room_price_id   = $item;
                        $roomPricePerDay->date            = $date;
                        $roomPricePerDay->hourly_price    = $result->hourly_price;
                        $roomPricePerDay->daily_price     = $result->daily_price;
                        $roomPricePerDay->overnight_price = $result->overnight_price;
                        $roomPricePerDay->save();
                    }
                }
            }
            $responseMessages[] = ['status' => 'pricedate', 'message' => 'Cập nhật giá thành công'];
        } else if ($option === "delDate") {
            RoomPricePerDay::whereIn('date', $dataDateArray)->delete();
            $responseMessages[] = ['status' => 'pricedate', 'message' => 'Cập nhật giá thành công'];
        }

        return response()->json(['status' => 'success', 'message' => $responseMessages]);
    }

    private function addPriceDay($day, $dateCurent, $option)
    {
        $responseMessages = [];
        $dataDateValue = $day;
        if (empty($dataDateValue)) {
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
        };
        $datesFromDB = RoomPriceDayOfWeek::pluck('day_of_week')->toArray(); // các day hiện đang có trong DB
        $differentDates = array_diff($dataDateValue, $datesFromDB); // các day trong DB chưa có
        if ($option === "addDate") {
            $id_room = RoomPriceDayOfWeek::pluck('room_price_id')->unique()->toArray(); // các id room tròn bảng
            foreach ($id_room as $item) {
                $result = RoomPriceDayOfWeek::where('room_price_id', $item)->where('day_of_week', $dateCurent)->first();
                if ($result) {
                    foreach ($differentDates as $date) {
                        $roomPricePerDay = new RoomPriceDayOfWeek();
                        $roomPricePerDay->room_price_id   = $item;
                        $roomPricePerDay->day_of_week            = $date;
                        $roomPricePerDay->hourly_price    = $result->hourly_price;
                        $roomPricePerDay->daily_price     = $result->daily_price;
                        $roomPricePerDay->overnight_price = $result->overnight_price;
                        $roomPricePerDay->save();
                    }
                }
            }
            $responseMessages[] = ['status' => 'priceday', 'message' => 'Cập nhật giá thành công'];
        } else if ($option === "delDate") {
            RoomPriceDayOfWeek::whereIn('day_of_week', $dataDateValue)->delete();
            $responseMessages[] = ['status' => 'priceday', 'message' => 'Cập nhật giá thành công'];
        }

        return response()->json(['status' => 'success', 'message' => $responseMessages]);
    }
    public function updatePriceDate(Request $request)
    {
        $select = $request->method;
        $response = null;
        switch ($select) {
            case 'date':
                $response = $this->addPriceDate($request->dataDateValue, $request->dateCurent, $request->flag);
                break;
            case 'day':
                $response = $this->addPriceDay($request->checkedValues, $request->dateCurent, $request->flag);
                break;
            default:
                return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
                break;
        }
        return $response;
    }



    public function roomPricePerDay()
    {
        $data = RoomPricePerDay::all();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function roomPricePerDayNew()
    {
        $data = RoomPricePerDay::select('date',  'room_price_id') // Chọn tất cả các cột bạn cần
            ->groupBy('date') // Nhóm theo 'date'
            ->get();

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function roomPricePerDayOfWeek()
    {
        $data = RoomPriceDayOfWeek::all();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function switchPrice(Request $request)
    {

        $chose = $request['method'];
        $data = [
            'price'   => $request['price'],
            'room_id' => $request['room_id'],
            'date'    => $request['date'] ?? "",
            'pricehours' => $request['pricehours']
        ];
        $response = null;
        switch ($chose) {
            case 'method_hourly':
                $response = $this->priceHourly($data);
                break;
            case 'method_fullday':
                $response = $this->priceFullDay($data);
                break;
            case 'method_overnightPrice':
                $response = $this->priceOverNightPrice($data);
                break;
            case 'method_overnightPricedate':
                $response = $this->priceOverNightPriceDate($data);
                break;
            case 'method_fulldaydate':
                $response = $this->priceFullDayDate($data);
                break;
            case 'method_hourlydate':
                $response = $this->priceHourlyDate($data);
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Method not found']);
        }
        return $response;
    }
    public function updateRoomPriceDate($data, $priceType)
    {
        $responseMessages = [];
        $dates = is_array($data['date']) ? $data['date'] : [$data['date']];
        $dates = array_map('trim', explode(', ', $data['date']));


        $this->priceRoomHour($data);

        foreach ($dates as $date) {
            if (preg_match('/^\d+$/', str_replace('-', '', $date))) {
                $roomPrice = RoomPricePerDay::where('room_price_id', $data['room_id'])->where('date', $date)->first();
                
                if (!$roomPrice) {
                    $roomPrice = new RoomPricePerDay();
                    $roomPrice->room_price_id = $data['room_id'];
                    $roomPrice->date = $date;
                }
                $this->updatePrice($roomPrice, $priceType, $data['price']);
                $responseMessages[] = ['status' => 'success', 'message' => 'Cập nhật giá thành công'];
            } else {
                $roomPrice = RoomPriceDayOfWeek::where('room_price_id', $data['room_id'])->where('day_of_week', $date)->first();

                if (!$roomPrice) {
                    $roomPrice                = new RoomPriceDayOfWeek();
                    $roomPrice->room_price_id = $data['room_id'];
                    $roomPrice->day_of_week   = $date;
                }

                $this->updatePrice($roomPrice, $priceType, $data['price']);
                $responseMessages[] = ['status' => 'success', 'message' => 'Cập nhật giá thành công'];
            }
        }

        return response()->json(['status' => 'success', 'messages' => $responseMessages]);
    }
    private function updatePrice($roomPrice, $priceType, $price)
    {
        switch ($priceType) {
            case 'hourly':
                $roomPrice->hourly_price = $price;
                break;
            case 'overnight':
                $roomPrice->overnight_price = $price;
                break;
            case 'full_day':
                $roomPrice->daily_price = $price;
                break;
        }
        $roomPrice->save();
    }
    public function priceHourlyDate($data)
    {
        return $this->updateRoomPriceDate($data, 'hourly');
    }

    public function priceOverNightPriceDate($data)
    {
        return $this->updateRoomPriceDate($data, 'overnight');
    }

    public function priceFullDayDate($data)
    {
        return $this->updateRoomPriceDate($data, 'full_day');
    }

    public function updateRoomPrice($data, $priceType)
    {
        $roomPrice = RegularRoomPrice::where('room_price_id', $data['room_id'])->first();

        if (!$roomPrice) {
            $roomPrice = new RegularRoomPrice();
            $roomPrice->room_price_id = $data['room_id'];
        }

        switch ($priceType) {
            case 'hourly':
                $roomPrice->hourly_price = $data['price'];
                break;
            case 'overnight':
                $roomPrice->overnight_price = $data['price'];
                break;
            case 'full_day':
                $roomPrice->daily_price = $data['price'];
                break;
        }

        if ($data['date'] == null) {
            $this->priceRoomWeekday($data);
        }
        $roomPrice->save();


        return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
    }

    public function priceOverNightPrice($data)
    {
        return $this->updateRoomPrice($data, 'overnight');
    }

    public function priceFullDay($data)
    {
        return $this->updateRoomPrice($data, 'full_day');
    }

    public function priceHourly($data)
    {
        return $this->updateRoomPrice($data, 'hourly');
    }


    public function priceRoomHour($data)
    {
        if (isset($data['pricehours'])) {
            $hours = array_column($data['pricehours'], 0);
            foreach ($data['pricehours'] as $key => $item) {
                $check = RoomPricesAdditionalHour::where('date', $data['date'])
                    ->where('room_price_id', $data['room_id'])
                    ->where('hour', $item[0])
                    ->first();
                if ($check) {
                    $check->update([
                        'price' => $item[1],
                    ]);
                } else {
                    RoomPricesAdditionalHour::create([
                        'date' => $data['date'],
                        'hour' => $item[0],
                        'price' => $item[1],
                        'room_price_id' => $data['room_id']
                    ]);
                }
            }
            RoomPricesAdditionalHour::where('date', $data['date'])
                ->where('room_price_id', $data['room_id'])
                ->whereNotIn('hour', $hours)
                ->delete();
        } else {
            RoomPricesAdditionalHour::where('date', $data['date'])
                ->where('room_price_id', $data['room_id'])
                ->delete();
        }
    }

    public function priceHours(Request $request)
    {
        // Lấy danh sách giá phòng theo giờ
        $list = RoomPricesAdditionalHour::where('room_price_id', $request->room_id)
            ->where('date', $request->date)
            ->get();

        // Log::info($list);
        // Trả về kết quả dưới dạng JSON
        return response()->json(['data' => $list], 200);
    }


    public function priceRoomWeekday($data)
    {
        if (isset($data['pricehours'])) {
            $hours = array_column($data['pricehours'], 0);
            foreach ($data['pricehours'] as $key => $item) {
                $check = RoomPricesWeekdayHour::where('room_price_id', $data['room_id'])
                    ->where('hour', $item[0])
                    ->first();
                if ($check) {
                    $check->update([
                        'price' => $item[1],
                    ]);
                } else {
                    RoomPricesWeekdayHour::create([
                        'hour' => $item[0],
                        'price' => $item[1],
                        'room_price_id' => $data['room_id']
                    ]);
                }
            }
            RoomPricesWeekdayHour::where('room_price_id', $data['room_id'])
                ->whereNotIn('hour', $hours)
                ->delete();
        } else {
            RoomPricesWeekdayHour::where('room_price_id', $data['room_id'])
                ->delete();
        }
    }
    public function priceweek(Request $request)
    {
        // Lấy danh sách giá phòng theo giờ
        $list = RoomPricesWeekdayHour::where('room_price_id', $request->room_id)->get();
        return response()->json(['data' => $list], 200);
    }


    public function rooms()
    {
        $rooms = Room::active()->get();
        Log::info($rooms);
        return response()->json(['status' => 'success', 'data' => $rooms]);
    }
}
