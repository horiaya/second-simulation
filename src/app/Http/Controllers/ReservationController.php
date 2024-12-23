<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Http\Requests\ReservationRequest;


class ReservationController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'number' => 'required|integer|min:1|max:10',
            'shop_id' => 'required|exists:shops,id',
        ]);

        Reservation::create([
        'user_id' => auth()->id(),
        'shop_id' => $validated['shop_id'],
        'date' => $validated['date'],
        'time' => $validated['time'],
        'number' => $validated['number'],
        ]);

        return redirect()->route('done')->with('success', '予約が完了しました！');

        // 予約対象の店舗を取得
        $shop = Shop::findOrFail($validated['shop_id']);

        // 定休日チェック
        $regularHolidays = $shop->regular_holidays; // 定休日 (例: ["水曜日"])
        $reservationDay = now()->create($validated['date'])->format('l'); // 曜日を取得
        if (in_array($reservationDay, $regularHolidays)) {
            return redirect()->back()->withErrors(['message' => '選択された日は定休日のため予約できません。']);
        }

        // 不定期休業日のチェック
        $closedDays = $shop->closed_days; // 不定期休業日 (例: ["2024-12-25", "2024-12-31"])
        if (in_array($validated['date'], $closedDays)) {
            return redirect()->back()->withErrors(['message' => '選択された日は休業日のため予約できません。']);
        }

        // 予約可能時間のチェック
        if ($validated['time'] < $shop->open_hours || $validated['time'] > $shop->close_hours) {
            return redirect()->back()->withErrors(['message' => '予約可能時間外です。']);
        }

        // 最大収容人数のチェック
        $totalReservationsForDay = $shop->reservations()
            ->where('date', $validated['date'])
            ->sum('number'); // その日の予約済み人数
        if (($totalReservationsForDay + $validated['number']) > $shop->max_people) {
            return redirect()->back()->withErrors(['message' => '最大収容人数を超えています。']);
        }
    }

    public function getReservationsByShop($shopId)
{
    // 指定された店舗の予約を取得し、日付・時間で並び替える
    $reservations = Reservation::where('shop_id', $shopId)
        ->orderBy('date')
        ->orderBy('time')
        ->get();

    return view('shopReservations', compact('reservations'));
}


    public function delete($id)
    {
        // 該当の予約を削除
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => '予約が見つかりませんでした。'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => '予約が削除されました。']);
    }

    public function edit($id){
        $reservation = Reservation:: find($id);
        if (!$reservation) {
            return response()->json(['message' => '予約が見つかりませんでした。'], 404);
        }

    return view('edit', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        // バリデーション済みデータを取得
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'number' => 'required|integer|min:1|max:10',
            'shop_id' => 'required|exists:shops,id',
        ]);

        if (!$reservation) {
            return redirect()->back()->withErrors(['message' => '予約が見つかりません']);
        }

        $reservation->update($request->only(['date', 'time', 'number']));

        return redirect()->route('mypage')->with('success', '予約を変更しました');
    }
}

