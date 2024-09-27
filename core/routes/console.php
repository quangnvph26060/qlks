<?php

use App\Console\Commands\UpdateRoomPrices;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('room:update-prices', function () {
    $this->comment(UpdateRoomPrices::updatePrice());
})->purpose('Cập nhật giá phòng dựa trên thời gian hiện tại')->everyMinute();
