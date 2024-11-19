<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegularRoomPrice;
use App\Models\RoomPriceDayOfWeek;
use App\Models\RoomPricePerDay;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function __construct() {}

    private  function addPriceDate($date,$dateCurent)
    {
        $dataDateValue = $date;
        if (empty($dataDateValue)) {
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
        };
        $dataDateArray = explode(", ", $dataDateValue); // các date được chọn bên client

        $datesFromDB = RoomPricePerDay::pluck('date')->toArray(); // các date hiện đang có trong DB
        $differentDates = array_diff($dataDateArray, $datesFromDB); // các date trong DB chưa có 

        $id_room = RoomPricePerDay::pluck('room_price_id')->unique()->toArray(); // các id room tròn bảng 
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
        return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
    }
    private function addPriceDay($day,$dateCurent){
      
        $dataDateValue = $day;
        if (empty($dataDateValue)) {
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
        }; 
        $datesFromDB = RoomPriceDayOfWeek::pluck('day_of_week')->toArray(); // các day hiện đang có trong DB
        $differentDates = array_diff($dataDateValue, $datesFromDB); // các day trong DB chưa có 
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
        return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);


    }
    public function updatePriceDate(Request $request)
    {

        $select = $request->method;
        $response = null;
        switch ($select) {
            case 'date':
                $response = $this->addPriceDate($request->dataDateValue, $request->dateCurent);
                break;
            case 'day':
                $response = $this->addPriceDay($request->checkedValues, $request->dateCurent);
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
        $dates = is_array($data['date']) ? $data['date'] : [$data['date']];
        $dates = array_map('trim', explode(', ', $data['date']));
        $responseMessages = [];

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
}
