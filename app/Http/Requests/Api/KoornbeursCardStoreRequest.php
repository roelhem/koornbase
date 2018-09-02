<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\HandlesValidation;
use App\KoornbeursCard;
use App\Services\Validators\AfterValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class KoornbeursCardStoreRequest extends FormRequest
{

    use HandlesValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', KoornbeursCard::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'person' => 'nullable|finds:person',
            'ref' => 'required|string|max:63',
            'version' => 'required|nullable|string|max:63',
            'remarks' => 'nullable|string',
            'activated_at' => 'nullable|date',
            'deactivated_at' => 'nullable|date|after_or_equal_fields:activated_at'
        ];
    }

    public function afterValidation(Validator $validator)
    {
        $after = new AfterValidation($validator);

        $query = KoornbeursCard::query()
            ->where('ref','=',$after->getValue('ref'))
            ->where('version','=',$after->getValue('version'));

        if($query->exists()) {
            $validator->errors()->add('ref',"Er bestaat al een Koornbeurs-kaart met dezelfde 'ref' en 'version'. ");
        }
    }
}
