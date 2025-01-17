<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusCode extends Model
{
    use HasFactory;

    protected $table = 'status_codes';

    protected $fillable = [
        'status_code',
        'status_name',
        'note',
        'status_status',
        'created_at',
        'updated_at',

    ];
    public function styleStatus(){
        return $this->status_status == 1 ? '<span class="badge badge--success">Hoạt động</span>' : '<span class="badge badge--danger">Không hoạt động</span>';
    }
}
