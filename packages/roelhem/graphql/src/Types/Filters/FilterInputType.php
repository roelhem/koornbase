<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 08:32
 */

namespace Roelhem\GraphQL\Types\Filters;


use GraphQL\Type\Definition\InputObjectType;
use Roelhem\GraphQL\Facades\GraphQL;

class FilterInputType extends InputObjectType
{

    const SUFFIX = '_filterInput';

    protected $filters = [];

    public function __construct(array $config)
    {

        $modelType = array_get($config, 'modelType');
        $this->filters = array_get($config,'filters', []);

        parent::__construct(array_merge([
            'name' => strval($modelType).self::SUFFIX,
            'description' => "This input-type is used to configure a *filter* on a list of `{$modelType}`-items.
                              When the filter is set, some items will be hidden (and so, the list will be shorter).
                              \n\n(When you set more than one argument in this object, both filters will work simultaneously,
                              which results in an even smaller output list.)",
            'fields' => [$this, 'fields'],
        ], $config));
    }

    /**
     * Returns an array with the definitions for the filter fields of this FilterInput.
     *
     * @return array
     */
    public function fields()
    {
        $res = array_merge([
            'withAnyId' => [
                'description' => 'Only returns the models which `ID` exists in the provided list of `ID`-values.',
                'type' => GraphQL::type('[ID!]'),
                'alias' => 'anyId',
                'importance' => 100,
            ],
            'createdBefore' => [
                'description' => 'Only returns the models that were created before the provided `DateTime`.',
                'type' => GraphQL::type('DateTime'),
                'importance' => -200,
            ],
            'createdAfter' => [
                'description' => 'Only returns the models that were created after the provided `DateTime`.',
                'type' => GraphQL::type('DateTime'),
                'importance' => -200,
            ],
            'updatedBefore' => [
                'description' => 'Only returns the models that had have not been updated before teh provided `DateTime`.',
                'type' => GraphQL::type('DateTime'),
                'importance' => -200,
            ],
            'updatedAfter' => [
                'description' => 'Only returns the models that were updated after the provided `DateTime`.',
                'type' => GraphQL::type('DateTime'),
                'importance' => -200,
            ]
        ], $this->filters);

        uasort($res, function($a, $b) {
            return array_get($b,'importance', 0) - array_get($a, 'importance', 0);
        });

        return $res;
    }

    /**
     * Takes the response of the client and then parses the filter-array so it can be used with ModelFilters.
     *
     * @param array $input
     * @return array
     * @throws
     */
    public function parseInput($input)
    {
        if($input === null) {
            return [];
        }

        if(!is_array($input)) {
            throw new \InvalidArgumentException("Input of a FilterInputType has to be an array.");
        }

        $res = [];
        foreach ($input as $fieldName => $value) {
            $field = $this->getField($fieldName);
            $filterName = array_get($field->config, 'alias', $field->name);

            $res[$filterName] = $value;
        }
        return $res;
    }

}