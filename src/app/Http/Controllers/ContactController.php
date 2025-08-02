<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function confirm(Request $request)
    {
        // バリデーションの実装はここに追加します。
        // 例: $request->validate([...]);

        $categories = Category::all();

        // 確認画面に必要なデータを取得してビューに渡します。
        return view('confirm', [
            'data' => $request->all(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        // 電話番号を連結して保存用のデータを準備
        $data = $request->all();

        // 電話番号が配列の場合は連結
        if (isset($data['tel']) && is_array($data['tel'])) {
            $data['tel'] = implode('', $data['tel']);
        }

        // 性別の値を数値に変換
        if (isset($data['gender'])) {
            $genderMap = [
                'male' => 1,
                'female' => 2,
                'other' => 3,
            ];
            $data['gender'] = $genderMap[$data['gender']] ?? null;
        }

        // データベースに保存
        Contact::create($data);

        // サンクスページへリダイレクト
        return view('thanks');
    }
}
