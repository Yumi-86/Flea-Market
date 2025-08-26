<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product_image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'name'          => ['required', 'string', 'max:255'], 
            'brand'         => ['nullable', 'string', 'max:255'],
            'price'         => ['required', 'integer', 'min:1'], 
            'description'   => ['required', 'string'],
            'condition'     => ['required', 'in:良好,目立った傷や汚れなし,やや傷や汚れあり,状態が悪い'],
            'categories'    => ['required', 'array'],
            'categories.*'  => ['exists:categories,id'],
        ];
    }
    public function messages()
    {
        return [
            'product_image.required' => '商品画像は必須です。',
            'product_image.image' => 'アップロードされたファイルは画像でなければなりません。',
            'product_image.mimes' => '画像はjpeg, jpgまたはpng形式でアップロードしてください。',
            'product_image.max' => '画像サイズは2MB以下にしてください。',

            'name.required' => '商品名は必須です。',
            'name.string' => '商品名は文字列で入力してください。',
            'name.max' => '商品名は255文字以内で入力してください。',

            'brand.string' => 'ブランド名は文字列で入力してください。',
            'brand.max' => 'ブランド名は255文字以内で入力してください。',

            'price.required' => '価格は必須です。',
            'price.integer' => '価格は数字で入力してください。',
            'price.min' => '価格は1円以上で入力してください。',

            'description.required' => '商品の説明は必須です。',

            'condition.required' => '商品の状態を選択してください。',
            'condition.in' => '商品の状態が不正です。',

            'categories.required' => 'カテゴリを1つ以上選択してください。',
            'categories.array' => 'カテゴリの形式が不正です。',
            'categories.*.exists' => '選択されたカテゴリが存在しません。',
        ];
    }
}
