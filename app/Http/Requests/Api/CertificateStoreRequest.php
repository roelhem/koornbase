<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 13:47
 */

namespace App\Http\Requests\Api;


use App\Certificate;
use App\Http\Requests\Api\Traits\HandlesValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CertificateStoreRequest extends FormRequest
{

    use HandlesValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Certificate::class);
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
            'category' => 'required|finds:certificate_category',
            'examination_at' => 'nullable|date',
            'passed' => 'boolean',
            'valid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'remarks' => 'nullable|string'
        ];
    }

    public function afterValidation(Validator $validator)
    {
        $data = $validator->getData();
        $examination_at = \Parse::try()->date(array_get($data, 'examination_at'));
        $valid_at =       \Parse::try()->date(array_get($data, 'valid_at'));
        $expired_at =     \Parse::try()->date(array_get($data, 'expired_at'));
    }

}