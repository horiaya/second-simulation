<!-- 管理者用ビュー -->
@extends('layouts.default')
<style>
    header {
        display: none;
    }
</style>
@section('content')
<div class="admin">
    <nav class="admin__header">
        <a href="{{ route('admin.indexStoreOwner') }}">店舗代表者の作成</a>
        <a href="{{ route('admin.indexAdmin') }}">管理者の作成</a>
        <div class="admin__logout">
            <form class="admin__logout-form" action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="admin__logout-btn">ログアウト</button>
            </form>
        </div>
    </nav>
    @if (session('status'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    <div class="admin__content">
    <div class="store-owner__list">
        <h2>店舗代表者一覧
            <button class="toggle-btn">
                <i class="fa-solid fa-caret-down hidden"></i>
            </button>
        </h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="list-content">
            <table>
                <tr>
                    <th>店舗名</th>
                    <th>店舗代表者名</th>
                    <th>エリア</th>
                    <th>ジャンル</th>
                    <th></th>
                </tr>
                @foreach($shops as $shop)
                <tr>
                    <td>{{ $shop->shop_name }}</td>
                    <td>{{ $shop->representative_name }}</td>
                    <td>{{ $shop->area->area_name }}</td>
                    <td>{{ $shop->genre->genre_name }}</td>
                    <td>@if($shop && $shop->id)
                        <a href="{{ route('admin.detail', $shop->id) }}">詳細</a>
                        @else
                        <span>詳細情報がありません</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="user__list">
        <h2>一般ユーザー一覧
            <button class="toggle-btn">
                <i class="fa-solid fa-caret-down hidden"></i>
            </button>
        </h2>
        <div class="list-content">
            <table>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>予約店舗</th>
                </tr>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if(count($user->reservations) > 0)
                        <ul>
                            @foreach($user->reservations as $reservation)
                                <li>
                                    店舗: {{ $reservation->shop->shop_name ?? '店舗情報なし' }}<br>
                                    日付: {{ $reservation->date ?? '日付情報なし' }}<br>
                                    時間: {{ $reservation->time ?? '時間情報なし' }}
                                </li>
                            @endforeach
                        </ul>
                        @else
                            <span>予約情報なし</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll('.toggle-btn');

        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                const listContent = button.closest('div').querySelector('.list-content');
                const icon = button.querySelector('i');

                if (listContent.style.display === 'none' || !listContent.style.display) {
                    listContent.style.display = 'block';
                    icon.classList.remove('fa-caret-down');
                    icon.classList.add('fa-caret-up');
                } else {
                    listContent.style.display = 'none';
                    icon.classList.remove('fa-caret-up');
                    icon.classList.add('fa-caret-down');
                }
            });
        });
    });
</script>
@endsection
