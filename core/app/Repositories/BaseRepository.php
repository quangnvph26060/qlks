<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function customPaginate($columns = ['*'], $relations = [], $perPage = 10, $orderBy = null, $search = null, $searchColumns = [], $relationSearchColumns = [])
    {
        if ($relations)
            $query = $this->model->with($relations);


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

        // Sắp xếp
        if ($orderBy) {
            $query->orderBy($orderBy, 'desc');
        }

        // Phân trang
        return $query->paginate($perPage, $columns);
    }
}
