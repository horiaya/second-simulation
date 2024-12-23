@extends('layouts.default')

@section('content')
<div class="reservation">
    <div class="reservation__content">
        <div class="reservation__edit-remove" style="margin-bottom:10px;">
            <a href="/myPage" style="background-color:white; padding:5px; border-radius:3px; text-decoration:none;">戻る</a>
        </div>
        <h2 class="reservation__logo">予約変更</h2>
        <div class="reservation__input">
            <form class="reservation__form" action="{{ route('reservations.update', $reservation->id) }}" method="post">
            @csrf
                <!-- 店舗IDを hidden フィールドとして埋め込む -->
                <input type="hidden" name="shop_id" value="{{ $reservation->shop_id }}">
                <div class="reservation__list">
                    <input class="reservation__item" name="date" type="date" id="date" value="{{ $reservation->date }}" required>
                </div>
                <div class="reservation__list">
                    <select class="reservation__item" name="time" id="time" required>
                        @php
                            $start = strtotime('10:00');
                            $end = strtotime('21:00');
                        @endphp
                        @for ($i = $start; $i <= $end; $i = strtotime('+30 minutes', $i))
                        <option value="{{ date('H:i', $i) }}" @if($reservation->time == date('H:i', $i)) selected @endif>{{ date('H:i', $i) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="reservation__list">
                    <select class="reservation__item" name="number" id="number_of_people" required>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" @if($reservation->number == $i) selected @endif>{{ $i }}人</option>
                        @endfor
                    </select>
                </div>
                <!-- 予約確認欄 -->
                <div class="reservation__confirmation">
                    <p><span class="list-name">Shop</span><span class="selected-shop_name">{{ $reservation->shop->shop_name }}</span></p>
                    <p><span class="list-name">Date</span><span id="selected-date">{{ $reservation->date }}</span></p>
                    <p><span class="list-name">Time</span><span id="selected-time">{{ $reservation->time }}</span></p>
                    <p><span class="list-name">Number</span><span id="selected-people">{{ $reservation->number }}</span></p>
                </div>
                <div class="reservation__form-btn">
                    <button>予約する</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('date').addEventListener('change', function() {
        document.getElementById('selected-date').innerText = this.value;
    });

    document.getElementById('time').addEventListener('change', function() {
        document.getElementById('selected-time').innerText = this.value;
    });

    document.getElementById('number_of_people').addEventListener('change', function() {
        document.getElementById('selected-people').innerText = this.value + '人';
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('selected-date').innerText = document.getElementById('date').value;
        document.getElementById('selected-time').innerText = document.getElementById('time').value;
        document.getElementById('selected-people').innerText = document.getElementById('number_of_people').value + '人';
});

    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date');
        const timeSelect = document.getElementById('time');
        const currentDate = new Date();

        // 今日の日付を設定
        dateInput.setAttribute('min', currentDate.toISOString().split('T')[0]);

        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            timeSelect.innerHTML = ''; // 時間リストをリセット

            const isToday = selectedDate.toDateString() === currentDate.toDateString();

        // 開始時刻と終了時刻を設定
        let startHour = 10;
        let startMinute = 0;
        if (isToday) {
            // 現在時刻を30分単位に切り上げ
            const now = new Date();
            startHour = now.getHours();
            startMinute = now.getMinutes();
            startMinute = Math.ceil(startMinute / 30) * 30; // 30分単位に切り上げ
            if (startMinute === 60) {
                startHour += 1;
                startMinute = 0;
            }
        }
        const endHour = 21;

        // 時間オプションを生成
        for (let hour = startHour; hour <= endHour; hour++) {
            for (let minute = (hour === startHour ? startMinute : 0); minute < 60; minute += 30) {
                const formattedTime = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                const timeOption = document.createElement('option');
                timeOption.value = formattedTime;
                timeOption.textContent = formattedTime;
                timeSelect.appendChild(timeOption);
            }
        }
    });

    // 初期状態で今日の日付をセットし、時間オプションを生成
    dateInput.value = currentDate.toISOString().split('T')[0];
    dateInput.dispatchEvent(new Event('change'));
    });
</script>
@endsection