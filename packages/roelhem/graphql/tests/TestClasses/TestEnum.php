<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 01:49
 */

namespace Roelhem\GraphQL\Tests\TestClasses;


use MabeEnum\Enum;

class TestEnum extends Enum
{
    const A = 'a';
    const B = 1;
    const C = false;
}