<!-- 店舗代表者用ビュー(新規作成) -->
@extends('layouts.default')

@section('content')
<div class="store__representative-create">
    <div>
        <a href="/store-owner/index">店舗一覧へ戻る</a>
    </div>
    <h1>店舗新規作成</h1>
    <form class="store__representative-form" action="{{ route('admin.create') }}" method="post">
    <div class="create__reservation-content">
    <table>
        <tr>
            <th>店舗名/店舗画像</th>
            <td><td class="shop_name-representative"><input type="text" name="shop_name"><br>
            <input type="file" name="image" placeholder="画像url"></td></td>
        </tr>
        <tr>
            <th>店舗説明</th>
            <td class="text-representative"><textarea name="text" id=""></textarea></td>
        </tr>
        <tr>
            <th>店舗ジャンル(タグ用)</th>
            <td class="genre-representative"><input type="text" name="genre" placeholder="例：焼肉、イタリアン"></td>
        </tr>
        <tr>
            <th>都道府県のみ入力(タグ用)</th>
            <td class="area-representative"><input type="text" name="area" placeholder="例：東京都"></td>
        </tr>
        <tr>
            <th>住所</th>
            <td class="address-representative"><input type="text"></td>
        </tr>
        <tr>
            <th>電話番号</th>
            <td class="tell-representative"><input type="tell" name="tell"></td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td class="email-representative"><input type="email" name="email"></td>
        </tr>
        <tr>
            <th>定休日</th>
            <td class="regular_holidays">
                <label for=""><input type="checkbox" name="regular_holidays" value="月曜日">月曜日</label>
                <label for=""><input type="checkbox" name="regular_holidays" value="火曜日">火曜日</label>
                <label for=""><input type="checkbox" name="regular_holidays" value="水曜日">水曜日</label>
                <label for=""><input type="checkbox" name="regular_holidays" value="木曜日">木曜日</label>
                <label for=""><input type="checkbox" name="regular_holidays" value="金曜日">金曜日</label>
                <label for=""><input type="checkbox" name="regular_holidays" value="土曜日">土曜日</label>
                <label for=""><input type="checkbox" name="regular_holidays" value="日曜日">日曜日</label>
                <p style="color:red">休業日が不定期の場合は、日付を入力してください。（例: 2024-12-25, 2024-12-31）またお知らせがございましたら「お知らせ」に記載してください。</p>
                <label for="closed_days">不定期休業日</label>
                <textarea name="closed_days" id="closed_days" placeholder="例: 2024-12-25, 2024-12-31"></textarea>
                <p>お知らせ</p>
                <textarea name="holidays_message" id="" placeholder="特別なお知らせがある場合、記入してください"></textarea>
            </td>
        </tr>
        <tr>
            <th>開店時間</th>
            <td class="open_hours-representative"><input type="time" name="open_hours"></td>
        </tr>
        <tr>
            <th>閉店時間</th>
            <td class="close_hours-representative"><input type="time" name="close_hours"></td>
        </tr>
        <tr>
            <th>店舗代表者名</th>
            <td class="representative_name"><input type="text" name="representative_name"></td>
        </tr>
    </table>
    </div>

    <div class="reservation-settings">
    <h2>予約制限設定</h2>
        <!-- 予約可能時間設定 -->
        <table>
        <tr>
            <th>予約可能時間</th>
            <td class="reservation-times">
                <label for="open_hours">開始時間</label>
                <input type="time" name="open_hours" id="open_hours">
                <label for="close_hours">終了時間</label>
                <input type="time" name="close_hours" id="close_hours">
            </td>
        </tr>
        <tr>
            <th>最大予約人数</th>
            <td class="max-people">
                <input type="number" name="number" id="number" placeholder="1日の最大収容人数を入力">
            </td>
        </tr>
        <tr>
            <td><button type="reservation-restriction__submit">作成する</button></td>
        </tr>
        </table>
    </div>
    </form>
</div>
@endsection