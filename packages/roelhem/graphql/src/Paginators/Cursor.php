<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 21:12
 */

namespace Roelhem\GraphQL\Paginators;


use Illuminate\Support\Fluent;

class Cursor extends Fluent
{
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}