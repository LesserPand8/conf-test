<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                FashionablyLate
            </a>
        </div>
    </header>

    <main>
        <div class="confirm__content">
            <div class="confirm__heading">
                <h2>Confirm</h2>
            </div>
            <form class="form" action="/thanks" method="post">
                @csrf
                <div class="confirm-table">
                    <table class="confirm-table__inner">
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">お名前</th>
                            <td class="confirm-table__text">
                                <input type="text" name="name" value="{{ ($data['last_name'] ?? '') . ' ' . ($data['first_name'] ?? '') }}" readonly />
                                <input type="hidden" name="last_name" value="{{ $data['last_name'] ?? '' }}" />
                                <input type="hidden" name="first_name" value="{{ $data['first_name'] ?? '' }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">性別</th>
                            <td class="confirm-table__text">
                                <input type="text" name="gender_display" value="{{ 
                                    $data['gender'] == 'male' ? '男性' : 
                                    ($data['gender'] == 'female' ? '女性' : 
                                    ($data['gender'] == 'other' ? 'その他' : '')) 
                                }}" readonly />
                                <input type="hidden" name="gender" value="{{ $data['gender'] ?? '' }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">メールアドレス</th>
                            <td class="confirm-table__text">
                                <input type="email" name="email" value="{{ $data['email'] ?? '' }}" readonly />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">電話番号</th>
                            <td class="confirm-table__text">
                                @php
                                    $tel = $data['tel'] ?? [];
                                    $telDisplay = '';
                                    if (is_array($tel)) {
                                        $telDisplay = ($tel[0] ?? '') . ($tel[1] ?? '') . ($tel[2] ?? '');
                                    } else {
                                        $telDisplay = $tel;
                                    }
                                @endphp
                                <input type="tel" name="tel_display" value="{{ $telDisplay }}" readonly />
                                @if(is_array($data['tel'] ?? null))
                                    <input type="hidden" name="tel[0]" value="{{ $data['tel'][0] ?? '' }}" />
                                    <input type="hidden" name="tel[1]" value="{{ $data['tel'][1] ?? '' }}" />
                                    <input type="hidden" name="tel[2]" value="{{ $data['tel'][2] ?? '' }}" />
                                @else
                                    <input type="hidden" name="tel" value="{{ $data['tel'] ?? '' }}" />
                                @endif
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">住所</th>
                            <td class="confirm-table__text">
                                <input type="text" name="address" value="{{ $data['address'] ?? '' }}" readonly />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">建物名</th>
                            <td class="confirm-table__text">
                                <input type="text" name="building" value="{{ $data['building'] ?? '' }}" readonly />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">お問い合わせの種類</th>
                            <td class="confirm-table__text">
                                @php
                                $categoryId = $data['category_id'] ?? '';
                                $selectedCategory = $categories->firstWhere('id', $categoryId);
                                @endphp
                                <input type="text" name="category_display" value="{{ $selectedCategory->content ?? '' }}" readonly />
                                <input type="hidden" name="category_id" value="{{ $categoryId }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">お問い合わせ内容</th>
                            <td class="confirm-table__text">
                                <input type="text" name="detail" value="{{ $data['detail'] ?? '' }}" readonly />
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">送信</button>
                    <a class="form__button-back" href="/?{{ http_build_query($data) }}">修正</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>