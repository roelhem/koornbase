<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Queries\Traits\HasPaginationType;
use App\Person;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class PersonsQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'Persons query'
    ];

    protected $typeName = 'Person';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return Person::query();
    }

}