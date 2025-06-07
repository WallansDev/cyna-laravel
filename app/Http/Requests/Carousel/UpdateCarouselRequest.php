<?php

namespace App\Http\Requests\Carousel;

use App\Models\Carousel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarouselRequest extends FormRequest
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
            'title' => 'required|string|max:50',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string|max:150',
            'link' => 'nullable|string|max:250',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 50 caractères.',

            'image_path.max' => "L'image ne peut pas dépasser 2048.",
            'image_path.mimes' => "L'image doit être du format jpeg, png, jpg",

            'description.max' => 'La description ne peut pas dépasser 150 caractères.',

            'link.max' => 'Le lien ne peut pas dépasser 250 caractères.',
        ];
    }
}
