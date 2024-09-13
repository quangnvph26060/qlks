<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierRepresentative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RepresentativeController extends Controller
{
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $data = Validator::make(
                data: $request->all(),
                rules: [
                    'name'              => 'required',
                    'email'             => 'required|email|unique:supplier',
                    'phone'             => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                    'position'          => 'nullable',
                ],
                messages: [
                    'name.required'     => 'Vui lòng nhập tên người đại diện!',
                    'email.required'    => 'Vui lòng nhập Email!',
                    'email.email'       => 'Email nhân viên phải đúng định dạng!',
                    'phone.required'    => 'Vui lòng nhập số điện thoại',
                    'phone.regex'       => 'Số điện thoại không đúng định dạng!',
                ]
            );

            if ($data->passes()) {
                try {
                    $supplier           = Supplier::query()->findOrFail($request->supplier_id);
                    $representative     = $supplier->supplier_representatives()->create($data->validated());
                    return response()->json([
                        'status'        => true,
                        'data'          => ["name" => $representative->name, "id" => $representative->id],
                        'message'       => 'Thêm mới thành công.'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status'        => false,
                        'message'       => 'Không tìm thấy nhà cung cấp!'
                    ]);
                }
            } else {
                return response()->json([
                    'status'        => false,
                    'errors'         => $data->errors()
                ]);
            }
        }
    }

    public function edit(string $id)
    {

        $representative = SupplierRepresentative::query()->find($id);

        if ($representative) {
            return response()->json(data: [
                'status'            => true,
                'data'              => $representative
            ]);
        }

        return response()->json(data: [
            'status'            => false,
            'message'           => 'Không tìm thấy dữ liệu!'
        ]);
    }

    public function update(Request $request, string $id)
    {

        $representative = SupplierRepresentative::query()->find($id);
        if ($representative) {

            $data = Validator::make(
                $request->all(),
                [
                    'name'              => 'required',
                    'email'             => 'required|email|unique:supplier_representatives,email,' . $id,
                    'phone'             => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                    'position'          => 'nullable',
                ],
                [
                    'name.required'     => 'Vui lòng nhập tên người đại diện!',
                    'email.required'    => 'Vui lòng nhập Email!',
                    'email.email'       => 'Email nhân viên phải đúng định dạng!',
                    'email.unique'      => 'Email đã tồn tại!',
                    'phone.required'    => 'Vui lòng nhập số điện thoại',
                    'phone.regex'       => 'Số điện thoại không đúng định dạng!',
                ]
            );
            if ($data->passes()) {

                $representative->update(attributes: $data->validated());

                return response()->json([
                    'status'            => true,
                    'message'           => 'Cập nhật thành công!',
                    'data'              => $representative
                ]);
            } else {
                return response()->json([
                    'status'            => false,
                    'errors'           => $data->errors()
                ]);
            }
        }
    }

    public function destroy(string $id)
    {

        $representative = SupplierRepresentative::query()->find($id);

        if ($representative) {
            $representative->delete();
            return response()->json([
                'status'            => true,
                'message'           => 'Xóa thành công!'
            ]);
        }

        return response()->json([
            'status'            => false,
            'message'           => 'Không tìm thấy nhà cung cấp!'
        ]);
    }
}
