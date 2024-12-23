<?php

namespace App\Http\Controllers\StoreOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

//店舗代表者の店舗管理
class StoreOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shops = Shop::where('owner_id', Auth::id())->with(['area', 'genre', 'image'])->get();
        return view('store-owners.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('store-owners.create', compact('areas', 'genres'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'shop_name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'text' => 'required|string'
        'genre_id' => 'required|string|max:255',
        'area_id' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'tell' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'regular_holidays' => 'nullable|array',
        'holidays_message' => 'nullable',
        'open_hour' => 'nullable|date_format:H:i',
        'close_hour' => 'nullable|date_format:H:i',
        'representative_name' => 'required|string|max:255',
    ]);

        // 画像の保存
        $imageId = null;
        if ($request->hasFile('image')) {
            $uploadedImage = $request->file('image')->store('shop_images', 'public');
            $image = Image::create(['file_path' => $uploadedImage]);
            $imageId = $image->id;
        }

        // 新しい店舗の作成
        $shop = new Shop($validated);
        $shop->owner_id = Auth::id();
        $shop->image_id = $imageId;
        $shop->save();

        return redirect()->route('shops.index')->with('success', '店舗が作成されました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop)
    {
        $this->authorize('update', $shop); // 店舗代表者が所有する店舗のみ編集可能
        $areas = Area::all();
        $genres = Genre::all();
        return view('store-owners.edit', compact('shop', 'areas', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shop $shop)
    {
        $this->authorize('update', $shop);

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text' => 'required|string'
            'genre_id' => 'required|string|max:255',
            'area_id' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tell' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'regular_holidays' => 'nullable|array',
            'holidays_message' => 'nullable',
            'open_hour' => 'nullable|date_format:H:i',
            'close_hour' => 'nullable|date_format:H:i',
            'representative_name' => 'required|string|max:255',
        ]);

        // 画像の更新
        if ($request->hasFile('image')) {
            $uploadedImage = $request->file('image')->store('shop_images', 'public');

            // 古い画像を削除し、新しい画像を登録
            if ($shop->image) {
                $shop->image->delete(); 
            }

            $newImage = Image::create(['file_path' => $uploadedImage]);
            $shop->image_id = $newImage->id;
        }

        $shop->update($validated);

        return redirect()->route('shops.index')->with('success', '店舗情報が更新されました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        $this->authorize('delete', $shop);
        $shop->delete();

        return redirect()->route('shops.index')->with('success', '店舗が削除されました');
    }
}
