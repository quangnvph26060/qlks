<?php

namespace App\Console\Commands;

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
    public function handle()
    {
        \Log::info('demo command');
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();
    }
}
