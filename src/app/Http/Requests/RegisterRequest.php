<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email',
            'password' => 'required|string|min:8|max:191',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'ユーザーネームは必須項目です。',
            'name.string' => 'ユーザーネームは文字列で入力してください。',
            'name.max' => 'ユーザーネームは191文字以下で入力してくだい。',

            'email.required' => 'メールアドレスは必須項目です。',
            'email.string' => 'メールアドレスは文字列で入力してください。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'email.max' => 'メールアドレスは191文字以下で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',

            'password.required' => 'パスワードは必須項目です。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは191文字以下で入力してください。',
        ];
    }
}
