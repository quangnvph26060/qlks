<?php
namespace App\Traits;

use App\Models\RoomTypePrice;
use Illuminate\Support\Carbon;
use App\Models\SetupPricing;

trait HasTodayPrice
{
   protected function getPricesBySetupPricing($date)
    {
        $carbonWeekday = Carbon::parse($date)->dayOfWeek;
        $customWeekday = $carbonWeekday === 0 ? 8 : $carbonWeekday + 1;

        $setupPricings = SetupPricing::withoutTenant()
            ->where('subdomain', subdomain())
              ->where('unit_code', unitCode())
            ->get()
            ->filter(function ($config) use ($customWeekday, $date) {
                $requirements = json_decode($config->price_requirement, true);
                if (!is_array($requirements)) return false;

                $normalized = collect($requirements)
                    ->flatMap(fn($item) => explode(',', $item))
                    ->map(fn($item) => trim($item))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                return in_array($date, $normalized) || in_array((string)$customWeekday, $normalized);
            })
            ->values();

        $setupPricingIdsForToday = $setupPricings->filter(function ($config) use ($date) {
            $requirements = json_decode($config->price_requirement, true);
            return in_array($date, $requirements);
        })->pluck('id')->all();

        // Nếu không có ngày chính xác thì lấy theo thứ hiện tại
        if (empty($setupPricingIdsForToday)) {
            $setupPricingIdsForToday = $setupPricings->filter(function ($config) use ($customWeekday) {
                $requirements = json_decode($config->price_requirement, true);
                return in_array((string)$customWeekday, $requirements);
            })->pluck('id')->all();
        }

        $roomTypePrices = RoomTypePrice::whereIn('setup_pricing_id', $setupPricings->pluck('id')->all())
            ->get();

        $pricesByRoomTypeId = $roomTypePrices->groupBy('room_type_id')->map(function ($prices) use ($setupPricingIdsForToday) {
            $priceForToday = $prices->first(fn($price) => in_array($price->setup_pricing_id, $setupPricingIdsForToday));
            return $priceForToday ?: $prices->first();
        });

        return $pricesByRoomTypeId->toArray();
    }
    protected function getPricesBySetupPricingForMultipleDates(array $dates)
{
    $subdomain = subdomain();
    $unitCode = unitCode();

    // Lấy toàn bộ setup pricing 1 lần
    $allSetupPricings = SetupPricing::withoutTenant()
        ->where('subdomain', $subdomain)
        ->where('unit_code', $unitCode)
        ->get();

    // Lấy toàn bộ RoomTypePrice 1 lần, group theo room_type_id
    $allRoomTypePrices = RoomTypePrice::whereIn('setup_pricing_id', $allSetupPricings->pluck('id')->all())
        ->get()
        ->groupBy('room_type_id');

    $result = [];

    foreach ($dates as $date) {
        $carbonWeekday = Carbon::parse($date)->dayOfWeek;
        $customWeekday = $carbonWeekday === 0 ? 8 : $carbonWeekday + 1;

        // Lọc setup pricing hợp lệ với ngày hoặc thứ
        $setupPricings = $allSetupPricings->filter(function ($config) use ($customWeekday, $date) {
            $requirements = json_decode($config->price_requirement, true);
            if (!is_array($requirements)) return false;

            $normalized = collect($requirements)
                ->flatMap(fn($item) => explode(',', $item))
                ->map(fn($item) => trim($item))
                ->filter()
                ->unique()
                ->values()
                ->all();

            return in_array($date, $normalized) || in_array((string)$customWeekday, $normalized);
        })->values();

        // Ưu tiên theo ngày chính xác
        $setupPricingIdsForToday = $setupPricings->filter(function ($config) use ($date) {
            $requirements = json_decode($config->price_requirement, true);
            return in_array($date, $requirements);
        })->pluck('id')->all();

        // Nếu không có theo ngày thì lấy theo thứ
        if (empty($setupPricingIdsForToday)) {
            $setupPricingIdsForToday = $setupPricings->filter(function ($config) use ($customWeekday) {
                $requirements = json_decode($config->price_requirement, true);
                return in_array((string)$customWeekday, $requirements);
            })->pluck('id')->all();
        }

        // Lấy giá theo room_type_id tương ứng
        $pricesByRoomTypeId = [];

        foreach ($allRoomTypePrices as $roomTypeId => $prices) {
            $priceForToday = $prices->first(fn($price) => in_array($price->setup_pricing_id, $setupPricingIdsForToday));
            $pricesByRoomTypeId[$roomTypeId] = $priceForToday ?: $prices->first();
        }

        $result[$date] = $pricesByRoomTypeId;
    }

    return $result;
}

}
