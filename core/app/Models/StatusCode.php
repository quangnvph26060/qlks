<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusCode extends Model
{
    use HasFactory;

    protected $table = 'status_codes';

    protected $fillable = [
        'group_code',
        'group_name',
        'note',
        'created_at',
        'updated_at',

    ];
    public function styleStatus(){
        return $this->status_status == 1 ? '<span class="badge badge--success">Hoạt động</span>' : '<span class="badge badge--danger">Không hoạt động</span>';
    }
}
