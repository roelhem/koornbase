<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 01:27
 */

namespace App\Services\GraphQL;

use App\Http\GraphQL\Enums\SortFieldEnum;
use App\Http\GraphQL\Types\Inputs\SortRuleType;
use App\Http\GraphQL\Types\PaginationType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Illuminate\Container\Container;
use Rebing\GraphQL\GraphQL as BaseGraphQL;

/**
 * Class GraphQL
 * @package App\Services\GraphQL
 */
class GraphQL extends BaseGraphQL
{
    /** @var Container */
    protected $app;


    /**
     * @return GraphQLBuilder
     */
    public function builder()
    {
        return app(GraphQLBuilder::class);
    }

    /**
     * @param mixed $type
     * @throws \Exception
     * @return string
     */
    public function getModelClassOfType($type)
    {
        /** @var ObjectType|InterfaceType $type */
        $type = $this->type($type);

        $class = array_get($type->config, 'model', 'App\\'.$type);
        if(!class_exists($class)) {
            throw new \Exception("ModelClass not found for GraphQL-type `$type`.");
        }

        return $class;
    }

    /**
     * Returns the pagination type that manages the pagination of the type with the given $typeName.
     *
     * @param string $typeName
     * @param string|null $customName
     * @return Type
     */
    public function paginate($typeName, $customName = null)
    {
        $name = $customName ?: $typeName . '_pagination';

        if(!isset($this->typesInstances[$name]))
        {
            $this->typesInstances[$name] = new PaginationType($typeName, $customName);
        }

        return $this->typesInstances[$name];
    }

    /**
     * Returns the sortField enum-type that contain all the fields on which the provided type could be sorted.
     *
     * @param string $typeName
     * @return \GraphQL\Type\Definition\EnumType
     */
    public function sortFields($typeName)
    {
        $name = $typeName.'_orderField';

        if(!isset($this->typesInstances[$name]))
        {
            /** @var SortFieldEnum $type */
            $type = $this->app->makeWith(SortFieldEnum::class, ['typeName' => $typeName]);
            $this->typesInstances[$name] = $type->toType();
        }

        return $this->typesInstances[$name];
    }

    /**
     * @param string $typeName
     * @return \GraphQL\Type\Definition\InputObjectType
     */
    public function sortRule($typeName)
    {
        $name = $typeName.'_orderRule';

        if(!isset($this->typesInstances[$name]))
        {
            $type = new SortRuleType($typeName);
            $this->typesInstances[$name] = $type->toType();
        }

        return $this->typesInstances[$name];
    }

}