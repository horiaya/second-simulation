<!-- 店舗代表者用ビュー -->
@extends('layouts.default')

@section('content')
<div class="reservation-status">
    <h1>{{ $shop->shop_name }}の予約状況</h1>
    <!-- カレンダーを表示 -->
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
    <div id="reservation-details" style="display: none;">
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const reservations = @json($reservations);
    const calendarBody = document.getElementById('calendar-body');
    const detailsBody = document.getElementById('details-body');
    const selectedDateEl = document.getElementById('selected-date');
    const detailsContainer = document.getElementById('reservation-details');

    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    // カレンダーを生成
    function generateCalendar(year, month) {
        calendarBody.innerHTML = '';
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
                    cell.innerHTML = `<button class="date-btn" data-date="${year}-${month + 1}-${date}">${date}</button>`;
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
        filteredReservations.forEach(reservation => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${reservation.time}</td>
                <td>${reservation.number}</td>
                <td>${reservation.user_name}</td>
                <td>${reservation.user_email}</td>
            `;
            detailsBody.appendChild(row);
        });

        detailsContainer.style.display = filteredReservations.length > 0 ? 'block' : 'none';
    }

    // 初期表示
    generateCalendar(currentYear, currentMonth);

    // 日付クリックイベント
    calendarBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('date-btn')) {
            const date = e.target.dataset.date;
            showReservations(date);
        }
    });
});
</script>
@endsection