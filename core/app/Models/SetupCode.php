<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SetupCode extends Model
{
    use HasFactory;

    protected $table = 'setup_code';

    protected $fillable = [
        'code',
        'menu_name',
        'created_at',
        'updated_at',

    ];
}
