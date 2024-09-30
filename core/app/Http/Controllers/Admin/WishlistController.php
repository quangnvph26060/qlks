<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{

    public function toggleWishlist($roomId)

    {
        $userId = Auth::id();

        //Kiểm tra xem phòng đã có trong danh sách yêu thích hay chưa
        $wishlist = Wishlist::where([
            'user_id' => $userId,
            'room_id' => $roomId,
        ])->first();

        if ($wishlist) {
            //Nếu đã có trong danh sách yêu thích, xóa khỏi danh sách
            $wishlist->delete();
            return response()->json(['message' => 'Đã xóa khỏi danh sách yêu thích', 'status' => 'success']);
        } else {
            //Nếu phòng chưa có, thêm vào danh sách yêu thích
            Wishlist::create([
                'user_id' => $userId,
                'room_id' => $roomId,
            ]);

            return response()->json(['message' => 'Đã thêm vào danh sách yêu thích', 'status' => 'success']);
        }
    }
}
