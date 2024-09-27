<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'supplier_id'                      => 'max:6|unique:suppliers,supplier_id,' . $this->id,
            'name'                             => 'required',
            'email'                            => 'required|email|unique:suppliers',
            'phone'                            => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'address'                          => 'required',
            'account_number'                   => 'required|integer',
            'tax_code'                         => 'required|integer',
            'bank_id'                          => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.max'                  => 'Mã nhà cung cấp không được quá 6 ký tự',
            'supplier_id.unique'               => 'Mã nhà cung cập đã tồn tại',
            'name.required'                    => 'Vui lòng nhập :attribute!',
            'email.required'                   => 'Vui lòng nhập :attribute!',
            'email.email'                      => ':attribute không đúng định dạng!',
            'email.unique'                     => ':attribute đã tồn tại!',
            'phone.required'                   => 'Vui lòng nhập :attribute!',
            'phone.regex'                      => ':attribute không đúng định dạng!',
            'phone.max'                        => ':attribute không đúng định dạng!',
            'phone.min'                        => ':attribute không đúng định dạng!',
            'address.required'                 => 'Vui lòng nhập :attribute!',
            'account_number.required'          => 'Vui lòng nhập :attribute!',
            'account_number.integer'           => ':attribute không đúng định dạng!',
            'tax_code.required'                => 'Vui lòng nhập :attribute!',
            'tax_code.integer'                 => ':attribute không đúng định dạng!',
            'bank_id.required'                 => 'Vui lòng nhập :attribute!',
            'bank_id.integer'                  => ':attribute không đúng định dạng!',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                             => 'tên nhà cung cấp',
            'email'                            => 'Email',
            'phone'                            => 'số điện thoại',
            'address'                          => 'địa chỉ',
            'account_number'                   => 'số tài khoản',
            'tax_code'                         => 'mã số thuế',
            'bank_id'                          => 'tên ngân hàng',
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
