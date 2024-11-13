<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegularRoomPrice;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function __construct() {}

    public function switchPrice(Request $request)
    {
        $chose = $request['method'];
        $data = [
            'price'   => $request['price'],
            'room_id' => $request['room_id'],
        ];
        $response = null;
        switch ($chose) {
            case  'memthod_hour':
                //   $this->priceHour($data['data']);
                break;
            case  'demo':
                $this->priceHour();
                break;
            case  'method_fullday':
                $response =   $this->priceFullDay($data);
                break;
            case  'method_overnightPrice':
                $response =  $this->priceOverNightPrice($data);
                break;
            default:
            return response()->json(['status' => 'error', 'message' => 'Method not found']);
        }
        return $response;
    }
    public function priceOverNightPrice($data){
        $roomPrice = RegularRoomPrice::where('room_price_id', $data['room_id'])->first();

        if ($roomPrice) {

            $roomPrice->overnight_price = $data['price'];
            $roomPrice->save();
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
        } else {

            $newRoomPrice = new RegularRoomPrice();
            $newRoomPrice->room_price_id = $data['room_id'];
            $newRoomPrice->overnight_price = $data['price'];
            $newRoomPrice->save();
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
        }
    }
    public function priceFullDay($data)
    {
        $roomPrice = RegularRoomPrice::where('room_price_id', $data['room_id'])->first();

        if ($roomPrice) {

            $roomPrice->daily_price = $data['price'];
            $roomPrice->save();
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
        } else {

            $newRoomPrice = new RegularRoomPrice();
            $newRoomPrice->room_price_id = $data['room_id'];
            $newRoomPrice->daily_price = $data['price'];
            $newRoomPrice->save();
            return response()->json(['status' => 'success', 'message' => 'Cập nhật giá thành công']);
        }
    }
    public function priceHour()
    {
        $regularPrice = new RegularRoomPrice();
    }
}
