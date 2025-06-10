<?php

namespace App\Http\Requests\Service;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:255',
            'availbility' => 'sometimes|boolean',
            'top_position' => 'nullable|boolean',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le titre est obligatoire.',
            'name.max' => 'Le titre ne peut pas dépasser 50 caractères.',

            'image_path.required' => 'Une image est requise.',
            'image_path.max' => "L'image ne peut pas dépasser 2048.",
            'image_path.mimes' => "L'image doit être du format jpeg, png, jpg",

            'description.max' => 'La description ne peut pas dépasser 150 caractères.',
        ];
    }
}
