<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-06-18
 * Time: 20:40
 */

namespace App\Http\Requests\Api\Traits;


use Illuminate\Validation\Validator;

trait HandlesValidation
{

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
    public function afterValidation(Validator $validator) {

    }

}