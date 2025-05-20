<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            // Kiểm tra tránh lỗi nếu hàm chưa có context
            if (function_exists('unitCode') && function_exists('subdomain')) {
                $unitCode = unitCode();
                $subdomain = subdomain();

                if ($unitCode && $subdomain) {
                    // 👇 Dùng alias bảng rõ ràng
                    $table = $builder->getModel()->getTable();

                    $builder->where("{$table}.unit_code", $unitCode)
                        ->where("{$table}.subdomain", $subdomain);
                } else {
                    logger()->warning('BelongsToTenant scope không áp dụng được - thiếu unitCode hoặc subdomain');
                }
            }
        });
    }

    /**
     * Bỏ global scope để lấy tất cả dữ liệu (bỏ lọc tenant)
     */
    public static function withoutTenant()
    {
        return (new static)->newQueryWithoutScope('tenant');
    }

    /**
     * Truy vấn dữ liệu theo tenant hiện tại
     */
    public static function tenant()
    {
        return static::query(); // đã có scope, nên sẽ auto where rồi
    }
}
