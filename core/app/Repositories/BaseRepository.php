<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function customPaginate(
        $columns = ['*'],
        $relations = [],
        $perPage = 10,
        $orderBy = null,
        $search = null,
        $customWhere = [],
        $searchColumns = [],
        $relationSearchColumns = [],
        $filters = [],
        $all = false,
        $direction = 'desc',
        $date = null // Thêm tham số lọc ngày
    ) {
        // Khởi tạo query với các quan hệ
        $query = $this->model->query()->with($relations);

        // Áp dụng tìm kiếm
        if ($search && !empty($searchColumns)) {
            $query = $this->applySearch($query, $search, $searchColumns, $relationSearchColumns);
        }

        // Áp dụng điều kiện tùy chỉnh
        if (!empty($customWhere)) {
            $query = $this->applyCustomWhere($query, $customWhere);
        }

        // Áp dụng các bộ lọc
        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        // Áp dụng sắp xếp
        if ($orderBy) {
            $query->orderBy($orderBy, $direction);
        }

        // Áp dụng lọc ngày
        if ($date) {
            $query = $this->applyDateFilter($query, $date);
        }

        // Phân trang hoặc lấy tất cả
        return $all ? $query->get() : $query->paginate($perPage, $columns);
    }

    private function applySearch($query, $search, $searchColumns, $relationSearchColumns)
    {
        return $query->where(function (Builder $query) use ($search, $searchColumns, $relationSearchColumns) {
            foreach ($searchColumns as $column) {
                $query->orWhere($column, 'like', "%{$search}%");
            }
            foreach ($relationSearchColumns as $relation => $columns) {
                foreach ($columns as $column) {
                    $query->orWhereHas($relation, function (Builder $query) use ($search, $column) {
                        $query->where($column, 'like', "%{$search}%");
                    });
                }
            }
        });
    }

    private function applyCustomWhere($query, $customWhere)
    {
        return $query->where(function (Builder $query) use ($customWhere) {
            foreach ($customWhere as $key => $value) {
                $query->where($key, $value);
            }
        });
    }

    private function applyFilters($query, $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value) {
                $query->where($key, $value);
            }
        }
        return $query;
    }

    private function applyDateFilter($query, $date)
    {
        $dates = explode(' - ', $date);
        $currentDate = Carbon::now();

        if (count($dates) === 2) {
            // Trường hợp người dùng nhập khoảng thời gian "startDate - endDate"
            $startDate = Carbon::parse(trim($dates[0]))->startOfDay();
            $endDate = Carbon::parse(trim($dates[1]))->endOfDay();
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $inputDate = Carbon::parse($date);

            // Trường hợp ngày nhập là trong quá khứ
            if ($inputDate->isPast()) {
                return $query->whereBetween('created_at', [$inputDate->startOfDay(), $currentDate]);
            }
            // Trường hợp ngày nhập là trong tương lai
            elseif ($inputDate->isFuture()) {
                return $query->whereBetween('created_at', [$currentDate, $inputDate->endOfDay()]);
            }
            // Trường hợp người dùng nhập đúng ngày hiện tại (hôm nay)
            else {
                return $query->whereBetween('created_at', [$inputDate->startOfDay(), $inputDate->endOfDay()]);
            }
        }
    }



    public function logError($exception)
    {
        // Ghi log lỗi
        Log::error('', [
            'message' => $exception->getMessage(),
            'line'    => $exception->getLine(),
            'code'    => $exception->getCode(),
            'function' => $exception->getFile(),
        ]);
    }


    function generateRandomString()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';

        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
