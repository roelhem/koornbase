<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\CommonMethodsForPersonContactObjects;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\PersonPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PersonPhoneNumberUpdateRequest extends FormRequest
{

    use FindsModels, HandlesValidation;

    use CommonMethodsForPersonContactObjects;

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
            'country_code' => 'country_code',
            'phone_number' => 'sometimes|required|phone',
        ] + $this->contactDefaultUpdateRules();
    }

    /**
     * @inheritdoc
     */
    public function afterValidation(Validator $validator)
    {
        $data = $validator->getData();
        $personPhoneNumber = $this->findFromUrl('person_phone_number');
        if (!($personPhoneNumber instanceof PersonPhoneNumber)) {
            abort(404);
        }


        // CHECK IF THE LABEL IS UNIQUE
        $label = array_get($data, 'label');
        if ($this->labelExists($label, $personPhoneNumber->person->phoneNumbers(), $personPhoneNumber->id)) {
            $validator->errors()->add(
                'label',
                "{$personPhoneNumber->person->name_short} heeft al een telefoonnummer met label '{$label}'."
            );
        }
    }
}
