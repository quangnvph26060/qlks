<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumService;
use Illuminate\Http\Request;

class PremiumServiceController extends Controller
{
    public function index()
    {
        $pageTitle     = 'Premium Services';
        $premiumServices = PremiumService::latest()->paginate(getPaginate());
        return view('admin.hotel.premium_services', compact('pageTitle', 'premiumServices'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:premium_services,name,' . $id,
            'cost' => 'required|integer|gt:0'
        ]);

        if ($id) {
            $premiumService         = PremiumService::findOrFail($id);
            $notification          = 'Service updated successfully';
        } else {
            $premiumService = new PremiumService();
            $notification  = 'Service added successfully';
        }

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
