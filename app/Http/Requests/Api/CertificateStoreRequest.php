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
use App\Services\Validators\AfterValidation;
use Carbon\Carbon;
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

    /**
     * Some extra validation after the main validation.
     *
     * @param Validator $validator
     */
    public function afterValidation(Validator $validator)
    {
        $after = new AfterValidation($validator);
        $after->ensureChronology(['examination_at','valid_at','expired_at']);
    }


}