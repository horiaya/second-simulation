<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Http\Requests\SearchRequest;

class ShopController extends Controller
{
    public function index(SearchRequest $request)
    {
        // リクエストから検索条件を取得
        $areaId = $request->input('area_id');
        $genreId = $request->input('genre_id');
        $keyword = $request->input('keyword');

        // スコープを使用してクエリを組み立てる
        $shops = Shop::with(['area', 'genre', 'image'])
            ->area($areaId)
            ->genre($genreId)
            ->keyword($keyword)
            ->get();

        $user = auth()->user();

        $favoriteShopIds = $user->favoriteShops->pluck('id')->toArray();

        foreach ($shops as $shop) {
            $shop->isFavorited = in_array($shop->id, $favoriteShopIds);
        }

        $errorMessage = null;
        if ($shops->isEmpty()) {
            $errorMessage = '該当する店舗が見つかりませんでした。';
        }

        return view('index', compact('shops', 'errorMessage'));

    }

    public function show($id){
        $shop = Shop::with(['area', 'genre', 'image'])->findOrFail($id);

        return view('detail', compact('shop'));
    }
}
