<?php

namespace App\Http\Requests\SubTask;
use App\Enums\TaskStatus;
use Illuminate\Validation\Rule;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;

class updateSubTaskRequest extends FormRequest
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
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'status' => ['nullable', Rule::in(array_column(TaskStatus::cases(), 'value'))],

        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if at least one of the three fields is filled
            if (!$this->checkAnyFilled([ 'name', 'status'])) {
                $validator->errors()->add('fields', 'At least one of subtask_name or status must be provided.');
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

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationFailedException($validator);
    }


}
