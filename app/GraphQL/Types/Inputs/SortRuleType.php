<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 05:22
 */

namespace App\GraphQL\Types\Inputs;

use App\Enums\SortOrderDirection;
use GraphQL;
use GraphQL\Type\Definition\Type;
use function GuzzleHttp\Psr7\str;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SortRuleType extends GraphQLType
{

    protected $inputObject = true;

    protected $modelShortName;

    /**
     * SortOrderType constructor.
     * @param Model|string $model
     * @param array $attributes
     */
    public function __construct($model, $attributes = [])
    {
        try {
            $shortName = (new \ReflectionClass($model))->getShortName();
        } catch (\ReflectionException $exception) {
            $shortName = strval($model);
        }

        $this->modelShortName = $shortName;
        parent::__construct($attributes);
    }

    public function attributes() {
        return [
            'name' => $this->modelShortName.'_sortRule',
            'description' => 'An input object that specifies the sortable field of '.$this->modelShortName.', and the direction in which the sort will be done.'
        ];
    }

    public function fields()
    {
        return [
            'by' => [
                'type' => Type::nonNull(GraphQL::type($this->modelShortName.'_sortField')),
                'description' => 'The field to order by.'
            ],
            'dir' => [
                'type' => GraphQL::type('SortOrderDirection'),
                'description' => 'The direction on which the sort should be performed.',
                'defaultValue' => SortOrderDirection::ASC
            ]
        ];
    }

}