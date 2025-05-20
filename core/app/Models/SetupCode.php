<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class SetupCode extends Model
{
    use HasFactory,BelongsToTenant;

    protected $table = 'setup_code';

    protected $fillable = [
        'code',
        'menu_name',
        'created_at',
        'updated_at',
        'subdomain',

    ];
}
