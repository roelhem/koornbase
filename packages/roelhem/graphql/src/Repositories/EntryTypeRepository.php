<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 08:40
 */

namespace Roelhem\GraphQL\Repositories;


use Roelhem\GraphQL\Types\MutationType;
use Roelhem\GraphQL\Types\QueryType;

class EntryTypeRepository extends TypeRepository
{

    public function __construct($queryType, $mutationType = null)
    {
        if(is_string($queryType)) {
            $queryType = resolve($queryType);
        }

        if(!($queryType instanceof QueryType)) {
            throw new \InvalidArgumentException("Can't resolve the query-type from the provided arguments.");
        }

        $types = [
            $queryType,
            $queryType->getConnectionTypeRepository(),
        ];

        if($mutationType !== null) {
            if(is_string($mutationType)) {
                $mutationType = resolve($mutationType);
            }

            if(!($mutationType instanceof MutationType)) {
                throw new \InvalidArgumentException("Can't resolve the mutation-type from the provided arguments.");
            }

            $types[] = $mutationType;
        }

        parent::__construct($types);
    }
}