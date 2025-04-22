<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestRequest extends FormRequest
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
            'business_partner' => 'string',
            'request_title' => 'string',
            'background' => 'string',
            'business_need' => 'string',
            'problem_statement' => 'string',
            'specific_requirements' => 'string',
            'wbs_number' => 'string',
            'business_value' => 'string',
            'business_unit' => 'string',
            'additional_expert_contact' => 'string',
            'resource_type' => 'string',
        ];
    }
}
