<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\CommonMethodsForPersonContactObjects;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\PersonEmailAddress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PersonEmailAddressUpdateRequest extends FormRequest
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
        $emailAddress = $this->findFromUrl('person_email_address');
        return $this->user()->can('update', $emailAddress);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email_address' => 'sometimes|required|email|string|max:255',
        ] + $this->contactDefaultUpdateRules();
    }

    /**
     * @inheritdoc
     */
    public function afterValidation(Validator $validator)
    {
        $data = $validator->getData();
        $personEmailAddress = $this->findFromUrl('person_email_address');
        if (!($personEmailAddress instanceof PersonEmailAddress)) {
            abort(404);
        }


        // CHECK IF THE LABEL IS UNIQUE
        $label = array_get($data, 'label');
        if ($this->labelExists($label, $personEmailAddress->person->emailAddresses(), $personEmailAddress->id)) {
            $validator->errors()->add(
                'label',
                "{$personEmailAddress->person->name_short} heeft al een email-adres met label '{$label}'."
            );
        }
    }

}
