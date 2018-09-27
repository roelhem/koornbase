<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 06:30
 */

namespace Roelhem\GraphQL\Fields;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Resolvers\QueryModelByIdResolver;

class ModelByIdField extends Field
{

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function name()
    {
        $name = parent::name();
        if(empty($name)) {
            return camel_case(strval($this->type()));
        }
        return $name;
    }

    public function description()
    {
        $description = parent::description();
        if(empty($description)) {
            return "Gives the `{$this->type()}`-item that has the provided `ID`.";
        }
        return $description;
    }

    public function args()
    {
        return [
            'id' => [
                'type' => GraphQL::type('ID!'),
                'description' => "The `ID` of the `{$this->type()}` that you want."
            ]
        ];
    }

    public function resolver()
    {
        return new QueryModelByIdResolver();
    }
}