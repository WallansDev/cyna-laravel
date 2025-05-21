<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $userId = $this->route('user');

        return [
            'name' => 'nullable|string|max:50',
            'surname' => 'nullable|string|max:30',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:319',
                Rule::unique(User::class)->ignore($userId)
            ],
            'password' => $this->isMethod('post') ? 'required|min:14' : 'nullable|min:14',

            'phone' => 'nullable|string|max:255',
            'is_admin' => 'nullable|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Le nom ne doit pas dépasser 50 caractères',

            'surname.max' => 'Le prénom ne doit pas dépasser 30 caractères',

            'email.required' => "L'email est obligatoire",
            'email.email' => "L'email doit respecter la forme d'un email",
            'email.lowercase' => "L'email ne doit pas contenir de majuscules",
            'email.max' => "L'email ne doit pas dépasser 319 caractères",

            'password.required' => 'Le mot de passe est obligatoire',
            'password.max' => 'Le mot de passe doit faire au minimum 14 caractères',

            'phone.max' => 'Le numéro de téléphone ne doit pas dépasser 255 caractères',
        ];
    }
}
