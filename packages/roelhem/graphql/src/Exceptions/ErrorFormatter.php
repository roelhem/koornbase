<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 19:41
 */

namespace Roelhem\GraphQL\Exceptions;


use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use Roelhem\GraphQL\Contracts\ErrorFormatterContract;

class ErrorFormatter implements ErrorFormatterContract
{

    /**
     * @param Error $error
     * @throws
     * @return mixed[]
     */
    public function __invoke(Error $error)
    {
        $response = FormattedError::createFromException($error);

        $prevError = $error->getPrevious();

        if($prevError instanceof ClientAwareInfo) {
            $response = array_merge($response, $prevError->extraInfo());
        }

        return $response;
    }
}