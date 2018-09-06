<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 05:22
 */

namespace App\Http\GraphQL\Types\Inputs;

use App\Enums\SortOrderDirection;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SortRuleType extends GraphQLType
{

    /**
     * Sets this value to indicate that this should be a input object type.
     *
     * @var bool
     */
    protected $inputObject = true;

    /**
     * @var null|string $typeName
     */
    protected $typeName;

    /**
     * SortOrderType constructor.
     * @param string|null $typeName
     * @param array $attributes
     */
    public function __construct($typeName, $attributes = [])
    {
        $this->typeName = $typeName;

        parent::__construct($attributes);
    }

    public function attributes() {
        return [
            'name' => $this->typeName.'_orderRule',
            'description' => 'An input object that specifies the sortable field of '.$this->typeName.', and the direction in which the sort will be done.'
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        return [
            'by' => [
                'type' => Type::nonNull(GraphQL::sortFields($this->typeName)),
                'description' => 'The field to order by.'
            ],
            'dir' => [
                'type' => GraphQL::type('SortOrderDirection'),
                'description' => 'The direction on which the sort should be performed.',
                'defaultValue' => SortOrderDirection::ASC()
            ]
        ];
    }

}