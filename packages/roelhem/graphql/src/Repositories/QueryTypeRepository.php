<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 08:40
 */

namespace Roelhem\GraphQL\Repositories;


use Roelhem\GraphQL\Types\QueryType;

class QueryTypeRepository extends TypeRepository
{

    public function __construct($queryType)
    {
        if(is_string($queryType)) {
            $queryType = resolve($queryType);
        }

        if(!($queryType instanceof QueryType)) {
            throw new \InvalidArgumentException("Can't resolve the query-type from the provided arguments.");
        }

        parent::__construct([
            $queryType,
            $queryType->getConnectionTypeRepository(),
        ]);
    }
}