<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstname' => ['required','string','max:255'],
            'lastname'  => ['required','string','max:255'],
            'middlename'  => ['nullable','string','max:255'],
            'contact'  => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users','email')->ignore($this->id)],
            'username' => ['required', 'string', 'email', 'max:255',Rule::unique('users','username')->ignore($this->id)],
            'password' => [Rule::requiredIf(is_null($this->id)), 'confirmed', Rules\Password::defaults()],
            'avatar'    => ['nullable','image'],
            'branch_id' => ['nullable', Rule::exists('branches','id')]
        ];
    }
}
