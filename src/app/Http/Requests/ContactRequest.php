<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => ['required'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'tel.0' => ['required', 'numeric', 'digits_between:1,5'],
            'tel.1' => ['required', 'numeric', 'digits_between:1,5'],
            'tel.2' => ['required', 'numeric', 'digits_between:1,5'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'detail' => ['required', 'string', 'max:120'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'first_name.required' => '名を入力してください',
            'last_name.required' => '姓を入力してください',
            'gender.required' => '性別を選択してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'tel.0.required' => '電話番号を入力してください',
            'tel.0.numeric' => '電話番号は5桁までの数字で入力してください',
            'tel.0.digits_between' => '電話番号は5桁までの数字で入力してください',
            'tel.1.required' => '電話番号を入力してください',
            'tel.1.numeric' => '電話番号は5桁までの数字で入力してください',
            'tel.1.digits_between' => '電話番号は5桁までの数字で入力してください',
            'tel.2.required' => '電話番号を入力してください',
            'tel.2.numeric' => '電話番号は5桁までの数字で入力してください',
            'tel.2.digits_between' => '電話番号は5桁までの数字で入力してください',
            'address.required' => '住所を入力してください',
            'detail.required' => 'お問い合わせ内容を入力してください',
            'detail.max' => 'お問合せ内容は120文字以内で入力してください',
        ];
    }
}
