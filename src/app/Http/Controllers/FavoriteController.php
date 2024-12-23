<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request, $shopId){
        $user = Auth::user();

    // お気に入りに登録されているか確認
        $favorite = Favorite::where('user_id', $user->id)->where('shop_id', $shopId)->first();

        if ($favorite) {
            // お気に入り解除
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            // お気に入り登録
            Favorite::create(['user_id' => $user->id, 'shop_id' => $shopId]);
            return response()->json(['status' => 'added']);
        }
    }
}
