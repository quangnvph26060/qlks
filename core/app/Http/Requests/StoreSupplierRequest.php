<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSupplierRequest extends FormRequest
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
            'suppliers.supplier_id'                      => 'unique:suppliers,supplier_id|max:6',
            'suppliers.name'                             => 'required',
            'suppliers.email'                            => 'required|email|unique:suppliers',
            'suppliers.phone'                            => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'suppliers.address'                          => 'required',
            'suppliers.account_number'                   => 'required|integer',
            'suppliers.tax_code'                         => 'required|integer',
            'suppliers.bank_id'                          => 'required|integer',
            'representatives.name'                       => 'required',
            'representatives.email'                      => 'required|email',
            'representatives.phone'                      => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'representatives.position'                   => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'suppliers.supplier_id.max'                  => 'Mã nhà cung cấp không được quá 6 ký tự',
            'suppliers.supplier_id.unique'               => 'Mã nhà cung cập đã tồn tại',
            'suppliers.name.required'                    => 'Vui lòng nhập :attribute!',
            'suppliers.email.required'                   => 'Vui lòng nhập :attribute!',
            'suppliers.email.email'                      => ':attribute không đúng định dạng!',
            'suppliers.email.unique'                     => ':attribute đã tồn tại!',
            'suppliers.phone.required'                   => 'Vui lòng nhập :attribute!',
            'suppliers.phone.regex'                      => ':attribute không đúng định dạng!',
            'suppliers.phone.max'                        => ':attribute không đúng định dạng!',
            'suppliers.phone.min'                        => ':attribute không đúng định dạng!',
            'suppliers.address.required'                 => 'Vui lòng nhập :attribute!',
            'suppliers.account_number.required'          => 'Vui lòng nhập :attribute!',
            'suppliers.account_number.integer'           => ':attribute không đúng định dạng!',
            'suppliers.tax_code.required'                => 'Vui lòng nhập :attribute!',
            'suppliers.tax_code.integer'                 => ':attribute không đúng định dạng!',
            'suppliers.bank_id.required'                 => 'Vui lòng nhập :attribute!',
            'suppliers.bank_id.integer'                  => ':attribute không đúng định dạng!',
            'representatives.name.required'              => 'Vui lòng nhập :attribute!',
            'representatives.email.required'             => 'Vui lòng nhập :attribute!',
            'representatives.email.email'                => ':attribute không đúng định dạng!',
            'representatives.phone.regex'                => ':attribute không đúng định dạng!',
            'representatives.phone.required'             => 'Vui lòng nhập :attribute!',
            'representatives.phone.max'                  => ':attribute không đúng định dạng!',
            'representatives.phone.min'                  => ':attribute không đúng định dạng!',
            'representatives.position.string'            => ':attribute không đúng định dạng!',
        ];
    }

    public function attributes(): array
    {
        return [
            'suppliers.name'                             => 'tên nhà cung cấp',
            'suppliers.email'                            => 'Email',
            'suppliers.phone'                            => 'số điện thoại',
            'suppliers.address'                          => 'địa chỉ',
            'suppliers.account_number'                   => 'số tài khoản',
            'suppliers.tax_code'                         => 'mã số thuế',
            'suppliers.bank_id'                          => 'tên ngân hàng',
            'representatives.name'                       => 'tên người đại diện',
            'representatives.email'                      => 'Email',
            'representatives.phone'                      => 'số điện thoại',
            'representatives.position'                   => 'chức vụ',

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
