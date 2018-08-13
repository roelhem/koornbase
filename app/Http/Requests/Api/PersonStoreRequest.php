<?php

namespace App\Http\Requests\Api;

use App\Person;
use Illuminate\Foundation\Http\FormRequest;

class PersonStoreRequest extends FormRequest
{


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create',Person::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_first' => 'required|string|max:255',
            'name_initials' => 'nullable|string|max:63',
            'name_middle' => 'nullable|string|max:255',
            'name_prefix' => 'nullable|string|max:63',
            'name_last' => 'required|string|max:255',
            'name_nickname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:now',
            'remarks' => 'nullable|string'
        ];
    }
}
