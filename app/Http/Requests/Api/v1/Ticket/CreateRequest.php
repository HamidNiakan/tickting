<?php

namespace App\Http\Requests\Api\v1\Ticket;

use App\Enums\Ticket\TicketPriorityEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateRequest extends FormRequest
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
            'title' => ['required','string'],
			'message' => ['required','string'],
			'priority' => ['required', new Enum(TicketPriorityEnums::class)],
        ];
    }
}
