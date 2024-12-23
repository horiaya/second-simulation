<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Shop;

class MyPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 予約した店舗を取得
        $reservations = $user->reservations()->with('shop')->get();
        
        // お気に入り登録した店舗を取得
        $favorites = $user->favoriteShops()->with('area', 'genre', 'image')->get();

        // 各店舗をお気に入りとして表示するため、isFavoritedをtrueに設定
        foreach ($favorites as $favorite) {
            $favorite->isFavorited = true;
        }


        return view('myPage', compact('reservations', 'favorites'));
    }

    public function myPage()
    {
        $user = auth()->user();

        // お気に入り店舗を取得（リレーション経由でshopデータも取得）
        $favoriteShops = $user->favorites()->with('shop')->get();

        return view('detail.myPage', compact('favoriteShops'));
    }

}
