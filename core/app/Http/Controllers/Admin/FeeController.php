<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdditionalFee;
use App\Models\Fee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeeController extends Controller
{
    public function index()
    {
        try {
            $fee = Fee::first();
            return view('admin.fee.index', compact('fee'));
        } catch (Exception $e) {
            Log::error("Failed to get all Fees: " . $e->getMessage());
            return response()->json(['error' => 'Failed to get all Fees'], 500);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            // Fetch the first or create a new fees record
            $fees = Fee::firstOrCreate([]);
            $perHour = preg_replace('/[^\d]/', '', $request->input('per_hour'));
            $perDay = preg_replace('/[^\d]/', '', $request->input('per_day'));
            $perNight = preg_replace('/[^\d]/', '', $request->input('per_night'));
            $perSeason = preg_replace('/[^\d]/', '', $request->input('per_season'));
            $perEvent = preg_replace('/[^\d]/', '', $request->input('per_event'));
            // Update the fees with request input, handling nullable values
            $fees->update([
                'per_hour'   => $perHour !== '' ? $perHour : 0,
                'per_day'    => $perDay !== '' ? $perDay : 0,
                'per_night'  => $perNight !== '' ? $perNight : 0,
                'per_season' => $perSeason !== '' ? $perSeason : 0,
                'per_event'  => $perEvent !== '' ? $perEvent : 0,
            ]);

            DB::commit();
            return redirect()->route('admin.fee.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update Fees: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update Fees'], 500);
        }
    }
}
