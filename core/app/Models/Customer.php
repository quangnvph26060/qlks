<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';

    protected $fillable = [
        'customer_code',
        'name',
        'phone',
        'address',
        'email',
        'note',
        'group_code',
        'unit_code',
        'status',
        'created_at',
        'updated_at',
    ];

    public function styleStatus(){
        return $this->status == 1 ? '<span class="badge badge--success">Hoạt động</span>' : '<span class="badge badge--danger">Không hoạt động</span>';
    }
}
