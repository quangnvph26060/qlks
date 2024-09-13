<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'image_path',
        'name',
        'description',
        'import_price',
        'selling_price',
        'stock',
        'sku',
        'is_published'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }


    protected $cats = [
        'is_published' => 'boolean',
    ];
}
