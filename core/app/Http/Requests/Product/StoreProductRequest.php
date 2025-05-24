<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép tất cả user gọi request này
    }

    protected function prepareForValidation()
    {
        $unitCode = unitCode();
        $subdomain = subdomain();

        // Gộp thêm unit_code, subdomain vào input để dùng trong rule unique
        $this->merge([
            'unit_code' => $unitCode,
            'subdomain' => $subdomain,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('products', 'name')
                    ->where(fn ($query) => $query
                        ->where('unit_code', $this->input('unit_code'))
                        ->where('subdomain', $this->input('subdomain'))
                    ),
            ],
            'import_price' => 'required|numeric',
            'selling_price' => 'required|numeric|gt:import_price',
            'description' => 'nullable',
            'sku' => [
                'required',
                'string',
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('products', 'sku')
                    ->where(fn ($query) => $query
                        ->where('unit_code', $this->input('unit_code'))
                        ->where('subdomain', $this->input('subdomain'))
                    ),
            ],
            'category_id' => 'required',
            'brand_id' => 'required',
            'stock' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
            'sku.required' => 'SKU không được để trống.',
            'sku.regex' => 'SKU chỉ được chứa chữ in hoa, số và dấu gạch ngang.',
            'sku.unique' => 'SKU đã tồn tại',
            'import_price.required' => 'Giá nhập không được để trống.',
            'selling_price.required' => 'Giá bán không được để trống.',
            'selling_price.gt' => 'Giá bán phải lớn hơn giá nhập.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Tên sản phẩm',
            'import_price' => 'Giá nhập',
            'selling_price' => 'Giá bán',
            'description' => 'Mô tả',
            'sku' => 'Mã sản phẩm',
            'category_id' => 'Danh mục',
            'brand_id' => 'Thương hiệu',
            'stock' => 'Số lượng',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]));
    }
}
