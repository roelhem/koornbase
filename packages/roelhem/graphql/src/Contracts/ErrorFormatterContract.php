<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 19:38
 */

namespace Roelhem\GraphQL\Contracts;


use GraphQL\Error\Error;

interface ErrorFormatterContract
{
    public function __invoke(Error $throwable);
}