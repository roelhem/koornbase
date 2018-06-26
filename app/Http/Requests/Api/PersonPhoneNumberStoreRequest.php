<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\CommonMethodsForPersonContactObjects;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\Person;
use App\PersonPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PersonPhoneNumberStoreRequest extends FormRequest
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
        return $this->user()->can('create', PersonPhoneNumber::class);
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
            'phone_number' => 'required|phone',
        ] + $this->contactDefaultStoreRules();
    }


    /**
     * @param Validator $validator
     */
    public function afterValidation(Validator $validator)
    {
        $data = $validator->getData();

        $person = $this->findFromInput('person');
        if($person instanceof Person) {

            $label = array_get($data, 'label');
            if($this->labelExists($label, $person->phoneNumbers())) {
                $validator->errors()->add(
                    'label',
                    "{$person->name_short} heeft al een email-adres met label '{$label}'."
                );
            }

        }
    }
}
