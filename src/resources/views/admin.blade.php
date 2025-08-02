@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h2>Admin</h2>
    </div>

    <!-- 検索フォーム -->
    <form class="search-form" method="GET" action="{{ route('admin') }}">
        <div class="search-row">
            <div class="search-item">
                <input type="text" name="name" class="search-input" placeholder="名前やメールアドレスを入力してください" value="{{ request('name') }}">
            </div>
            <div class="search-item">
                <select name="gender" class="search-select">
                    <option value="">性別</option>
                    <option value="all" {{ request('gender') == 'all' ? 'selected' : '' }}>全て</option>
                    <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
                    <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
                </select>
            </div>
            <div class="search-item">
                <select name="category_id" class="search-select">
                    <option value="">お問い合わせの種類</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->content }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="search-item">
                <input type="date" name="date" class="search-input" value="{{ request('date') }}">
            </div>
            <div class="search-item">
                <button type="submit" class="search-btn">検索</button>
            </div>
            <div class="search-item">
                <a href="{{ route('admin') }}" class="reset-btn">リセット</a>
            </div>
        </div>
    </form>

    <!-- エクスポートボタンとページネーション -->
    <div class="export-pagination-row">
        <div class="export-btn">
            <a href="{{ route('admin.export', request()->query()) }}" class="search-btn">エクスポート</a>
        </div>
        <div class="pagination-wrapper">
            {{ $contacts->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- お問い合わせ一覧テーブル -->
    <table class="contact-table">
        <thead>
            <tr>
                <th>お名前</th>
                <th>性別</th>
                <th>メールアドレス</th>
                <th>お問い合わせの種類</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $contact)
            <tr>
                <td>{{ $contact->last_name }} {{ $contact->first_name }}</td>
                <td>
                    @if($contact->gender == 1)
                    男性
                    @elseif($contact->gender == 2)
                    女性
                    @else
                    その他
                    @endif
                </td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->category->content ?? '' }}</td>
                <td>
                    <button type="button" class="detail-btn"
                        data-id="{{ $contact->id }}"
                        data-name="{{ $contact->last_name }} {{ $contact->first_name }}"
                        data-gender="{{ $contact->gender == 1 ? '男性' : ($contact->gender == 2 ? '女性' : 'その他') }}"
                        data-email="{{ $contact->email }}"
                        data-tel="{{ $contact->tel }}"
                        data-address="{{ $contact->address }}"
                        data-building="{{ $contact->building }}"
                        data-category="{{ $contact->category->content ?? '' }}"
                        data-detail="{{ $contact->detail }}"
                        onclick="openModal(this)">詳細</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">お問い合わせがありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- モーダルウィンドウ -->
<div id="contactModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="detail-row">
                <span class="detail-label">お名前</span>
                <span id="modal-name" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">性別</span>
                <span id="modal-gender" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">メールアドレス</span>
                <span id="modal-email" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">電話番号</span>
                <span id="modal-tel" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">住所</span>
                <span id="modal-address" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">建物名</span>
                <span id="modal-building" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">お問い合わせの種類</span>
                <span id="modal-category" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">お問い合わせ内容</span>
                <span id="modal-detail" class="detail-value"></span>
            </div>
        </div>
        <div class="modal-footer">
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn" onclick="return confirm('本当に削除しますか？')">削除</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(button) {
        const modal = document.getElementById('contactModal');

        // ボタンのdata属性から情報を取得
        document.getElementById('modal-name').textContent = button.dataset.name;
        document.getElementById('modal-gender').textContent = button.dataset.gender;
        document.getElementById('modal-email').textContent = button.dataset.email;
        document.getElementById('modal-tel').textContent = button.dataset.tel;
        document.getElementById('modal-address').textContent = button.dataset.address;
        document.getElementById('modal-building').textContent = button.dataset.building || '';
        document.getElementById('modal-category').textContent = button.dataset.category;
        document.getElementById('modal-detail').textContent = button.dataset.detail;

        // 削除フォームのactionを設定
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/contact/${button.dataset.id}`;

        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('contactModal').style.display = 'none';
    }

    // モーダル外をクリックした時に閉じる
    window.onclick = function(event) {
        const modal = document.getElementById('contactModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection