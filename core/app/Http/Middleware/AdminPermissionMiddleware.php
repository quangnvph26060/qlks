<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminPermissionMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $codes = optional(auth('admin')->user()->role)->permissions->pluck('code')->toArray();
        $currentRoute = $request->route()->getName();
        $excludedRoutes = ['admin.revenue',
            'admin.hotel.setup.amenities.search',
            'admin.hotel.import.room.store',
            'admin.hotel.room.type.statusAll',
            'admin.booking.payment.print.invoice',
            'admin.hotel.room.type.changeDirections',
            'admin.warehouse.show',
            'admin.warehouse.create',
            'admin.product.index', //1
            'admin.product.filter',           // tìm kiếm sản phẩm
            'admin.warehouse.store',          // thêm phiếu nhập kho
            'admin.supplier.create',          // trang thêm nhà cung cấp
            'admin.supplier.store',           // thêm mới nhà cung cấp
            'admin.supplier.edit',            // sửa nhà cung cấp
            'admin.representative.edit',
            'admin.representative.update',    // sửa thông tin người đại diện
            'admin.supplier.destroy',         // xoá nhà cung cấp
            'admin.warehouse.update',         // xác nhận trạng thái phiếu nhập hàng
            'admin.return.create',            // trả hàng
            'admin.return.store', // xác nhận hàng hỏng
            'admin.return.index', // trang trả hàng
            'admin.return.show', // phiếu trả hàng
            'admin.warehouse.warehouse', // trang danh mục kho
            'admin.warehouse.add.warehouse', // thêm kho mới
            'admin.warehouse.edit.warehouse', // edit kho
            'admin.warehouse.update.warehouse', // update kho
            'admin.warehouse.delete.warehouse', // xoá kho
            'admin.warehouse.destroy', // xoá phiếu nhập
            'admin.warehouse.destroy.warehouse.item', // xoá sản phẩm trong chi tiết phiếu nhập khi chưa xác nhận
            'admin.warehouse.update.import.slipe', // cập nhật thông tin phiếu nhập, cập nhật 3 select nhà cung cấp, nhân viện tạo, phương thức thanh toán
            'admin.warehouse.export.index', // trang xuất kho 
            'admin.warehouse.export.store', // thêm phiếu xuất kho
            'admin.warehouse.export.destroy', // xoá xuất phiếu kho
            'admin.warehouse.export.show', // chi tiết xuất kho
            'admin.warehouse.export.update', //  xác nhận trạng thái phiếu xuất hàng
            'admin.warehouse.export.destroy.warehouse.item',  // xoá sản phẩm trong chi tiết phiếu xuất khi chưa xác nhận
            'admin.warehouse.transfer.index', // trang điều chuyển
            'admin.warehouse.transfer.store', // thêm điều chuyển
            'admin.warehouse.transfer.show', // chi tiết điều chuyển
            'admin.warehouse.transfer.destroy',// xoá phiếu điều chuyển
            'admin.warehouse.transfer.update',// xác nhận trạng thái phiếu điểu chuyển
            'admin.inventory.index', // trang tồn kho
            'admin.inventory.get', // api tồn kho 
            'admin.warehouse.export.print', // in phiếu xuất 
            'admin.warehouse.export.update.import.slipe', // chỉnh sửa phiếu xuất
            'admin.warehouse.print',// in phiếu nhập 
            'admin.warehouse.transfer.update.import.slipe', // chỉnh sửa phiếu điều chuyển  
            'admin.warehouse.transfer.check-stock', // check trong kho đó sản phẩm còn bao nhiêu
            'admin.warehouse.transfer.print', // in phiếu điều chuyển
            'admin.hotel.room.product.admin.warehouse.get-products-by-warehouse',
            'admin.warehouse.import.store', // import phiếu nhập
            'admin.warehouse.get.logs', // chi tiết phiếu nhập ai là ngươiuf tạo, cập nhật
            'admin.warehouse.export.get.logs',// chi tiết phiếu  xuất ai là ngươiuf tạo, cập nhật
            'admin.warehouse.transfer.get.logs', // chi tiết phiếu  điều chuyển ai là ngươiuf tạo, cập nhật
            'admin.supplier.update',
            'admin.hotel.customer.search', // tìm kiếm danh mục khách hàng 
            'admin.hotel.customer.source.search', // tìm kiếm danh mục nguồn khách
            'admin.hotel.customer.check',
            'admin.hotel.room.amenities.search',
            'admin.hotel.room.product.search',
            'admin.booking.sumPriceRoom',
            // 'admin.booking.room-booking-history', //Thông tin lễ tân
            // 'admin.hotel.premium.service.get-all-service', //Trang sản phẩm & dịch vụ

        ];
        /*
            'admin.revenue' => 'Danh thu trong màn thông kế'
            'admin.hotel.setup.amenities.search' => 'Tìm kiếm cài đặt tiện nghi'
            'admin.hotel.import.room.store' => import room
            'admin.hotel.room.type.statusAll' => update nhiều trạng thái phòng 
            'admin.booking.payment.print.invoice'=> in hoá đơn bán hàng ở lễ tân
            'admin.hotel.room.type.changeDirections'=> chuyển hướng phòng
            'admin.warehouse.show' => chi tiết nhập kho
            'admin.warehouse.create'=> tạo nhập kho
        
        */
        if (!in_array($currentRoute, $excludedRoutes) && !in_array($currentRoute, $codes)) {
             Log::warning('Access denied for route: ' . $currentRoute);
            if (url()->previous() == url()->current()) {
                abort(403, 'Bạn không có quyền truy cập vào trang này .');
            }
           abort(403, 'Bạn không có quyền truy cập vào trang này.');
        }


        return $next($request);
        // if (!Role::hasPermission()) {
        //     return redirect()->route('admin.profile');
        // }

        // return $next($request);
    }
}
