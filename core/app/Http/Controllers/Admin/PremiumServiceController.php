<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumService;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class PremiumServiceController extends Controller
{
    public function index(Request $request)
    {
        $input         = $request->name;
        $pageTitle     = 'Dịch vụ cao cấp';
        // PremiumService::latest()->paginate(getPaginate());
        $premiumServices = PremiumService::query();
        if (!empty($input)) {
            $premiumServices->where('name','like', '%' . $input . '%');
        }
        $premiumServices = $premiumServices->paginate(getPaginate()); 
        return view('admin.hotel.premium_services', compact('pageTitle', 'premiumServices', 'input'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'code' => 'unique:premium_services,code|max:6',
            'name'       => 'required|string|max:255|unique:premium_services,name,' . $id,
            'cost'       => 'required|integer|gt:0'
        ]);

        if ($id) {
            $premiumService         = PremiumService::findOrFail($id);
            $notification          = 'Dịch vụ đã được cập nhật thành công';
        } else {
            $premiumService = new PremiumService();
            $notification  = 'Dịch vụ đã được thêm thành công';
        }

        $premiumService->code = $request->code;
        $premiumService->name = $request->name;
        $premiumService->cost = $request->cost;

        $premiumService->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return PremiumService::changeStatus($id);
    }
}
