<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 02:16
 */

namespace Roelhem\GraphQL\Tests\Unit\Repositories;


use GraphQL\Type\Definition\BooleanType;
use GraphQL\Type\Definition\FloatType;
use GraphQL\Type\Definition\IDType;
use GraphQL\Type\Definition\IntType;
use GraphQL\Type\Definition\StringType;
use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;
use Roelhem\GraphQL\Repositories\InternalTypeRepository;
use Roelhem\GraphQL\Tests\TestCase;

class InternalTypeRepositoryTest extends TestCase
{

    /**
     * Provider for `testInternalTypes`.
     *
     * @return array
     */
    public function internalTypeProvider()
    {
        return [
            Type::ID      => [Type::ID,      IDType::class,      Type::id()],
            Type::FLOAT   => [Type::FLOAT,   FloatType::class,   Type::float()],
            Type::INT     => [Type::INT,     IntType::class,     Type::int()],
            Type::STRING  => [Type::STRING,  StringType::class,  Type::string()],
            Type::BOOLEAN => [Type::BOOLEAN, BooleanType::class, Type::boolean()],
        ];
    }


    /**
     * @param string $typeName
     * @param string $typeClass
     * @param Type $typeInstance
     * @dataProvider internalTypeProvider
     */
    public function testInternalTypes($typeName, $typeClass, $typeInstance)
    {
        /** @var TypeRepositoryContract $instance */
        $instance = new InternalTypeRepository();

        $this->assertTrue($instance->has($typeName));
        $type = $instance->get($typeName);
        $this->assertInstanceOf($typeClass, $type);
        $this->assertTrue($type === $typeInstance);
        $otherType = $instance->get($typeName);
        $this->assertTrue($type === $otherType);
    }

    public function testNamesList()
    {
        $types = Type::getInternalTypes();

        $repo = new InternalTypeRepository();
        $this->assertArraySubset(array_keys($types), $repo->getNames());
    }

}