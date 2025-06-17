<?php

namespace App\Http\Requests\Challenge;

use Illuminate\Foundation\Http\FormRequest;

class StoreChallengeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'tasks' => ['required', 'array', 'min:1'],
            'tasks.*.name' => ['required', 'string'],
            'tasks.*.description' => ['nullable', 'string'],
            'tasks.*.days' => ['nullable', 'array'],
            'tasks.*.requires_photo' => ['boolean'],
            'tasks.*.points_weekday' => ['integer'],
            'tasks.*.points_weekend' => ['integer'],
            'tasks.*.replicate' => ['boolean'],
            'tasks.*.availability_dates' => ['nullable', 'array'],
        ];
    }
}
