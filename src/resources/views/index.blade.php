<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
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
        <div class="contact-form__content">
            <div class="contact-form__heading">
                <h2>Content</h2>
            </div>
            <form class="form" action="/confirm" method="post">
                @csrf
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">お名前</span>
                        <span class="form__label--required">※</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--name">
                            <input type="text" name="last_name" placeholder="例:山田" value="{{ old('last_name') }}" />
                            <input type="text" name="first_name" placeholder="例:太郎" value="{{ old('first_name') }}" />
                        </div>
                        <div class="form__error">
                            @error('last_name')
                            <div class="form__error-message">{{ $message }}</div>
                            @enderror
                            @error('first_name')
                            <div class="form__error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">性別</span>
                        <span class="form__label--required">※</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="radio" name="gender" value="male" {{ old('gender') == 'male' || !old('gender') ? 'checked' : '' }} /> 男性
                            <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} /> 女性
                            <input type="radio" name="gender" value="other" {{ old('gender') == 'other' ? 'checked' : '' }} /> その他
                        </div>
                        <div class="form__error">
                            @error('gender')
                            <div class="form__error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">メールアドレス</span>
                        <span class="form__label--required">※</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="email" name="email" placeholder="test@example.com" value="{{ old('email') }}" />
                        </div>
                        <div class="form__error">
                            @error('email')
                            <div class="form__error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">電話番号</span>
                        <span class="form__label--required">※</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="tel" name="tel[0]" placeholder="090" value="{{ old('tel.0') }}" />
                            <span class="form__label--hyphen">-</span>
                            <input type="tel" name="tel[1]" placeholder="123" value="{{ old('tel.1') }}" />
                            <span class="form__label--hyphen">-</span>
                            <input type="tel" name="tel[2]" placeholder="4567" value="{{ old('tel.2') }}" />
                        </div>
                        <div class="form__error">
                            @error('tel.0')
                                <div class="form__error-message">{{ $message }}</div>
                            @enderror
                            @error('tel.1')
                                <div class="form__error-message">{{ $message }}</div>
                            @enderror
                            @error('tel.2')
                                <div class="form__error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">住所</span>
                        <span class="form__label--required">※</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--textarea">
                            <textarea name="address" placeholder="例:東京都千代田区1-1-1">{{ old('address') }}</textarea>
                        </div>
                        <div class="form__error">
                            @error('address')
                            <div class="form__error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">建物名</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--textarea">
                            <textarea name="building" placeholder="例:グランデュオ蒲田">{{ old('building') }}</textarea>
                        </div>
                        <div class="form__error">
                            @error('building')
                            <div class="form__error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">お問い合わせの種類</span>
                        <span class="form__label--required">※</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--textarea">
                            <select name="category_id">
                                <option value="" disabled {{ !old('category_id') ? 'selected' : '' }}>選択してください</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->content }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form__error">
                            @error('category_id')
                            <div class="form__error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">お問い合わせ内容</span>
                        <span class="form__label--required">※</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--textarea">
                            <textarea name="detail" placeholder="資料をいただきたいです">{{ old('detail') }}</textarea>
                        </div>
                        <div class="form__error">
                            @error('detail')
                            <div class="form__error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">確認画面</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>