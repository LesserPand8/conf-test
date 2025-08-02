<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function confirm(ContactRequest $request)
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

    public function admin(Request $request)
    {
        $query = Contact::with('category');

        // 名前検索（姓、名、フルネーム、メールアドレスで部分一致と完全一致の両方で検索）
        if ($request->filled('name')) {
            $searchTerm = $request->name;

            $query->where(function ($q) use ($searchTerm) {
                // 部分一致検索
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ['%' . $searchTerm . '%'])
                    // 完全一致検索も同時に実行
                    ->orWhere('first_name', $searchTerm)
                    ->orWhere('last_name', $searchTerm)
                    ->orWhere('email', $searchTerm)
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) = ?", [$searchTerm])
                    ->orWhereRaw("CONCAT(last_name, first_name) = ?", [$searchTerm]);
            });
        }

        // 性別検索（「性別」がデフォルト、「全て」選択時は全性別を表示）
        if ($request->filled('gender') && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }

        // カテゴリ検索
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 日付検索
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // ページネーション（7件ずつ）
        $contacts = $query->paginate(7);

        // 検索用のカテゴリ一覧を取得
        $categories = Category::all();

        return view('admin', compact('contacts', 'categories', 'request'));
    }

    public function export(Request $request)
    {
        $query = Contact::with('category');

        // 検索条件を適用（adminメソッドと同じロジック）
        if ($request->filled('name')) {
            $searchTerm = $request->name;

            $query->where(function ($q) use ($searchTerm) {
                // 部分一致検索
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ['%' . $searchTerm . '%'])
                    // 完全一致検索も同時に実行
                    ->orWhere('first_name', $searchTerm)
                    ->orWhere('last_name', $searchTerm)
                    ->orWhere('email', $searchTerm)
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) = ?", [$searchTerm])
                    ->orWhereRaw("CONCAT(last_name, first_name) = ?", [$searchTerm]);
            });
        }

        // 性別検索（「全て」がデフォルト、「全て」選択時は全性別を表示）
        if ($request->filled('gender') && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }

        // カテゴリ検索
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 日付検索
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // 全件取得（ページネーションなし）
        $contacts = $query->get();

        // CSVファイル名を生成（現在の日時を含む）
        $filename = 'contacts_' . date('Y-m-d_H-i-s') . '.csv';

        // レスポンスヘッダーを設定
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // CSV生成のコールバック関数
        $callback = function () use ($contacts) {
            $file = fopen('php://output', 'w');

            // BOM（Byte Order Mark）を追加してExcelで文字化けを防ぐ
            fwrite($file, "\xEF\xBB\xBF");

            // CSVヘッダー
            fputcsv($file, [
                'お名前',
                '性別',
                'メールアドレス',
                '電話番号',
                '住所',
                '建物名',
                'お問い合わせの種類',
                'お問い合わせ内容',
                '作成日時'
            ]);

            // データ行
            foreach ($contacts as $contact) {
                $gender = '';
                if ($contact->gender == 1) {
                    $gender = '男性';
                } elseif ($contact->gender == 2) {
                    $gender = '女性';
                } else {
                    $gender = 'その他';
                }

                fputcsv($file, [
                    $contact->last_name . ' ' . $contact->first_name,
                    $gender,
                    $contact->email,
                    $contact->tel,
                    $contact->address,
                    $contact->building ?? '',
                    $contact->category->content ?? '',
                    $contact->detail,
                    $contact->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function delete($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $contact->delete();
        }

        return redirect()->route('admin');
    }
}
