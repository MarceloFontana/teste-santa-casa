<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'password' => ['nullable', Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
            'usr_blq' => ['required', Rule::in([User::USR_BLQ_DESBLOQUEADO, User::USR_BLQ_BLOQUEADO])],
        ];
    }
}
