<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\CommonMethodsForPersonContactObjects;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\Person;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PersonEmailAddressStoreRequest extends FormRequest
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
            'email_address' => 'required|email|string|max:255',
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
            if($this->labelExists($label, $person->emailAddresses())) {
                $validator->errors()->add(
                    'label',
                    "{$person->name_short} heeft al een email-adres met label '{$label}'."
                );
            }

        }
    }
}
