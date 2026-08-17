<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('started_at')) {
            $this->merge([
                'starts_at' => $this->started_at,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'test_id' => 'required|exists:tests,id',
            'comment' => 'nullable|string',
            'started_at' => 'required|date',
            'finished_at' => 'required|date|after:started_at',
            'active' => 'nullable|boolean',
        ];
    }
}
