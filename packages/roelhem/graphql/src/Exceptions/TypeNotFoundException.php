<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 00:08
 */

namespace Roelhem\GraphQL\Exceptions;


use Throwable;

class TypeNotFoundException extends \UnexpectedValueException
{

    public function __construct(string $typeName, $repository = null, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Type '$typeName' not found.", $code, $previous);
    }
}