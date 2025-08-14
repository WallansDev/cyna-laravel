<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'address_line1.required' => 'L\'adresse principale est obligatoire.',
            'address_line1.max' => 'L\'adresse principale ne peut pas dépasser 255 caractères.',
            'address_line2.max' => 'L\'adresse secondaire ne peut pas dépasser 255 caractères.',
            'city.required' => 'La ville est obligatoire.',
            'city.max' => 'Le nom de la ville ne peut pas dépasser 100 caractères.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'postal_code.max' => 'Le code postal ne peut pas dépasser 20 caractères.',
            'country.required' => 'Le pays est obligatoire.',
            'country.max' => 'Le nom du pays ne peut pas dépasser 100 caractères.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
        ];
    }
} 