<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\FindsModels;
use Illuminate\Foundation\Http\FormRequest;

class PersonUpdateRequest extends FormRequest
{

    use FindsModels;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $person = $this->findFromUrl('person');

        return $this->user()->can('update', $person);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_first' => 'sometimes|required|string|max:255',
            'name_initials' => 'nullable|string|max:63',
            'name_middle' => 'nullable|string|max:255',
            'name_prefix' => 'nullable|string|max:63',
            'name_last' => 'sometimes|required|string|max:255',
            'name_nickname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:now',
            'remarks' => 'nullable|string'
        ];
    }
}
