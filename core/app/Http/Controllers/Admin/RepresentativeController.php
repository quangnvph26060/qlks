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
                $request->all(),
                [
                    'name'              => 'required',
                    'email'             => 'required|email',
                    'phone'             => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                    'position'          => 'nullable',
                ],
                [
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
                    '   errors'         => $data->errors()
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
