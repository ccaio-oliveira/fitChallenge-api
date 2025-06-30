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
            'date' => 'nullable|date',
            'completed' => 'nullable|in:true,false,1,0', // Aceita string boolean
            'media_file' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi,mp3,wav,m4a|max:10240', // 10MB max
            'media_type' => 'nullable|in:photo,video,audio,text',
            'text_proof' => 'nullable|string|max:1000',
            'checked_options' => 'nullable|array', // Será enviado como checked_options[0], checked_options[1], etc.
            'checked_options.*' => 'string', // Cada item do array deve ser string
            'points_awarded' => 'nullable|integer|min:0',
            'status' => 'nullable|in:pending,approved,rejected'
        ];
    }
}
