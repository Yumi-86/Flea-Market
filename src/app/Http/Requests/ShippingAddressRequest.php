<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingAddressRequest extends FormRequest
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
            'shipping_postal_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address' => ['required', 'string'],
            'shipping_building' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'shipping_postal_code.required' => '郵便番号は必須です。',
            'shipping_postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',
            'shipping_address.required' => '住所は必須です。',
        ];
    }
}
