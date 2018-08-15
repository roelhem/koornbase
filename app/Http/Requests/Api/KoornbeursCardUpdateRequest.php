<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\KoornbeursCard;
use App\Services\Validators\AfterValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class KoornbeursCardUpdateRequest extends FormRequest
{

    use HandlesValidation, FindsModels;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $card = $this->findFromUrl('koornbeurs_card');
        return $this->user()->can('update', $card);
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
            'remarks' => 'nullable|string',
            'activated_at' => 'nullable|date',
            'deactivated_at' => 'nullable|date'
        ];
    }

    /**
     * Some extra validation after the main validation.
     *
     * @param Validator $validator
     */
    public function afterValidation(Validator $validator)
    {
        /** @var KoornbeursCard $card */
        $card = $this->findFromUrl('koornbeurs_card');

        $after = new AfterValidation($validator);
        $after->setDefaults($card->only(['activated_at','deactivated_at']));
        $after->ensureChronology(['activated_at','deactivated_at']);
    }
}
