<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Wishlist;
use App\Http\Controllers\Controller;
use App\Models\User;
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
            $wishlist =  Wishlist::create([
                'user_id' => $userId,
                'room_id' => $roomId,
            ]);

            $room = Room::with(['roomPricesActive:price', 'wishList'])->where('id', $roomId)->first();

            return response()->json([
                'message' => 'Đã thêm vào danh sách yêu thích',
                'status' => 'success',
                'data' => $room,
            ]);
        }
    }

    public function handlePublish($id)
    {
        $user = Auth::user();

        /**
         *  @var User $user
         *
         */

        $user->wishList()->where('room_id', $id)->update(['publish' => !$user->wishList()->where('room_id', $id)->first()->publish]);


        $wishlist = $user->wishList()->where('publish', 1)->with('room')->get();


        $total = 0;

        if ($wishlist->isNotEmpty()) {
            foreach ($wishlist as $item) {
                if ($item->room) {
                    $total += $item->room->roomPricesActive->first()->price;
                }
            }
        }

        return response()->json([
            'message' => 'Đã thay đổi trạng thái',
            'status' => 'success',
            'total' => $total,
        ]);
    }

    public function handlePublishAll()
    {

        $user = Auth::user();

        /**
         *  @var User $user
         *
         */

        $user->wishList()->update(['publish' => !$user->wishList()->first()->publish]);

        $wishlist = $user->wishList()->where('publish', 1)->with('room')->get();


        $total = 0;

        if ($wishlist->isNotEmpty()) {
            foreach ($wishlist as $item) {
                if ($item->room) {
                    $total += $item->room->roomPricesActive->first()->price;
                }
            }
        }

        return response()->json([
            'message' => 'Đã thay đổi trạng thái',
            'status' => 'success',
            'total' => $total,
        ]);
    }
}
