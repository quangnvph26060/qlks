<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $cats = [
        'is_active' => "boolean",
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
