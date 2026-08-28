<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AcademicProfileUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'study_hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'attendance' => ['required', 'numeric', 'min:0', 'max:100'],
            'sleep_hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'internet_usage' => ['required', 'numeric', 'min:0', 'max:24'],

        ];
    }
}
