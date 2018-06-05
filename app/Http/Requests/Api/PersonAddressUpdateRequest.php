<?php

namespace App\Http\Requests\Api;

use App\Contracts\Finders\FinderCollection;
use App\PersonAddress;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\Finder\Finder;

class PersonAddressUpdateRequest extends FormRequest
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
            'label' => 'sometimes|required|string|max:63',
            'index' => 'integer|nullable',
            'options' => 'array',
            'country_code' => 'country_code',
            'administrative_area' => 'nullable|string|max:255',
            'locality' => 'nullable|string|max:255',
            'dependent_locality' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'sorting_code' => 'nullable|string|max:255',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'organisation' => 'nullable|string|max:255',
            'remarks' => 'nullable|text'
        ];
    }

    /**
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after([$this, 'afterValidation']);
    }

    /**
     * @param Validator $validator
     */
    public function afterValidation(Validator $validator)
    {
        $data = $validator->getData();
        $personAddress = $this->getPersonAddress();

        // CHECK IF THE LABEL IS UNIQUE
        $label = array_get($data, 'label');
        if($label !== null) {
            $person = $personAddress->person;
            $query = $person->addresses()
                ->where([
                    ['label', '=', trim($label)],
                    ['id','<>', $personAddress->id]
                ]);
            if($query->exists()) {
                $validator->errors()->add('label','Er bestaat al een adres van deze persoon met dit label.');
            }
        }

        // RE-VALIDATE THE ADDRESS FIELDS
        $addressRules = [
            'country_code'       => 'required|country_code',
            'adminstrative_area' => 'address_field:allow_unused',
            'locality'           => 'address_field:allow_unused',
            'dependent_locality' => 'address_field:allow_unused',
            'postal_code'        => 'address_field:allow_unused|postal_code',
            'sorting_code'       => 'address_field:allow_unused',
            'address_line_1'     => 'address_field:allow_unused',
            'address_line_2'     => 'address_field:allow_unused',
            'organisation'       => 'address_field:allow_unused'
        ];

        $addressAttributes = array_keys($addressRules);
        $inputAddressValues = array_only($data, $addressAttributes);

        if(count($inputAddressValues) > 0) {
            $oldAddressValues = $personAddress->only($addressAttributes);

            $addressValues = array_merge($oldAddressValues, $inputAddressValues);

            $subValidator = \Validator::make($addressValues, $addressRules);
            if($subValidator->fails()) {
                $validator->errors()->merge($subValidator->errors());
            }
        }


    }

    /**
     * Gets the PersonAddress that needs to be updated
     * @return PersonAddress
     */
    protected function getPersonAddress()
    {
        return resolve(FinderCollection::class)->find($this->route('person_address'), 'person_address');
    }
}
