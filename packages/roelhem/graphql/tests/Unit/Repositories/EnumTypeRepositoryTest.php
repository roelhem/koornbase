<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 01:47
 */

namespace Roelhem\GraphQL\Tests\Unit\Repositories;


use Roelhem\GraphQL\Repositories\EnumTypeRepository;
use Roelhem\GraphQL\Tests\TestCase;
use Roelhem\GraphQL\Tests\TestClasses\TestEnum;
use Roelhem\GraphQL\Types\MabeEnumType;

class EnumTypeRepositoryTest extends TestCase
{

    public function testHas()
    {
        $repo = new EnumTypeRepository([
            TestEnum::class,
            [
                'name' => 'EnumLetters',
                MabeEnumType::CLASS_CONFIG_NAME => TestEnum::class
            ],
            'KeyName' => [
                'name' => 'ConfigName',
                MabeEnumType::CLASS_CONFIG_NAME => TestEnum::class
            ],
            'OtherName' => TestEnum::class,
            new MabeEnumType([
                'name' => 'FromType',
                MabeEnumType::CLASS_CONFIG_NAME => TestEnum::class,
            ]),
            'OtherKeyName' => new MabeEnumType([
                'name' => 'TypeName',
                MabeEnumType::CLASS_CONFIG_NAME => TestEnum::class,
            ]),
        ]);

        $typeNames = ['TestEnum','EnumLetters','ConfigName','OtherName','FromType','TypeName'];

        foreach ($typeNames as $typeName) {
            $this->assertTrue($repo->has($typeName), 'Asserting that the repo has Type "'.$typeName.'"');
        }

        $this->assertArraySubset($typeNames, $repo->getNames());


        $this->assertFalse($repo->has('DoesNotExist'));
        $this->assertFalse($repo->has('KeyName'));
        $this->assertFalse($repo->has('OtherKeyName'));
    }


    public function testInstances()
    {

        $instance = new MabeEnumType([
            'name' => 'TestInstance',
            MabeEnumType::CLASS_CONFIG_NAME => TestEnum::class
        ]);

        $repo = new EnumTypeRepository([
            TestEnum::class,
            [
                'name' => 'TestConfig',
                MabeEnumType::CLASS_CONFIG_NAME => TestEnum::class
            ],
            'TestKey' => TestEnum::class,
            $instance,
            'TestKeyAndConfig' => [
                MabeEnumType::CLASS_CONFIG_NAME => TestEnum::class
            ]
        ]);


        $types = ['TestEnum','TestConfig','TestKey','TestInstance','TestKeyAndConfig'];
        foreach ($types as $typeName) {
            /** @var MabeEnumType $type */
            $type = $repo->get($typeName);
            $this->assertInstanceOf(MabeEnumType::class, $type);
            $this->assertEquals($type->name, $typeName);
        }

        foreach ($types as $typeName) {
            $type = $repo->get($typeName);
            foreach ($types as $otherTypeName) {
                $otherType = $repo->get($otherTypeName);
                $this->assertEquals($typeName === $otherTypeName, $type === $otherType);
            }
        }
    }

}