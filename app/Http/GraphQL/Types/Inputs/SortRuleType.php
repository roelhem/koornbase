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
use Illuminate\Database\Eloquent\Model;
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
     * @var Model|string $model
     */
    protected $model;

    /**
     * @var null|string $typeName
     */
    protected $typeName;

    /**
     * SortOrderType constructor.
     * @param Model|string $model
     * @param string|null $typeName
     * @param array $attributes
     */
    public function __construct($model, $typeName = null, $attributes = [])
    {
        $this->model = $model;
        $this->typeName = $typeName;

        if($this->typeName === null) {
            $this->typeNameFromModel();
        }

        parent::__construct($attributes);
    }

    /**
     * Sets the value of `$this->typeName` based on the model.
     */
    protected function typeNameFromModel() {
        try {
            $this->typeName = (new \ReflectionClass($this->model))->getShortName();
        } catch (\ReflectionException $exception) {
            $this->typeName = strval($this->model);
        }
    }

    public function attributes() {
        return [
            'name' => $this->typeName.'_sortRule',
            'description' => 'An input object that specifies the sortable field of '.$this->typeName.', and the direction in which the sort will be done.'
        ];
    }

    public function fields()
    {
        return [
            'by' => [
                'type' => Type::nonNull(GraphQL::type($this->typeName.'_sortField')),
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