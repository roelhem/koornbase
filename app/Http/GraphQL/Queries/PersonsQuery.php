<?php

namespace App\Http\GraphQL\Queries;

use App\Person;
use App\PersonEmailAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class PersonsQuery extends ModelListQuery
{

    protected $typeName = 'Person';

    public function name()
    {
        return 'persons';
    }

}