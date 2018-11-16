<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 19:02
 */

namespace Roelhem\GraphQL\Exceptions;

use Illuminate\Validation\ValidationException as OriginalValidationException;

class ValidationException extends \Exception implements ClientAwareInfo
{

    /** @var \Illuminate\Contracts\Validation\Validator */
    public $validator;

    public function __construct(OriginalValidationException $exception)
    {
        $this->validator = $exception->validator;

        parent::__construct($exception->getMessage(), $this->getCode(), $this);
    }

    /**
     * Returns true when exception message is safe to be displayed to a client.
     *
     * @api
     * @return bool
     */
    public function isClientSafe()
    {
        return true;
    }

    /**
     * Returns string describing a category of the error.
     *
     * Value "graphql" is reserved for errors produced by query parsing or validation, do not use it.
     *
     * @api
     * @return string
     */
    public function getCategory()
    {
        return 'validation';
    }

    /**
     * @return array|iterable
     */
    public function extraInfo()
    {
        return [
            'fields' => $this->validator->errors()->toArray()
        ];
    }
}