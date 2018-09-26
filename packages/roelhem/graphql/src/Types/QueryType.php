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
use Roelhem\GraphQL\Repositories\ConnectionTypeRepository;
use Roelhem\GraphQL\Resolvers\ModelByIdResolver;
use Roelhem\GraphQL\Resolvers\ModelListResolver;

abstract class QueryType extends ObjectType
{

    public $name = 'Query';

    public $description = 'The type of the main entry-point of the graph.';

    protected $modelByIdFields = [];

    protected $modelListFields = [];



    protected function fieldSources()
    {
        return array_merge([
            $this->modelByIdFields(),
            $this->modelListFields(),
        ], parent::fieldSources());
    }

    /**
     * Returns an array of field-definitions which define the queries that enable to find a model based on a id.
     *
     * @return array
     */
    protected function modelByIdFields() {
        $resolver = new ModelByIdResolver();

        $res = [];

        foreach ($this->modelByIdFields as $key => $value) {
            if(is_string($value)) {
                $typeName = $value;
                $type = GraphQL::type($typeName);
                $name = is_string($key) ? $key : camel_case($value);
                $description = "Gives the `{$typeName}` that has the provided `ID`.";
            } elseif(is_array($value)) {
                $typeName = array_get($value, 'type');
                $type = GraphQL::type($typeName);
                $name = array_get($value, 'name', is_string($key) ? $key : camel_case($typeName));
                $description = array_get($value,'description', "Gives the `{$typeName}` that has the provided `ID`.");
            } else {
                throw new \UnexpectedValueException("Can't create a 'modelById' query field.");
            }

            $res[] = [
                'name' => $name,
                'description' => $description,
                'type' => $type,
                'args' => [
                    'id' => [
                        'type' => GraphQL::type('ID!'),
                        'description' => 'The `ID` of the `'.$typeName.'` you want.'
                    ]
                ],
                'resolve' => $resolver
            ];
        }

        return $res;
    }

    protected $modelListFieldsConnectionTypeRepository;

    public function getModelListFieldsConnectionTypeRepository() {
        if($this->modelListFieldsConnectionTypeRepository instanceof ConnectionTypeRepository) {
            return $this->modelListFieldsConnectionTypeRepository;
        }

        $repository = new ConnectionTypeRepository();

        foreach ($this->modelListFields as $key => $value) {
            if(is_string($value)) {
                $value = ['type' => $value];
                $this->modelListFields[$key] = $value;
            }

            if(!is_array($value)) {
                throw new \UnexpectedValueException();
            }

            $type = array_get($value,'type');
            $name = array_get($value,'name',is_string($key) ? $key : camel_case(str_plural($type)));

            $this->modelListFields[$key]['name'] = $name;
            $this->modelListFields[$key]['typeName'] = $type;

            $type = $repository->addType([
                'connectionName' => $name,
                'fromType' => $this,
                'toType' => $type,
            ]);

            $this->modelListFields[$key]['type'] = $type;
        }
        $this->modelListFieldsConnectionTypeRepository = $repository;

        return $this->modelListFieldsConnectionTypeRepository;
    }

    protected function modelListFields() {
        $resolver = new ModelListResolver();
        $this->getModelListFieldsConnectionTypeRepository();

        $res = [];

        foreach ($this->modelListFields as $value) {
            $res[] = new ConnectionField(array_merge(['resolve' => $resolver], $value));
        }

        return $res;
    }

}