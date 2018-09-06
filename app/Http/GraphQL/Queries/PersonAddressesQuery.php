<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 15:15
 */

namespace App\Http\GraphQL\Queries;


use App\PersonAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;

class PersonAddressesQuery extends ModelListQuery
{

    protected $typeName = 'PersonAddress';


}