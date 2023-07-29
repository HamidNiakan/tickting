<?php

namespace App\Http\Requests\Api\v1\Manager;

use App\Enums\User\DeviceTypeEnums;
use App\Enums\UserRoleEnums;
use App\Rules\ValidMobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required','string'],
			'last_name' => ['required','string'],
			'mobile' => ['required','unique:users,mobile',new ValidMobile()],
			'email' => ['required','email','unique:users,email'],
			'role_name' => ['required',new Enum(UserRoleEnums::class)],
			'password' => [
				'required',
				Password::min(8)
			]
        ];
    }
}
