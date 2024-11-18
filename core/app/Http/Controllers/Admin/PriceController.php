<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegularRoomPrice;
use App\Models\RoomPricePerDay;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function __construct() {}

    public function updatePriceDate(Request $request){

        $dataDateArray = explode(", ", $request->dataDateValue); // các date được chọn bên client

        $datesFromDB = RoomPricePerDay::pluck('date')->toArray(); // các date hiện đang có trong DB
        $differentDates = array_diff($dataDateArray, $datesFromDB); // các date trong DB chưa có 

        $id_room = RoomPricePerDay::pluck('room_price_id')->unique()->toArray(); // các id room tròn bảng 
            foreach($id_room as $item){
                $result = RoomPricePerDay::where('room_price_id',$item)->where('date',$request->dateCurent)->first();
                if($result){
                    foreach($differentDates as $date){
                        $roomPricePerDay = new RoomPricePerDay();
                        $roomPricePerDay->room_price_id = $item;
                        $roomPricePerDay->date = $date;
                        $roomPricePerDay->hourly_price = $result->hourly_price;
                        $roomPricePerDay->daily_price = $result->daily_price;
                        $roomPricePerDay->overnight_price =  $result->overnight_price;
                        $roomPricePerDay->save();
                    }
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
    }



    public function roomPricePerDay()  {
        $data = RoomPricePerDay::all();
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
            $roomPrice = RoomPricePerDay::where('room_price_id', $data['room_id'])->where('date', $date)->first();

            if (!$roomPrice) {
                $roomPrice = new RoomPricePerDay();
                $roomPrice->room_price_id = $data['room_id'];
                $roomPrice->date = $date;
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
            $responseMessages[] = ['status' => 'success', 'message' => 'Cập nhật giá thành công'];
        }

        return response()->json(['status' => 'success', 'messages' => $responseMessages]);
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
