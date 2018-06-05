<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PersonUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
