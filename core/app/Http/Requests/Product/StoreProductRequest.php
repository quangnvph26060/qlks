<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            // 'name' => 'required|unique:products',
            'name' => [
                'required',
                'string',
                Rule::unique('products', 'sku')
                    ->ignore($this->id)
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
            'import_price' => 'required|numeric',
            'selling_price' => 'required|numeric|gt:import_price',
            'description' => 'nullable',
            'sku' => [
                'required',
                'string',
               
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('products', 'sku')
                    ->ignore($this->id)
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
            'category_id' => 'required',
            'brand_id' => 'required',
            'stock' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.max'                               => 'Mã sản phẩm chỉ có tối đa 6 ký tự',
            'name.required'                         => 'Vui lòng nhập :attribute',
            'name.unique'                           => 'Tên sản phẩm đã tồn tại',
            'import_price.required'                 => 'Vui lòng nhập :attribute',
            'import_price.numeric'                  => 'Vui lòng nhập đúng định dạng :attribute',
            'selling_price.required'                => 'Vui lòng nhập :attribute',
            'selling_price.numeric'                 => 'Vui lòng nhập đúng định dạng :attribute',
            'selling_price.gt'                      => 'Giá bán < giá nhập',
            'sku.required'                          => 'Vui lòng nhập :attribute',
            'sku.unique'                            => 'Mã sản phẩm đã tồn tại',
            'sku.regex'                            => 'Mã sản phẩm phải ghi hoa',
            'category_id.required'                  => 'Vui lòng chọn :attribute',
            'brand_id.required'                     => 'Vui lòng chọn :attribute',

            'stock.integer'                         => 'Vui lòng nhập đúng định dạng :attribute',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                                  => 'Tên sản phẩm',
            'import_price'                          => 'Giá nhập',
            'selling_price'                         => 'Giá bán',
            'description'                           => 'Mô tả',
            'sku'                                   => 'Mã sản phẩm',
            'category_id'                           => 'Danh mục',
            'brand_id'                              => 'Thương hiệu',
            'stock'                                 => 'Số lượng',
        ];
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
}
