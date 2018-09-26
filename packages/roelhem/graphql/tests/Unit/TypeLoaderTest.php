<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 04:19
 */

namespace Roelhem\GraphQL\Tests\Unit;


use GraphQL\Type\Definition\IntType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use function PHPSTORM_META\map;
use Roelhem\GraphQL\Contracts\TypeLoaderContract;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;
use Roelhem\GraphQL\Tests\TestCase;

class TypeLoaderTest extends TestCase
{

    public function testInstance()
    {
        $instance = app(TypeLoaderContract::class);
        $this->assertInstanceOf(TypeLoaderContract::class, $instance);
        $otherInstance = app(TypeLoaderContract::class);
        $this->assertTrue($instance === $otherInstance);
    }

    public function testTypes()
    {
        /** @var TypeLoaderContract $loader */
        $loader = app(TypeLoaderContract::class);
        /** @var TypeRepositoryContract $repo */
        $repo = app(TypeRepositoryContract::class);

        foreach ($repo->getNames() as $typeName) {
            $type = $loader->load($typeName);
            $this->assertInstanceOf(Type::class, $type);
            $repoType = $repo->get($typeName);
            $this->assertTrue($type === $repoType);
        }

        $intType = $loader->load('Int');
        $this->assertInstanceOf(IntType::class, $intType);

        $objectType = new ObjectType([
            'name' => 'TestType',
            'fields' => [
                'test' => [
                    'type' => $intType,
                    'resolve' => function() { return 0; }
                ],
            ],
        ]);

        $type = $loader->load($objectType);
        $this->assertInstanceOf(ObjectType::class, $type);
        $this->assertTrue($type === $objectType);
    }

    public function testNonNull()
    {
        /** @var TypeLoaderContract $loader */
        $loader = app(TypeLoaderContract::class);

        /** @var NonNull $type */
        $type = $loader->load('Int!');

        $this->assertInstanceOf(NonNull::class, $type);

        $wrappedType = $type->getWrappedType();
        $this->assertInstanceOf(IntType::class, $wrappedType);
        $intType = $loader->load('Int');
        $this->assertFalse($type === $intType);
        $this->assertTrue($wrappedType === $intType);
    }


    public function testListOf()
    {
        /** @var TypeLoaderContract $loader */
        $loader = app(TypeLoaderContract::class);
        /** @var ListOfType $type */
        $type = $loader->load('[Int]');

        $this->assertInstanceOf(ListOfType::class, $type);

        $wrappedType = $type->getWrappedType();
        $this->assertInstanceOf(IntType::class, $wrappedType);
        $intType = $loader->load('Int');
        $this->assertFalse($type === $intType);
        $this->assertTrue($wrappedType === $intType);
    }

    public function testCombination()
    {
        /** @var TypeLoaderContract $loader */
        $loader = app(TypeLoaderContract::class);
        /** @var NonNull $type */
        $type = $loader->load('[Int!]!');
        $this->assertInstanceOf(NonNull::class, $type);
        /** @var ListOfType $listType */
        $listType = $type->getWrappedType();
        $this->assertInstanceOf(ListOfType::class, $listType);
        /** @var NonNull $wrappedNonNullType */
        $wrappedNonNullType = $listType->getWrappedType();
        $this->assertInstanceOf(NonNull::class, $wrappedNonNullType);

        $wrappedType = $wrappedNonNullType->getWrappedType();
        $this->assertInstanceOf(IntType::class, $wrappedType);
        $intType = $loader->load('Int');
        $this->assertFalse($type === $intType);
        $this->assertTrue($wrappedType === $intType);
    }


    public function testInvoke()
    {
        /** @var TypeLoaderContract $loader */
        $loader = app(TypeLoaderContract::class);

        $type = $loader->load('Int');
        $otherType = $loader('Int');
        $this->assertTrue($type === $otherType);
    }

}