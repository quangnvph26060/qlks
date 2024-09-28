<?php

namespace App\Http\Controllers\Admin;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function addToWishlist($roomId)
    {
        $userId = Auth::id();

        $existing = Wishlist::where([
            'user_id' => $userId,
            'room_id' => $roomId,
        ])->first();

        if ($existing) {
            return response()->json(['message' => 'Phòng đã tồn tại trong danh sách yêu thích!', 'status' => 'error']);
        }

        Wishlist::create([
            'user_id' => $userId,
            'room_id' => $roomId,
        ]);

        return response()->json(['message' => 'Đã thêm vào danh sách yêu thích.', 'status' => 'success']);
    }

    // Remove room from wishlist
    public function removeFromWishlist(Request $request, $roomId)
    {
        $userId = Auth::id();

        $wishlist = Wishlist::where([
            'user_id' => $userId,
            'room_id' => $roomId,
        ])->first();

        if (!$wishlist) {
            return response()->json(['message' => 'Không tìm thấy phòng trong danh sách yêu thích!', 'status' => 'error']);
        }

        // Remove from wishlist
        $wishlist->delete();

        return response()->json(['message' => 'Đã xóa khỏi danh sách yêu thích', 'status' => 'success']);
    }
}
