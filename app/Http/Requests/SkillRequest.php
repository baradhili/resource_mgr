<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
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
			'skill_name' => 'required|string',
			'skill_description' => 'string',
			'context' => 'string',
			'category' => 'string',
			'license' => 'string',
			'source_id' => 'string',
			'type' => 'string',
			'authors' => 'string',
        ];
    }
}
