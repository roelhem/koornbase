<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 18:28
 */

namespace App\Http\Requests\Api;


use App\Certificate;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\Services\Validators\AfterValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CertificateUpdateRequest extends FormRequest
{

    use HandlesValidation, FindsModels;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $certificate = $this->findFromUrl('certificate');
        return $this->user()->can('update', $certificate);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
        /** @var Certificate $certificate */
        $certificate = $this->findFromUrl('certificate');

        $after = new AfterValidation($validator);
        $after->setDefaults($certificate->only(['examination_at','valid_at','expired_at']));
        $after->ensureChronology(['examination_at','valid_at','expired_at']);
    }

}