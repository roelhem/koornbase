<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 03:20
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;

use Rebing\GraphQL\Support\Type as GraphQLType;

class FilterType extends GraphQLType
{

    protected $inputObject = true;

    /** @var string $typeName The name of the main GraphQL-type */
    protected $typeName;

    /**
     * FilterType constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        if($this->typeName === null) {
            $this->typeName = $this->defaultTypeName();
        }
    }

    /**
     * The default value for the TypeName, based on the class-name.
     *
     * @return string
     */
    protected function defaultTypeName()
    {
        try {
            $reflection = new \ReflectionClass($this);
            $shortName = $reflection->getShortName();
            return str_before($shortName, 'FilterType');
        } catch (\ReflectionException $exception) { throw with($exception); }
    }


    /** @inheritdoc */
    public function attributes()
    {
        return [
            'name' => $this->typeName.'_filter',
            'description' => 'This input-type can be used to apply some filters on a list of `'.$this->typeName.'`.',
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        return $this->filters() + $this->timestampFilters();
    }

    /**
     * An array of arguments to filter on the creation- and update dates.
     *
     * @return array
     */
    protected function timestampFilters() {
        return [
            'createdBefore' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the models that were created before the provided moment.'
            ],
            'createdAfter' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the models that were created after the provided moment.'
            ],
            'updatedBefore' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the models that were last edited before the provided moment.'
            ],
            'updatedAfter' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the models that were last edited after the provided moment.'
            ]
        ];
    }

    /**
     * Function that should return all the specific filters that the FilterType needs to display.
     *
     * @return array
     */
    public function filters() { return []; }

}