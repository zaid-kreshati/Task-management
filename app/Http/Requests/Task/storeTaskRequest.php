<?php

namespace App\Http\Requests\Task;

use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class storeTaskRequest extends FormRequest
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
            'task_description_en' => 'required|string|max:255',
            'task_description_ar' => 'required|string|max:255',
            'dead_line' => 'required|date|after_or_equal:now',
            'assign_users' => 'required|array|exists:users,id',
            'assign_categories' => 'required|array|exists:categories,id',
        ];
    }

    /**
     * Handle failed validation.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \App\Exceptions\ValidationFailedException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationFailedException($validator);
    }
}
