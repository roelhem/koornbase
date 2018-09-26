<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 00:44
 */

namespace Tests\Feature\GraphQL;


use GraphQL\Type\Definition\BooleanType;
use GraphQL\Type\Definition\FloatType;
use GraphQL\Type\Definition\IDType;
use GraphQL\Type\Definition\IntType;
use GraphQL\Type\Definition\StringType;
use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;
use Roelhem\GraphQL\Repositories\TypeRepository;
use Roelhem\GraphQL\Types\MabeEnumType;
use Tests\TestCase;

class TypeRepositoryTest extends TestCase
{


    public function testRepositoryInstance()
    {
        $instance = app(TypeRepositoryContract::class);

        $this->assertInstanceOf(TypeRepositoryContract::class, $instance);

        $otherInstance = app(TypeRepositoryContract::class);

        $this->assertTrue($instance === $otherInstance);
    }

    /** @return TypeRepositoryContract */
    protected function getRepo()
    {
        return app(TypeRepositoryContract::class);
    }

    /**
     * Provides the Types that should be tested for Existence in the TypeRepository.
     *
     * @return array
     */
    public function typeProvider()
    {
        return [
            ['Int', IntType::class],
            ['SortOrderDirection', MabeEnumType::class],
            ['MembershipStatusType', MabeEnumType::class]
        ];
    }

    /**
     * @param string $typeName
     * @dataProvider typeProvider
     */
    public function testTypeExistence($typeName)
    {
        $repo = $this->getRepo();

        $this->assertTrue($repo->has($typeName));

        $names = $repo->getNames();

        $this->assertContains($typeName, $names);
    }

    /**
     * @param $typeName
     * @param string|null $typeClass
     * @dataProvider typeProvider
     */
    public function testTypeInstance($typeName, $typeClass = null)
    {
        $repo = $this->getRepo();

        $type = $repo->get($typeName);
        $this->assertInstanceOf(Type::class, $type);
        if($typeClass !== null) {
            $this->assertInstanceOf($typeClass, $type);
        }

        $otherType = $repo->get($typeName);
        $this->assertTrue($type === $otherType);
    }



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
        $repo = $this->getRepo();

        $this->assertTrue($repo->has($typeName));
        $type = $repo->get($typeName);
        $this->assertInstanceOf($typeClass, $type);
        $this->assertTrue($type === $typeInstance);
        $otherType = $repo->get($typeName);
        $this->assertTrue($type === $otherType);
    }
}