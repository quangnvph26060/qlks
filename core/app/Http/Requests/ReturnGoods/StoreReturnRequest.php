<?php

namespace App\Http\Requests\ReturnGoods;

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

    public function messages(): array{

        return [
            'products.*.quantity.required' => 'Vui lòng nhập số lượng',
            'products.*.quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1',
            'products.*.reason.required' => 'Vui lòng nhập lý do',
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
