@extends('layouts.default')

@section('content')
<div class="shop__detail">
    <div class="shop__detail-content">
        <div class="detail__heading">
            <a class="detail__remove-btn" href="/">&lt;</a>
            <h2 class="detail__title">{{$shop->shop_name}}</h2>
        </div>
            <div class="shop__detail-img">
                <img class="shop__img-item" src="{{ asset('storage/' . $shop->image->file_path) }}" alt="">
            </div>
            <div class="shop__text">
                <p class="shop__tag">#{{$shop->area->area_name}}</p>
                <p class="shop__tag">#{{$shop->genre->genre_name}}</p>
                <p class="shop__text">{{$shop->text}}</p>
            </div>
    </div>

    <div class="reservation">
        <div class="reservation__content">
            <h2 class="reservation__logo">予約</h2>
            <div class="reservation__input">
                <form class="reservation__form" action="{{ route('reservations.store') }}" method="post">
                @csrf
                    <!-- 店舗IDを hidden フィールドとして埋め込む -->
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    <div class="reservation__list">
                        <input class="reservation__item" name="date" type="date" id="date" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="reservation__list">
                        <select class="reservation__item" name="time" id="time" required>
                            @php
                                $start = strtotime('10:00');
                                $end = strtotime('21:00');
                            @endphp
                            @for ($i = $start; $i <= $end; $i = strtotime('+30 minutes', $i))
                            <option value="{{ date('H:i', $i) }}">{{ date('H:i', $i) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="reservation__list">
                        <select class="reservation__item" name="number" id="number_of_people" required>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}人</option>
                            @endfor
                        </select>
                    </div>
                    <!-- 予約確認欄 -->
                    <div class="reservation__confirmation">
                        <p><span class="list-name">Shop</span><span class="selected-shop_name">{{ $shop->shop_name }}</span></p>
                        <p><span class="list-name">Date</span><span id="selected-date">未選択</span></p>
                        <p><span class="list-name">Time</span><span id="selected-time">未選択</span></p>
                        <p><span class="list-name">Number</span><span id="selected-people">未選択</span></p>
                    </div>
                    <div class="reservation__form-btn">
                        <button>予約する</button>
                    </div>
                </form>
            </div>
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
        const dateInput = document.getElementById('date');
        const timeSelect = document.getElementById('time');
        const numberSelect = document.getElementById('number_of_people');
        const currentDate = new Date();

        // 今日の日付を設定
        dateInput.setAttribute('min', currentDate.toISOString().split('T')[0]);
        dateInput.value = currentDate.toISOString().split('T')[0];

        // 初期状態の値を反映
        document.getElementById('selected-date').innerText = dateInput.value;
        document.getElementById('selected-time').innerText = timeSelect.value || '未選択';
        document.getElementById('selected-people').innerText = numberSelect.value + '人';


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
        // 最初の時間を選択状態にする
        if (timeSelect.options.length > 0) {
            timeSelect.value = timeSelect.options[0].value;
            document.getElementById('selected-time').innerText = timeSelect.value;
        } else {
            document.getElementById('selected-time').innerText = '未選択';
        }
    });

    // 初期状態で今日の日付をセットし、時間オプションを生成
    dateInput.dispatchEvent(new Event('change'));

    // 時間セレクトボックスの変更を反映
    timeSelect.addEventListener('change', function() {
        document.getElementById('selected-time').innerText = this.value;
    });
    // 人数セレクトボックスの初期値を反映
    numberSelect.addEventListener('change', function() {
        document.getElementById('selected-people').innerText = this.value + '人';
    });
    // 初期値を明示的に反映
    document.getElementById('selected-time').innerText = timeSelect.value || '未選択';
    document.getElementById('selected-people').innerText = numberSelect.value + '人';
    });
</script>
@endsection