<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required|unique:products,name,' . $this->id,
            'import_price' => 'required|numeric',
            'selling_price' => 'required|numeric|gt:import_price',
            'description' => 'nullable',
            'sku' => 'required|unique:products,sku,' . $this->id,
            'category_id' => 'required',
            'brand_id' => 'required',
            'stock' => 'nullable|integer',
            'image_path' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                         => 'Vui lòng nhập :attribute',
            'name.unique'                           => 'Tên sản phẩm đã tồn tại',
            'import_price.required'                 => 'Vui lòng nhập :attribute',
            'import_price.numeric'                  => 'Vui lòng nhập đúng định dạng :attribute',
            'selling_price.required'                => 'Vui lòng nhập :attribute',
            'selling_price.numeric'                 => 'Vui lòng nhập đúng định dạng :attribute',
            'selling_price.gt'                      => 'Giá bán < giá nhập',
            'sku.required'                          => 'Vui lòng nhập :attribute',
            'sku.unique'                            => 'Mã sản phẩm đã tồn tại',
            'category_id.required'                  => 'Vui lòng chọn :attribute',
            'brand_id.required'                     => 'Vui lòng chọn :attribute',
            'image_path.mimes'                      => 'Vui lòng chọn hình ảnh định dạng png,jpg,jpeg',
            'image_path.max'                        => 'Vui lòng chọn hình ảnh nhỏ hơn 2 MB',
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
            'image_path'                            => 'Hình ảnh',
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
