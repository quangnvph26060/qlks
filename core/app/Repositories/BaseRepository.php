<?php

namespace App\Repositories;

use Illuminate\Http\Request;
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

    public function customPaginate($columns = ['*'], $relations = [], $perPage = 10, $orderBy = null, $search = null, $customWhere = [], $searchColumns = [], $relationSearchColumns = [], $filters = [], $all = false)
    {
        // dd($relationSearchColumns);
        $query = $this->model->query()->with($relations);

        // Tìm kiếm trong các cột chính
        if ($search && !empty($searchColumns)) {
            $query->where(function (Builder $query) use ($search, $searchColumns, $relationSearchColumns) {
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

        // Tìm kiếm trong các cấu trúc tìm kiếm
        if (!empty($customWhere)) {
            $query->where(function (Builder $query) use ($customWhere) {
                foreach ($customWhere as $key => $value) {
                    $query->where($key, $value);
                }
            });
        }

        // Áp dụng các điều kiện lọc từ mảng filters
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if ($value) { // Kiểm tra xem giá trị có hợp lệ không
                    $query->where($key, $value);
                }
            }
        }

        // Sắp xếp
        if ($orderBy) {
            $query->orderBy($orderBy, 'desc');
        }

        // Phân trang
        return $all ? $query->get() : $query->paginate($perPage, $columns);
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
