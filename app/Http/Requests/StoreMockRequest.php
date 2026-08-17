<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreMockRequest extends FormRequest
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
        $this->merge([
            'user_id' => auth()->id(),
            'slug' => Str::slug($this->name, '-') . '-' . Str::random(5),
            'starts_at' => $this->started_at ?? $this->starts_at,
        ]);
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
            'comment' => 'nullable|string',
            'started_at' => 'required|date',
            'finished_at' => 'required|date|after:started_at',
            'user_id' => 'required|exists:users,id',
            'test_id' => 'required|exists:tests,id',
            'slug' => 'required|string|max:255|unique:mocks,slug',
            'active' => 'nullable|boolean',
        ];
    }
}
