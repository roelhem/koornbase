<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 15:23
 */

namespace Roelhem\GraphQL\Types\OrderBy;



use GraphQL\Type\Definition\EnumType;
use Roelhem\GraphQL\Enums\OrderByDirection;
use Roelhem\GraphQL\Types\ModelType;

class OrderByInputType extends EnumType
{

    public const SUFFIX = '_orderByInput';

    public function __construct($config)
    {

        $modelType = array_get($config,'modelType');
        if($modelType !== null) {
            $defaultConfig = [
                'name' => $modelType.self::SUFFIX,
                'description' => "This `Enum`-type contains all the possible ways that you can order a list of `{$modelType}`-typed objects.",
            ];
        } else {
            $defaultConfig = [
                'description' => $this->description,
            ];
        }

        $orderables = array_get($config,'orderables', $this->orderables());
        $defaultConfig['values'] = $this->orderableValues($orderables);

        parent::__construct(array_merge($defaultConfig, $config));
    }

    protected function orderables() {
        return [];
    }

    /**
     * @param array $orderables
     * @return array
     */
    protected function orderableValues($orderables = []) {
        $res = [];

        foreach ($orderables as $key => $value) {
            if(is_string($key) && is_string($value)) {
                $value = ['name' => $key, 'query' => $value];
            } elseif(is_string($value)) {
                $value = ['name' => $value];
            } elseif (is_array($value) && is_string($key) && !array_has($value, 'name')) {
                $value['name'] = $key;
            }

            $ascOrderable  = new Orderable(array_merge($value, ['direction' => OrderByDirection::ASC() ]));
            $descOrderable = new Orderable(array_merge($value, ['direction' => OrderByDirection::DESC()]));

            $ascDef = $ascOrderable->enumValueDefinition();
            $descDef = $descOrderable->enumValueDefinition();

            $res[array_get($ascDef, 'name')] = $ascDef;
            $res[array_get($descDef,'name')] = $descDef;
        }

        return $res;
    }

}