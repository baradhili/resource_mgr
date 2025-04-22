<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstimateRequest extends FormRequest
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
            'name' => 'required|string',
            'use_name_as_title' => 'required',
            'expiration_date' => 'required',
            'currency_symbol' => 'required|string',
            'currency_decimal_separator' => 'required|string',
            'currency_thousands_separator' => 'required|string',
            'allows_to_select_items' => 'required',
            'tags' => 'required|string',
            'estimate_owner' => 'required',
            'partner' => 'required',
            'total_cost' => 'required',
        ];
    }
}
