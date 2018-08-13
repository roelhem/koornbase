<?php

namespace App\Http\Requests\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\Traits\CommonMethodsForPersonContactObjects;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\Person;
use App\PersonAddress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PersonAddressStoreRequest extends FormRequest
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
        return $this->user()->can('create', PersonAddress::class);
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
            'administrative_area' => 'address_field|nullable|string|max:255',
            'locality' => 'address_field|nullable|string|max:255',
            'dependent_locality' => 'address_field|nullable|string|max:255',
            'postal_code' => 'address_field|postal_code|nullable|string|max:255',
            'sorting_code' => 'address_field|nullable|string|max:255',
            'address_line_1' => 'address_field|nullable|string|max:255',
            'address_line_2' => 'address_field|nullable|string|max:255',
            'organisation' => 'address_field|nullable|string|max:255',
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
            if($this->labelExists($label, $person->addresses())) {
                $validator->errors()->add(
                    'label',
                    "{$person->name_short} heeft al een adres met label '{$label}'."
                );
            }

        }
    }

}
