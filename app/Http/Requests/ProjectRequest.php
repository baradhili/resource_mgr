<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'empowerID' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'projectManager' => 'required|string|max:255',
            // Must match exactly one of the ENUM values defined in your SQL table
            'status' => 'nullable|in:Proposed,Active,Cancelled,Completed,On Hold,Prioritised', 
        ];
    }
}