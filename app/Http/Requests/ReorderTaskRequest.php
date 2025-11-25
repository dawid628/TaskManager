<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTaskRequest extends FormRequest
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
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.priority' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tasks.required' => 'Tasks data is required.',
            'tasks.array' => 'Tasks must be an array.',
            'tasks.*.id.required' => 'Each task must have an ID.',
            'tasks.*.id.exists' => 'One or more tasks do not exist.',
            'tasks.*.priority.required' => 'Each task must have a priority.',
            'tasks.*.priority.integer' => 'Priority must be a number.',
            'tasks.*.priority.min' => 'Priority must be at least 1.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tasks' => 'tasks',
            'tasks.*.id' => 'task ID',
            'tasks.*.priority' => 'task priority',
        ];
    }
}
