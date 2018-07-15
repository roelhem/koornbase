<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 00:39
 */

namespace App\GraphQL\Queries;


use App\Person;

class PersonQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'person'
    ];

    protected $typeName = 'Person';

    /** @inheritdoc */
    protected function query($args, $selectFields)
    {
        return Person::query();
    }

}