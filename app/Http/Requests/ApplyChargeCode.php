<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyChargeCode extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username'=>["bail","required","regex:/^\+[1-9]\d{1,14}$/","exists:users,username"],
            'charge_code'=>["bail","required","min:3","max:20","exists:charge_codes,code"]
        ];
    }
}
