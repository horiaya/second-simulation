<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterRequest;

//管理者専用操作
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shops = Shop::with(['area', 'genre'])->get();
        $users = User::role('user')->with(['reservations.shop'])->get();
        return view('admin.index', compact('shops', 'users'));
    }

    public function representatives()
    {
        $representatives = User::whereHas('role', function($query) {
            $query->where('name', 'representative');
        })->get();

        return view('admin.representatives', compact('representatives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    //初期管理者が新規管理者を追加
    public function createAdmin(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:8',
    ]);

    $adminRole = Role::where('name', 'admin')->first();

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $user->assignRole($adminRole->name);

    return redirect()->route('admin.index')->with('success', '新しい管理者を追加しました。');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //店舗詳細
        $shop = Shop::with(['area', 'genre', 'image', 'reservations.user'])
            ->findOrFail($id);
        $reservations = $shop->reservations;
        return view('admin.detail', compact('shop', 'reservations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateProfile(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . auth()->id(),
        'password' => 'nullable|confirmed|min:8',
    ]);

    $admin = auth()->user();

    // role_idは変更しない
    $admin->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password ? Hash::make($request->password) : $admin->password,
    ]);

    return redirect()->route('admin.profile')->with('success', 'プロフィールが更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteShopRequest $request, $id)
    {
        // セッションを完全に無効化
        $request->session()->invalidate();
        $shop = Shop::findOrFail($id);

        // 関連データの確認
        if ($shop->reservations()->exists()) {
            return redirect()->route('admin.index')
                ->with('error', '予約が存在するため削除できません。');
        }

            $shop->delete();

        return redirect()->route('admin.index')->with('success', '店舗を削除しました。');
    }

    public function deleteAdmin(User $user)
    {
    if ($user->id === 1) { // 初期管理者のIDをハードコーディング
        return redirect()->route('admin.index')->with('error', 'この管理者は削除できません。');
    }

    $user->delete();
    return redirect()->route('admin.index')->with('success', '管理者を削除しました。');
    }
}
