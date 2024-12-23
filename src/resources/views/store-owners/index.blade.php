<!-- 店舗代表者用ビュー -->
@extends('layouts.default')

@section('content')
<div class="store__representative">
    <h1>登録済みの店舗一覧</h1>
    <a href="{{ route('admin.create') }}">新規店舗を追加</a>
    <div class="store__representative-list">
        <table>
            <tr>
                <th>店舗名/店舗画像</th>
                <th>店舗説明</th>
                <th>店舗ジャンル(タグ用)</th>
                <th>都道府県のみ入力(タグ用)</th>
                <th>住所</th>
                <th>電話番号</th>
                <th>メールアドレス</th>
                <th>定休日</th>
                <th>開店時間</th>
                <th>閉店時間</th>
                <th>店舗代表者名</th>
                <th>操作</th>
                <th>予約状況</th>
            </tr>
            @foreach ($shops as $shop)
            <tr>
                <td class="shop_name-representative">{{ $shop->shop_name }}<br>{{ $shop->image ? asset('storage/' .$shop->image->file_path) : '画像がありません' }}</td>
                <td class="text-representative">{{ $shop->text }}</td>
                <td class="genre-representative">{{ $shop->genre->genre_name }}</td>
                <td class="area-representative">{{ $shop->->area->area_name }}</td>
                <td class="address-representative">{{ $shop->address }}</td>
                <td class="tell-representative">{{ $shop->tell }}</td>
                <td class="email-representative">{{ $shop->email }}</td>
                <td class="regular_holiday-representative">
                    {{ $shop->regular_holidays }}
                    <p style="color:red">休業日が不定期の場合は、日付を入力してください。（例: 2024-12-25, 2024-12-31）またお知らせがございましたら「お知らせ」に記載してください。</p>
                    <label for="closed_days">不定期休業日</label>
                    <textarea name="closed_days" id="closed_days" placeholder="例: 2024-12-25, 2024-12-31">{{ implode(', ', $shop->closed_days ?? []) }}</textarea>
                    <p>お知らせ</p>
                    {{ $shop->holidays_message }}
                </td>
                <td class="open_hours-representative">{{ $shop->open_hours }}</td>
                <td class="close_hours-representative">{{ $shop->close_hours }}</td>
                <td class="representative_name">{{ $shop->representative_name }}</td>
                <td class="edit-representative">
                    <a href="{{ route('store-owners.edit', $store) }}">編集</a>
                </td>
                <td>
                    <a href="{{ route('store-owners.reservation', ['shop' => $shop->id]) }}">確認</a>
                </td>
            </tr>
            @endforeach
        </div>
    </table>
</div>
@endsection
