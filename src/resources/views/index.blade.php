@extends('layouts.default')

@section('content')
<div class="shop__search">
    <form id="searchForm" class="shop__search-form" action="/" method="get">
        <div class="shop__search-item">
            <select class="select__area search__name" name="area_id" onchange="submitForm()">
                <option value="" {{ request('area_id') == '' ? 'selected' : '' }}>All area</option>
                <option value="1" {{ request('area_id') == '1' ? 'selected' : '' }}>東京都</option>
                <option value="2" {{ request('area_id') == '2' ? 'selected' : '' }}>大阪府</option>
                <option value="3" {{ request('area_id') == '3' ? 'selected' : '' }}>福岡県</option>
            </select>
        </div>
        <div class="shop__search-item">
            <select class="select__genre search__name" name="genre_id" onchange="submitForm()">
                <option value="" {{ request('genre_id') == '' ? 'selected' : '' }}>All genre</option>
                <option value="1" {{ request('genre_id') == '1' ? 'selected' : '' }}>寿司</option>
                <option value="2" {{ request('genre_id') == '2' ? 'selected' : '' }}>焼肉</option>
                <option value="3" {{ request('genre_id') == '3' ? 'selected' : '' }}>居酒屋</option>
                <option value="4" {{ request('genre_id') == '4' ? 'selected' : '' }}>ラーメン</option>
                <option value="5" {{ request('genre_id') == '5' ? 'selected' : '' }}>イタリアン</option>
            </select>
        </div>
        <div class="shop__search-item">
            <input class="shop__search-input search__name" type="text" name="keyword" placeholder="Search" onkeypress="submitOnEnter(event)"/>
        </div>
    </form>
        @if (!empty($errorMessage))
            <div class="alert alert-danger">
                {{ $errorMessage }}
            </div>
        @endif
</div>

<div class="shop__list">
    <div class="shop__list-group">
        @foreach ($shops as $shop)
        <div class="shop__list-item">
            <div class="shop__img">
                <img class="shop__img-item" src="{{ asset('storage/' . $shop->image->file_path) }}" alt="">
            </div>
            <div class="shop__content">
                <div class="shop__content-item">
                    <h2 class="shop__title">{{$shop->shop_name}}</h2>
                    <p class="shop__area">#{{$shop->area->area_name}}</p>
                    <p class="shop__genre">#{{$shop->genre->genre_name}}</p>
                    <div class="detail-favorite__group">
                        <a class="shop__detail-link" href="{{ route('shop.detail', ['id' => $shop->id]) }}">
                            <button class="shop__detail-btn">詳しく見る</button>
                        </a>
                        <button class="favorite__btn" data-shop-id="{{ $shop->id }}">
                            <i class="{{ $shop->isFavorited ? 'fas' : 'far' }} fa-heart favorite__icon" style="{{ $shop->isFavorited ? 'color: red;' : '' }}">
                            </i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<script>
        // 選択肢が変更されたときにフォームを自動送信
        function submitForm() {
            document.getElementById('searchForm').submit();
        }

        // エンターキーが押されたときにフォームを送信
        function submitOnEnter(event) {
            if (event.key === 'Enter') {
                event.preventDefault();  // エンターキーによるページリロードを防ぐ
                document.getElementById('searchForm').submit();
            }
        }

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
</script>
@endsection