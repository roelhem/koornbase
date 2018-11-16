<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 19:32
 */

namespace Roelhem\GraphQL\Exceptions;


use GraphQL\Error\ClientAware;

interface ClientAwareInfo extends ClientAware
{

    /**
     * Should return an array that contains extra info about the exception.
     *
     * @return iterable|array
     */
    public function extraInfo();
}