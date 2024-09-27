<?php

namespace App\Console\Commands;

use App\Models\Room;
use Illuminate\Console\Command;
use Carbon\Carbon;

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
    public static function updatePrice() {
       
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $rooms = Room::active()->has('roomPrices')->with('roomPrices')->get();
        foreach ($rooms as $room) {
            $activePrice = null;
            \Log::info($room);
            foreach ($room->roomPrices as $price) {
               
                // Ưu tiên 1: Kiểm tra giờ (start_time, end_time)
                if (!empty($price->start_time) && !empty($price->end_time)) {
                
                    if ($currentTime >= $price->start_time && $currentTime <= $price->end_time) {
                     //   \Log::info("start_time : ".$price->start_time);     \Log::info("end_time : ".$price->end_time);
                     
                        $activePrice = $price;
                        break;
                    }
                }
            }
    
            // Ưu tiên 2: Nếu chưa tìm thấy giá theo giờ, kiểm tra ngày đặc biệt
            if (is_null($activePrice)) {
                foreach ($room->roomPrices as $price) {
                    if (!empty($price->specific_date) && $price->specific_date == $currentDate) {
                        $activePrice = $price;
                        break; // Ngưng vòng lặp vì ưu tiên ngày đặc biệt
                    }
                }
            }
    
            // Ưu tiên 3: Nếu chưa có giá, kiểm tra khoảng ngày (start_date, end_date)
            if (is_null($activePrice)) {
                foreach ($room->roomPrices as $price) {
                    if ($currentDate >= $price->start_date && $currentDate <= $price->end_date) {
                        $activePrice = $price;
                        break; // Ngưng vòng lặp vì tìm thấy giá theo khoảng ngày
                    }
                }
            }
    
            // Hiển thị giá đang hoạt động
            if (!is_null($activePrice)) {
                echo "Phòng ID: {$room->id} đang áp dụng giá: {$activePrice->name}, Mức giá: {$activePrice->price} VND\n";
            } else {
                echo "Phòng ID: {$room->id} không có giá nào đang áp dụng tại thời điểm này.\n";
            }
        }
    }

    
    
    public function handle()
    {
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $rooms = Room::active()->with('roomPrices')->toSql();
        \Log::info($rooms);
        foreach ($rooms as $room) {
            $roomId = $room->id;
          
        }
      
    }
}
