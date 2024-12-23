<?php

namespace App\Http\Controllers\StoreOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//店舗予約管理
class OwnerReservationController extends Controller
{
    public function show(Shop $shop)
{
    $this->authorize('view', $shop);

    // 日付と時間の順で並び替え
    $reservations = $shop->reservations()
        ->with('user')
        ->orderBy('date')
        ->orderBy('time')
        ->get();

    return view('store-owners.owner-reservation', compact('shop', 'reservations'));
}


    public function update(Request $request, Shop $shop)
    {
        $this->authorize('update', $shop);

        $validated = $request->validate([
            'regular_holidays' => 'nullable|array',
            'regular_holidays.*' => 'string|in:月曜日,火曜日,水曜日,木曜日,金曜日,土曜日,日曜日',
            'open_hours' => 'nullable|date_format:H:i',
            'close_hours' => 'nullable|date_format:H:i',
            'number' => 'nullable|integer|min:1',
        ]);

        $shop->update($validated);

        return redirect()->route('store-owners.reservation', ['shop' => $shop->id])->with('success', '予約制限が更新されました');
    }

}
