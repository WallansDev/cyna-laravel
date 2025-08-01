<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => ['string', 'max:255'],
            'surname' => ['string', 'max:255'],
           'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
            'siret' => ['string', 'max:255'],
            'phone' => ['string', 'max:255'],
        ];
    }
}
