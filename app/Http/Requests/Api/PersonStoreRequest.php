<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PersonStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'name_short' => 'nullable|string|max:63',
            'name_formal' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:now',
            'remarks' => 'nullable|string',

            'emailAddresses.*.label' => 'required|string|max:63',
            'emailAddresses.*.options' => 'sometimes|required|array',
            'emailAddresses.*.remarks' => 'nullable|string',
            'emailAddresses.*.email_address' => 'required|string|email|max:255',

            'phoneNumbers.*.label' => 'required|string|max:63',
            'phoneNumbers.*.options' => 'sometimes|required|array',
            'phoneNumbers.*.remarks' => 'nullable|string'
        ];
    }
}
