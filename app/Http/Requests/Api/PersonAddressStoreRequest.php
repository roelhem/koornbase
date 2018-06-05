<?php

namespace App\Http\Requests\Api;

use App\Contracts\Finders\FinderCollection;
use App\Person;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PersonAddressStoreRequest extends FormRequest
{

    use PersonFromInputData;

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
            'person' => 'required|finds:person',
            'label' => 'required|string|max:63',
            'index' => 'integer|nullable',
            'options' => 'array',
            'country_code' => 'country_code',
            'administrative_area' => 'address_field|nullable|string|max:255',
            'locality' => 'address_field|nullable|string|max:255',
            'dependent_locality' => 'address_field|nullable|string|max:255',
            'postal_code' => 'address_field|postal_code|nullable|string|max:255',
            'sorting_code' => 'address_field|nullable|string|max:255',
            'address_line_1' => 'address_field|nullable|string|max:255',
            'address_line_2' => 'address_field|nullable|string|max:255',
            'organisation' => 'address_field|nullable|string|max:255',
            'remarks' => 'nullable|text',
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

        $label = array_get($data, 'label');
        if ($label !== null) {

            $person = $this->getPersonFromInput($data);
            if($person !== null && $person->addresses()->where(['label' => trim($label)])->exists()) {
                $validator->errors()->add('label', 'Deze persoon heeft al een adres met dit label.');
            }

        }
    }

}
