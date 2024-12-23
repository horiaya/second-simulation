
@extends('layouts.default')
<style>
    header {
        display: none;
    }
    .admin-index__remove a{
        font-size: 15px;
        background-color: white;
        text-decoration: none;
        border: 1px solid gray;
        border-radius: 3px;
        padding: 5px;
    }
    .prev-month, .next-month {
        background-color: white;
        border: 1px solid gray;
        border-radius: 3px;
    }
    #calendar {
        border-collapse: collapse;
        table-layout: fixed;
    }
    #calendar th, #calendar td {
    border: 1px solid #ddd;
    text-align: center;
    vertical-align: middle;
    padding: 8px;
    height: 50px;
    }
    .date-btn.reserved {
    background-color: #ffeb3b;
    color: #000;
    border-radius: 50%;
    }
    .date-btn.reserved:hover {
    background-color: #ffc107;
    }
    .calendar__group {
        display: flex;
    }
    .reservation-details {
        margin-left: 50px;
    }
</style>
@section('content')
<!-- 管理者用ビュー -->
<div>
    <div class="admin-index__remove">
        <a href="{{ route('admin.index') }}">戻る</a>
    </div>
    <h1>{{ $shop->shop_name }}の店舗情報</h1>
    <table>
        <tr>
            <th>店舗名/店舗画像</th>
            <td><input type="text" name="shop_name" value="{{ $shop->shop_name }}"><br>
            <input type="file" name="image" value="{{ $shop->image }}"></td>
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
            <td class="regular_holiday-representative">
                {{ $shop->regular_holidays }}
                <p style="color:red">休業日が不定期の場合は、日付を入力してください。（例: 2024-12-25, 2024-12-31）またお知らせがございましたら「お知らせ」に記載してください。</p>
                <label for="closed_days">不定期休業日</label>
                <textarea name="closed_days" id="closed_days" placeholder="例: 2024-12-25, 2024-12-31">{{ implode(', ', $shop->closed_days ?? []) }}</textarea>
                <p>お知らせ</p>
                <textarea name="" id="">{{ $shop->holidays_message }}</textarea>{{ $shop->holidays_message }}
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
            <th>お問い合せする</th>
            <td>
                <a href="mailto:{{ $shop->email }}?subject=お問い合わせ&body={{ urlencode('店舗名: ' . $shop->shop_name . '\n内容を記載してください。') }}" class="btn btn-primary">
                    メールを送信する
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <form action="{{ route('admin.destroy', $shop->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="color:red;">この店舗を削除する</button>
                </form>
            </td>
        </tr>
    </table>

    <div class="admin_reservation-status">
        <h1>{{ $shop->shop_name }}の予約状況</h1>
        <!-- カレンダーを表示 -->
    <div class="calendar-navigation">
        <button id="previousMonth" class="prev-month">前月</button>
        <button id="nextMonth" class="next-month">次月</button>
    </div>
    <div class="calendar__group">
        <div id="calendar-container">
            <table id="calendar">
                <!-- カレンダーのヘッダー -->
                <thead>
                    <tr>
                        <th>日</th>
                        <th>月</th>
                        <th>火</th>
                        <th>水</th>
                        <th>木</th>
                        <th>金</th>
                        <th>土</th>
                    </tr>
                </thead>
                <!-- カレンダーのボディ -->
                <tbody id="calendar-body">
                    <!-- JavaScriptで月ごとの日付を生成 -->
                </tbody>
            </table>
        </div>
        <!-- 日付をクリックした際に表示する詳細 -->
        <div id="reservation-details" class="reservation-details" style="display: none;">
            <h2 id="selected-date"></h2>
            <table>
                <thead>
                    <tr>
                        <th>時間</th>
                        <th>人数</th>
                        <th>予約者名</th>
                        <th>予約者メール</th>
                    </tr>
                </thead>
                <tbody id="details-body">
                    <!-- JavaScriptで選択した日の詳細を生成 -->
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const reservations = @json($reservations);
    const previousMonthButton = document.getElementById('previousMonth');
    const nextMonthButton = document.getElementById('nextMonth');
    const calendarBody = document.getElementById('calendar-body');
    const detailsBody = document.getElementById('details-body');
    const selectedDateEl = document.getElementById('selected-date');
    const detailsContainer = document.getElementById('reservation-details');
    const currentMonthEl = document.createElement('h3'); // 現在の月を表示
    document.getElementById('calendar-container').prepend(currentMonthEl);

    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    const monthNames = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];

    // カレンダーを生成
    function generateCalendar(year, month) {
        calendarBody.innerHTML = '';
        currentMonthEl.textContent = `${year}年 ${monthNames[month]}`;
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let date = 1;
        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');

            for (let j = 0; j < 7; j++) {
                const cell = document.createElement('td');
                if (i === 0 && j < firstDay) {
                    cell.innerHTML = '';
                } else if (date > daysInMonth) {
                    break;
                } else {
                    const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                    const isReserved = reservations.some(reservation => reservation.date === formattedDate);
                    cell.innerHTML = `<button class="date-btn ${isReserved ? 'reserved' : ''}" data-date="${formattedDate}">${date}</button>`;
                    date++;
                }
                row.appendChild(cell);
            }
            calendarBody.appendChild(row);
        }
    }

    // 日付クリック時の処理
    function showReservations(date) {
        const filteredReservations = reservations.filter(reservation => reservation.date === date);

        detailsBody.innerHTML = '';
        selectedDateEl.textContent = `${date} の予約状況`;
        if (filteredReservations.length > 0) {
        // 予約がある場合、詳細を表示
        filteredReservations
            .sort((a, b) => a.time.localeCompare(b.time)) // 時間順にソート
            .forEach(reservation => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${reservation.time}</td>
                    <td>${reservation.number}</td>
                    <td>${reservation.user ? reservation.user.name : '未登録のユーザー'}</td>
                    <td>${reservation.user ? reservation.user.email : 'メールアドレスなし'}</td>
                `;
                detailsBody.appendChild(row);
            });

        detailsContainer.style.display = 'block';
        } else {
            // 予約がない場合のメッセージ
            detailsBody.innerHTML = '<tr><td colspan="4">予約がありません。</td></tr>';
            detailsContainer.style.display = 'block';
        }
    }

    // 前月ボタンのクリックイベント
    previousMonthButton.addEventListener('click', () => {
        currentMonth -= 1;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear -= 1;
        }
        generateCalendar(currentYear, currentMonth);
    });

    // 次月ボタンのクリックイベント
    nextMonthButton.addEventListener('click', () => {
        currentMonth += 1;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear += 1;
        }
        generateCalendar(currentYear, currentMonth);
    });

    // 初期表示
    generateCalendar(currentYear, currentMonth);

    // 日付クリックイベント
    calendarBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('date-btn')) {
            const date = e.target.dataset.date;
            showReservations(date);
        }
    });

    const calendarContainer = document.getElementById('calendar-container');
    calendarContainer.prepend(prevButton);
    calendarContainer.append(nextButton);
});
</script>
@endsection