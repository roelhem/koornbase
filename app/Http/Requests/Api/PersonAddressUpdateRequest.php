<?php

namespace App\Http\Requests\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\Traits\CommonMethodsForPersonContactObjects;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\PersonAddress;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\Finder\Finder;

class PersonAddressUpdateRequest extends FormRequest
{

    use HandlesValidation, FindsModels;

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
            'administrative_area' => 'nullable|string|max:255',
            'locality' => 'nullable|string|max:255',
            'dependent_locality' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'sorting_code' => 'nullable|string|max:255',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'organisation' => 'nullable|string|max:255',
        ] + $this->contactDefaultUpdateRules();
    }

    /**
     * @param Validator $validator
     */
    public function afterValidation(Validator $validator)
    {
        $data = $validator->getData();
        $personAddress = $this->findFromUrl('person_address');
        if(!($personAddress instanceof PersonAddress)) { abort(404); }



        // CHECK IF THE LABEL IS UNIQUE
        $label = array_get($data, 'label');
        if($this->labelExists($label, $personAddress->person->addresses(),$personAddress->id)) {
            $validator->errors()->add(
                'label',
                "{$personAddress->person->name_short} heeft al een adres met label '{$label}'."
            );
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

}
