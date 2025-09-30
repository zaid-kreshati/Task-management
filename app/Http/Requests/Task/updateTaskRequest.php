<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TaskStatus;
use Illuminate\Validation\Rule;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;



class updateTaskRequest extends FormRequest
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
            'task_description_ar' => 'nullable|string|max:255',
            'task_description_en' => 'nullable|string|max:255',

            'dead_line' => 'nullable|date|after_or_equal:now',
            'status' => ['nullable', Rule::in(array_column(TaskStatus::cases(), 'value'))],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationFailedException($validator);
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if at least one of the three fields is filled
            if (!$this->checkAnyFilled(['task_description', 'dead_line', 'status'])) {
                $validator->errors()->add('fields', 'At least one of task description, deadline, or status must be provided.');
            }
        });
    }

    // Custom method to check if at least one field is filled
    protected function checkAnyFilled(array $fields)
    {
        foreach ($fields as $field) {
            if ($this->filled($field)) {
                return true;
            }
        }
        return false;
    }
}
