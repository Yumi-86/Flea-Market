<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'name' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'profile_image.image' => 'アップロードされたファイルは画像でなければなりません。',
            'profile_image.mines' => '画像はjpeg, jpgまたはpng形式でアップロードしてください。',
            'profile_image.max' => '画像サイズは2MB以下にしてください。',

            'name.string' => '名前は文字列で入力してください。',
            'name.max' => '名前は255文字以下で入力してください。',

            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.string' => '郵便番号は文字列で入力してください。',
            'postal_code.regex' => '郵便番号はハイフンありの8文字で入力してください。',

            'address.required' => '住所を入力してください。',
            'address.string' => '住所は文字列で入力してください。',
            'address.max' => '住所は255文字以下で入力してください。',

            'building.string' => '建物名は文字列で入力してください。',
            'building.max' => '建物名は255文字以下で入力してください。',
        ];
    }
}
