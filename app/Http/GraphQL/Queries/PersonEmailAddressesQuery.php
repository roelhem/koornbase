<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:14
 */

namespace App\Http\GraphQL\Queries;

use App\PersonEmailAddress;
use GraphQL\Type\Definition\Type;

class PersonEmailAddressesQuery extends ModelListQuery
{

    protected $typeName = 'PersonEmailAddress';

}