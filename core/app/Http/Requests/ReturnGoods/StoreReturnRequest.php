<?php

namespace App\Http\Requests\ReturnGoods;

use App\Models\WarehouseEntry;
use App\Models\WarehouseEntryItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.reason' => 'required',
        ];
    }

    public function messages(): array
    {

        return [
            'products.*.quantity.required' => 'Vui lòng nhập số lượng',
            'products.*.quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1',
            'products.*.reason.required' => 'Vui lòng nhập lý do',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $orderProducts = $this->getOrderProducts();

            foreach ($this->input('products') as $productId => $product) {
                $returnQuantity = $product['quantity'];
                $purchasedQuantity = $orderProducts[$productId] ?? 0;

                if ($returnQuantity > $purchasedQuantity) {
                    $validator->errors()->add("products.{$productId}.quantity", "Số lượng trả hàng không được vượt quá số lượng đã mua.");
                }
            }
        });
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ])
        );
    }

    protected function getOrderProducts()
    {

    $warehouseEntry = WarehouseEntry::find( $this->id);

    $warehouseEntryProducts = [];

    foreach ($warehouseEntry->entries as $entry) {
        $warehouseEntryProducts[$entry->product_id] = $entry->quantity;
    }

    return $warehouseEntryProducts;
    }
}
