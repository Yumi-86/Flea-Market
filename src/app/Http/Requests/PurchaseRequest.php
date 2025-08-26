<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'shipping_postal_code' => ['nullable', 'string'],
            'shipping_address' => ['nullable', 'string'],
            'shipping_building' => ['nullable', 'string'],
            'payment_method' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $postal = $this->input('shipping_postal_code');
            $address = $this->input('shipping_address');

            if (empty($postal) || empty($address)) {
                $validator->errors()->add('shipping_error', '配送先情報をすべて入力してください（変更ボタンから修正可能です）');
            }
        });
    }
}
