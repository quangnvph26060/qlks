<?php

namespace App\Console\Commands;

use App\Constants\Status;
use App\Models\Room;
use App\Models\RoomPriceRoom;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateRoomPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'room:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật giá phòng dựa trên thời gian hiện tại';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public static function updatePrice()
    {

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $rooms = Room::active()->has('roomPrices')->with('roomPrices')->get();
        foreach ($rooms as $room) {
            $activePrice = null;
            foreach ($room->roomPrices as $price) {

                // Ưu tiên 1: Kiểm tra giờ (start_time, end_time)
                if (!empty($price->pivot->start_time) && !empty($price->pivot->end_time)) {
                    if ($currentTime >= $price->pivot->start_time && $currentTime <= $price->pivot->end_time) {
                        $activePrice = $price;
                        break;
                    }
                }
            }

            // Ưu tiên 2: Nếu chưa tìm thấy giá theo giờ, kiểm tra ngày đặc biệt
            if (is_null($activePrice)) {
                foreach ($room->roomPrices as $price) {
                    if (!empty($price->pivot->specific_date) && $price->pivot->specific_date == $currentDate) {
                        $activePrice = $price;
                        break;
                    }
                }
            }

            // Ưu tiên 3: Nếu chưa có giá, kiểm tra khoảng ngày (start_date, end_date)
            if (is_null($activePrice)) {
                foreach ($room->roomPrices as $price) {
                    if ($currentDate >= $price->pivot->start_date && $currentDate <= $price->pivot->end_date) {
                        $activePrice = $price;
                        break;
                    }
                }
            }
            DB::table('room_price_rooms')->where('room_id', $room->id)->update(['status' => Status::PRICEROOM_INACTIVE]);
            // Hiển thị giá đang hoạt động
            if (!is_null($activePrice)) {

                try {
                   

                    DB::table('room_price_rooms')
                        ->where('room_id', $room->id)
                        ->where('price_id', $activePrice->id)
                        ->update(['status' => Status::PRICEROOM_ACTIVE]);
                } catch (\Exception $e) {
                    \Log::error("Error updating status: " . $e->getMessage());
                }
            } else {
                echo "Phòng ID: {$room->id} không có giá nào đang áp dụng tại thời điểm này.\n";
            }
        }
    }


    public function handle()
    {
        // $currentDate = Carbon::now()->toDateString();
        // $currentTime = Carbon::now()->toTimeString();

        // $rooms = Room::active()->with('roomPrices')->toSql();
        // \Log::info($rooms);
        // foreach ($rooms as $room) {
        //     $roomId = $room->id;

        // }

    }
}
