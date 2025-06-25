<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteTaskRequest extends FormRequest
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
            'challenge_participant_id' => 'required|exists:challenge_participants,id',
            'task_id' => 'required|exists:tasks,id',
            'date' => 'required|date',
            'completed' => 'required|boolean',
            'photo_url' => 'nullable|url',
            'points_awarded' => 'nullable|integer|min:0',
            'media_url' => 'nullable|url',
            'media_type' => 'nullable|in:photo,video,audio,text',
            'text_proof' => 'nullable|string|max:1000',
            'checked_options' => 'nullable|array',
            'status' => 'nullable|in:pending,approved,rejected'
        ];
    }
}
