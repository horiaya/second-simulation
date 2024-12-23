@extends('layouts.default')

@section('content')
    <h2 class="myPage__user-name">{{Auth::user()->name}}さん</h2>

    @if (session('success'))
        <div class="alert alert-success" style="color:red;">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
<div class="myPage__content">
    <div class="myPage-reservation">
        <h3 class="myPage-reservation__title">予約状況</h3>
        <div class="myPage-reservation__content">
            @foreach ($reservations as $reservation)
            <div class="myPage-reservation__item">
                <div class="reservation__heading">
                    <i class="fa-solid fa-clock clock-icon"></i>
                    <span>予約 {{ $loop->iteration }}</span>
                    <i class="fa-regular fa-circle-xmark delete-icon" data-id="{{ $reservation->id }}"></i>
                </div>
                <div class="myPage-reservation__confirmation">
                    <p><span>Shop</span><span>{{ $reservation->shop->shop_name}}</span></p>
                    <p><span>Date</span><span>{{ $reservation->date }}</span></p>
                    <p><span>Time</span><span>{{ $reservation->time }}</span></p>
                    <p><span>Number</span><span>{{ $reservation->number }}人</span></p>
                </div>
                <div class="reservation__change">
                    <a href="{{ route('reservations.edit', ['id' => $reservation->id]) }}">変更する</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="favorite__list">
        <h3 class="favorite__title">お気に入り店舗</h3>
        <div class="shop__list">
            <div class="shop__list-group">
                @foreach ($favorites as $favorite)
                <div class="myPage-shop__list">
                    <div class="shop__img">
                        <img class="shop__img-item" src="{{ isset($favorite->image) ? asset('storage/' . $favorite->image->file_path) : asset('storage/default.jpg') }}" alt="">
                    </div>
                    <div class="shop__content">
                        <div class="shop__content-item">
                            <h2 class="shop__title">{{$favorite->shop_name ?? '店舗名がありません' }}</h2>
                            <p class="shop__area">#{{$favorite->area->area_name ?? 'エリア情報なし' }}</p>
                            <p class="shop__genre">#{{$favorite->genre->genre_name ?? 'ジャンル情報なし' }}</p>
                            <div class="detail-favorite__group">
                                <a class="shop__detail-link" href="{{ route('shop.detail', ['id' => $favorite->id]) }}">
                                    <button class="shop__detail-btn">詳しく見る</button>
                                </a>
                                <button class="favorite__btn" data-shop-id="{{ $favorite->id }}">
                                    <i class="{{ $favorite->isFavorited ? 'fas' : 'far' }} fa-heart favorite__icon" style="{{ $favorite->isFavorited ? 'color: red;' : '' }}">
                                    </i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.favorite__btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();  // 通常のボタン動作を防ぐ

                const shopId = this.getAttribute('data-shop-id');// ボタンのdata-shop-id属性から店舗IDを取得
                const heartIcon = this.querySelector('.favorite__icon');//ハートのアイコンを取得

            fetch(`/favorites/toggle/${shopId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                heartIcon.classList.remove('far'); // 枠ありクラスを削除
                heartIcon.classList.add('fas');    // 塗りつぶしクラスを追加
                heartIcon.style.color = 'red';     // 赤色にする
                } else if (data.status === 'removed') {
                heartIcon.classList.remove('fas'); // 塗りつぶしクラスを削除
                heartIcon.classList.add('far');    // 枠ありクラスを追加
                heartIcon.style.color = '';        // 色を元に戻す
                }
            })
            .catch(error => console.error('Error:', error));// エラーハンドリング
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        // 削除ボタンのクリックイベントを監視
        document.querySelectorAll('.delete-icon').forEach(icon => {
            icon.addEventListener('click', function () {
                if (confirm('本当にこの予約を削除しますか？')) {
                    const reservationId = this.dataset.id;

                    // 非同期リクエストを送信
                    fetch(`/reservations/${reservationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => {
                    if (!response.ok) {
                        throw new Error('削除に失敗しました。サーバーの状態を確認してください。');
                    }
                    return response.json();
                    })
                    .then(data => {
                        if (data.message) {
                            alert(data.message);
                            // 削除後にUIから予約を削除
                            this.closest('.myPage-reservation__item').remove();

                            // 削除後に番号を繰り上げ
                            document.querySelectorAll('.myPage-reservation__item').forEach((item, index) => {
                                const orderSpan = item.querySelector('.reservation__heading span');
                                if (orderSpan) {
                                    orderSpan.textContent = `予約 ${index + 1}`;
                                }
                            });
                        }
                    })
                    .catch(error => console.error('エラー:', error));
                }
            });
        });
    });
</script>
@endsection