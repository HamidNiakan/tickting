<?php

namespace App\Http\Requests\v1\Manager;

use App\Rules\ValidMobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
				'mobile' => ['required',new ValidMobile(),'unique:users,mobile,'.$this->userId],
				'email' => ['required','email','unique:users,email,'.$this->userId],
				'password' => [
					Password::min(8)
				]
			];
    }
}
