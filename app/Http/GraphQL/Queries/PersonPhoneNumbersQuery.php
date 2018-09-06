<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 14:46
 */

namespace App\Http\GraphQL\Queries;

use App\PersonPhoneNumber;
use GraphQL;
use GraphQL\Type\Definition\Type;

class PersonPhoneNumbersQuery extends ModelListQuery
{

    protected $typeName = 'PersonPhoneNumber';

}