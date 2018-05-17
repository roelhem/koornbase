<?php

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name.initials' => 'nullable|string|max:63',
            'name.first' => 'required|string|max:255',
            'name.middle' => 'nullable|string|max:255',
            'name.prefix' => 'nullable|string|max:63',
            'name.last' => 'required|string|max:255',
            'name.nickname' => 'nullable|string|max:63',

            'birth_date' => 'nullable|date|before:now',

            'emailAddresses.*._deleted' => 'boolean',
            'emailAddresses.*.label' => 'required_unless:emailAddresses.*._deleted,1|distinct|string|max:63',
            'emailAddresses.*.email_address' => 'required_unless:emailAddresses.*._deleted,1|email|string|max:255',
            'emailAddresses.*.is_primary' => 'boolean',
            'emailAddresses.*.for_emergency' => 'boolean',
            'emailAddresses.*.remarks' => 'nullable|string',

            'phoneNumbers.*._deleted' => 'boolean',
            'phoneNumbers.*.label' => 'required_unless:phoneNumbers.*._deleted,1|distinct|string|max:63',
            'phoneNumbers.*.phone_number' => 'required_unless:phoneNumbers.*._deleted,1|phone:AUTO,NL',
            'phoneNumbers.*.is_primary' => 'boolean',
            'phoneNumbers.*.for_emergency' => 'boolean',
            'phoneNumbers.*.is_mobile' => 'boolean',
            'phoneNumbers.*.remarks' => 'nullable|string',

            'remarks' => 'nullable|string',
        ];
    }
}
