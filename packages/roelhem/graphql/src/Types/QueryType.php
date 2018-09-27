<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 16:40
 */

namespace Roelhem\GraphQL\Types;



use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Fields\ConnectionField;
use Roelhem\GraphQL\Fields\ModelByIdField;
use Roelhem\GraphQL\Repositories\ConnectionTypeRepository;
use Roelhem\GraphQL\Resolvers\QueryModelByIdResolver;
use Roelhem\GraphQL\Resolvers\QueryModelListResolver;
use Roelhem\GraphQL\Types\Traits\HasConnectionFields;

abstract class QueryType extends ObjectType
{

    use HasConnectionFields;

    public $name = 'Query';

    public $description = 'The type of the main entry-point of the graph.';

    //protected $modelByIdFields = [];

    /*

    protected function fieldSources()
    {
        return array_merge([
            $this->modelByIdFields(),
        ], parent::fieldSources());
    }


    /**
     * Returns an array of field-definitions which define the queries that enable to find a model based on a id.
     *
     * @return array
     *
    public function modelByIdFields() {
        //$resolver = new QueryModelByIdResolver();

        $res = [];

        foreach ($this->modelByIdFields as $key => $value) {
            if(is_string($value)) {
                $value = ['type' => $value];
            }
            $type = array_get($value,'type');
            $value['type'] = is_string($type) ? GraphQL::type($type) : $type;

            $field = new ModelByIdField(array_merge([
                'name' => is_string($key) ? $key : null
            ], $value));
            $res[$field->name()] = $field;
        }

        return $res;
    }*/

}