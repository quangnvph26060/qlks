<?php

namespace App\Traits;

use App\Models\RoomTypePrice;
use Illuminate\Support\Carbon;
use App\Models\SetupPricing;

trait HasTodayPrice
{
    protected function getPricesBySetupPricingApi($date)
    {
        $carbonWeekday = Carbon::parse($date)->dayOfWeek;
        $customWeekday = $carbonWeekday === 0 ? 8 : $carbonWeekday + 1;

        $setupPricings = SetupPricing::withoutTenant()

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

        $today = \Carbon\Carbon::parse($date)->toDateString();

        // $pricesByRoomTypeId = $roomTypePrices->groupBy('room_type_id')->map(function ($prices) use ($setupPricingIdsForToday) {
        //     $priceForToday = $prices->first(fn($price) => in_array($price->setup_pricing_id, $setupPricingIdsForToday));
        //     return $priceForToday ?: $prices->first();
        // });
        $pricesByRoomTypeId = $roomTypePrices
            ->groupBy('room_type_id')
            ->map(function ($prices) use ($today) {
                // Lọc ra tất cả record có ngày hiệu lực <= hôm nay
                $pastOrToday = $prices->filter(fn($p) => $p->price_validity_period <= $today);

                if ($pastOrToday->isNotEmpty()) {
                    // Lấy bản ghi gần nhất nhưng không vượt quá hôm nay
                    return $pastOrToday->sortByDesc('price_validity_period')->first();
                }

                // Nếu không có bản ghi nào <= hôm nay thì lấy bản ghi cũ nhất (trường hợp dữ liệu toàn là tương lai)
                return $prices->sortBy('price_validity_period')->first();
            });
        return $pricesByRoomTypeId->toArray();
    }
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
        $today = \Carbon\Carbon::parse($date)->toDateString();

        // $pricesByRoomTypeId = $roomTypePrices->groupBy('room_type_id')->map(function ($prices) use ($setupPricingIdsForToday) {
        //     $priceForToday = $prices->first(fn($price) => in_array($price->setup_pricing_id, $setupPricingIdsForToday));
        //     return $priceForToday ?: $prices->first();
        // });
        $pricesByRoomTypeId = $roomTypePrices
            ->groupBy('room_type_id')
            ->map(function ($prices) use ($today) {
                // Lọc ra tất cả record có ngày hiệu lực <= hôm nay
                $pastOrToday = $prices->filter(fn($p) => $p->price_validity_period <= $today);

                if ($pastOrToday->isNotEmpty()) {
                    // Lấy bản ghi gần nhất nhưng không vượt quá hôm nay
                    return $pastOrToday->sortByDesc('price_validity_period')->first();
                }

                // Nếu không có bản ghi nào <= hôm nay thì lấy bản ghi cũ nhất (trường hợp dữ liệu toàn là tương lai)
                return $prices->sortBy('price_validity_period')->first();
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
            // $pricesByRoomTypeId = [];

            // $today = \Carbon\Carbon::parse($date)->toDateString();

            // foreach ($allRoomTypePrices as $roomTypeId => $prices) {
            //     // Lọc ra tất cả record có ngày hiệu lực <= hôm nay
            //     $pastOrToday = $prices->filter(fn($p) => $p->price_validity_period <= $today);

            //     if ($pastOrToday->isNotEmpty()) {
            //         // Lấy bản ghi gần nhất nhưng không vượt quá hôm nay
            //         $pricesByRoomTypeId[$roomTypeId] = $pastOrToday->sortByDesc('price_validity_period')->first();
            //     } else {
            //         // Nếu không có bản ghi nào <= hôm nay thì lấy bản ghi cũ nhất (tương lai xa nhất về trước)
            //         $pricesByRoomTypeId[$roomTypeId] = $prices->sortBy('price_validity_period')->first();
            //     }
            // }

            // $result[$date] = $pricesByRoomTypeId;
        }

        return $result;
    }
    public function getDates($startDate, $endDate)
    {
        $dates = [];
        $currentDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->startOfDay();

        // Luôn thêm ngày check_in vào mảng, dù chỉ có 1 ngày
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }

        return $dates;
    }
    protected function getPricesBySetupPricingForMultipleDatesApi(array $dates)
    {
        // $subdomain = subdomain();
        // $unitCode = unitCode();

        // Lấy toàn bộ setup pricing 1 lần
        $allSetupPricings = SetupPricing::withoutTenant()
            // ->where('subdomain', $subdomain)
            // ->where('unit_code', $unitCode)
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
            // $pricesByRoomTypeId = [];

            // $today = \Carbon\Carbon::parse($date)->toDateString();

            // foreach ($allRoomTypePrices as $roomTypeId => $prices) {
            //     // Lọc ra tất cả record có ngày hiệu lực <= hôm nay
            //     $pastOrToday = $prices->filter(fn($p) => $p->price_validity_period <= $today);

            //     if ($pastOrToday->isNotEmpty()) {
            //         // Lấy bản ghi gần nhất nhưng không vượt quá hôm nay
            //         $pricesByRoomTypeId[$roomTypeId] = $pastOrToday->sortByDesc('price_validity_period')->first();
            //     } else {
            //         // Nếu không có bản ghi nào <= hôm nay thì lấy bản ghi cũ nhất (tương lai xa nhất về trước)
            //         $pricesByRoomTypeId[$roomTypeId] = $prices->sortBy('price_validity_period')->first();
            //     }
            // }

            // $result[$date] = $pricesByRoomTypeId;
        }

        return $result;
    }
}
