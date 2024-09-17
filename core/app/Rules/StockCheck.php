<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class StockCheck implements Rule
{
    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function passes($attribute, $value)
    {
        // Lấy sản phẩm từ database
        $product = Product::find($this->productId);

        // Nếu sản phẩm tồn tại và số lượng người dùng nhập <= tồn kho
        return $product && $value <= $product->stock;
    }

    public function message()
    {
        return 'Số lượng sản phẩm trong kho không đủ.';
    }
}
