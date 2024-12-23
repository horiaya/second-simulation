<!-- 店舗代表者用ビュー(編集) -->
@extends('layouts.default')

@section('content')
<div class="store__representative-edit">
    <div class="store__representative-remove">
        <a href="/store-owner/index">店舗一覧へ戻る</a>
    </div>
    <div class="store__delete-btn">
        <button>この店舗を削除する</button>
    </div>
    <form class="store__representative-edit" action="{{ route('store-owners.update', $shop->id) }}" method="post">
    @foreach ($shops as $shop)
    <h1>{{ $shop->shop_name }}の編集</h1>
    <table>
        <tr>
            <th>店舗名</th>
            <td><input type="text" name="shop_name" value="{{ $shop->shop_name }}"><br>
            <input type="file" name="image" value="{{ $shop->image->file_path }}"></td>
        </tr>
        <tr>
            <th>店舗説明</th>
            <td><textarea name="text" id="" value="{{ $shop->text }}"></textarea></td>
        </tr>
        <tr>
            <th>店舗ジャンル(タグ用)</th>
            <td><input type="text" name="genre" value="{{ $shop->genre->genre_name }}"></td>
        </tr>
        <tr>
            <th>都道府県のみ入力(タグ用)</th>
            <td><input type="text" name="area" value="{{ $shop->area->area_name }}"></td>
        </tr>
        <tr>
            <th>住所</th>
            <td><input type="text" value="{{ $shop->address }}"></td>
        </tr>
        <tr>
            <th>電話番号</th>
            <td><input type="tell" name="tell" value="{{ $shop->tell }}"></td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td><input type="email" name="email" value="{{ $shop->email }}"></td>
        </tr>
        <tr>
            <th>定休日</th>
            <td>
                <!-- 定休日のチェックボックス -->
            @php
                $regularHolidays = $shop->regular_holidays ?? []; // 配列形式で取得
            @endphp
            <label><input type="checkbox" name="regular_holidays[]" value="月曜日"
                @if(in_array("月曜日", $regularHolidays)) checked @endif>月曜日</label>
            <label><input type="checkbox" name="regular_holidays[]" value="火曜日"
                @if(in_array("火曜日", $regularHolidays)) checked @endif>火曜日</label>
            <label><input type="checkbox" name="regular_holidays[]" value="水曜日"
                @if(in_array("水曜日", $regularHolidays)) checked @endif>水曜日</label>
            <label><input type="checkbox" name="regular_holidays[]" value="木曜日"
                @if(in_array("木曜日", $regularHolidays)) checked @endif>木曜日</label>
            <label><input type="checkbox" name="regular_holidays[]" value="金曜日"
                @if(in_array("金曜日", $regularHolidays)) checked @endif>金曜日</label>
            <label><input type="checkbox" name="regular_holidays[]" value="土曜日"
                @if(in_array("土曜日", $regularHolidays)) checked @endif>土曜日</label>
            <label><input type="checkbox" name="regular_holidays[]" value="日曜日"
                @if(in_array("日曜日", $regularHolidays)) checked @endif>日曜日</label>
            <!-- 不定期休業日の入力 -->
            <p style="color:red">休業日が不定期の場合は、日付を入力してください。（例: 2024-12-25, 2024-12-31）</p>
            <label for="closed_days">不定期休業日</label>
            <textarea name="closed_days" id="closed_days" placeholder="例: 2024-12-25, 2024-12-31">{{ implode(', ', $shop->closed_days ?? []) }}</textarea>
            <!-- お知らせ -->
            <p>お知らせ</p>
            <textarea name="holidays_message" id="">{{ $shop->holidays_message }}</textarea>
        </td>
        <tr>
            <th>開店時間</th>
            <td><input type="time" name="open_hours">{{ $shop->open_hours }}</td>
        </tr>
        <tr>
            <th>閉店時間</th>
            <td><input type="time" name="close_hours">{{ $shop->close_hours }}</td>
        </tr>
        <tr>
            <th>店舗代表者名</th>
            <td><input type="text" name="representative_name">{{ $shop->representative_name }}</td>
        </tr>
        <tr>
            <button>編集完了</button>
        </tr>
    </table>
    <div>
        <h2>予約制限設定</h2>
        <!-- 予約可能時間設定 -->
        <label for="open_hours">予約可能時間 (開始)</label>
        <input type="time" name="open_hours" value="{{ $shop->open_hours }}">

        <label for="close_hours">予約可能時間 (終了)</label>
        <input type="time" name="close_hours" value="{{ $shop->close_hours }}">

        <!-- 最大予約人数設定 -->
        <label for="max_people">1日の最大収容人数</label>
        <input type="number" name="max_people" value="{{ $shop->max_people }}">

        <!-- 予約制限を含む店舗の更新 -->
        <button type="submit">更新する</button>
    </div>
    @endforeach
    </form>
</div>
@endsection