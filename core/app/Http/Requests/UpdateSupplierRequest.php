<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
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
            'supplier_id' => ['required', 'string', Rule::unique('suppliers', 'supplier_id')->ignore($this->id)],
            'name'        => ['required', 'string'],
            'address'     => ['nullable', 'string'],
            'email'       => ['nullable', 'email', Rule::unique('suppliers', 'email')->ignore($this->id)],
            'phone'       => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'digits:10'],
            'tax_code'    => ['nullable', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Vui lòng nhập :attribute!',
            'supplier_id.max'      => ':attribute không được vượt quá 6 ký tự!',
            'supplier_id.unique'   => ':attribute đã tồn tại!',

            'name.required'        => 'Vui lòng nhập :attribute!',

            'email.email'          => ':attribute không đúng định dạng!',
            'email.unique'         => ':attribute đã tồn tại!',

            'phone.regex'          => ':attribute không đúng định dạng!',
            'phone.digits'         => ':attribute phải có đúng 10 chữ số!',
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id' => 'mã nhà cung cấp',
            'name'        => 'tên nhà cung cấp',
            'address'     => 'địa chỉ',
            'email'       => 'email',
            'phone'       => 'số điện thoại',
            'tax_code'    => 'mã số thuế',
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
