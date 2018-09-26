<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 03:07
 */

namespace Roelhem\GraphQL\Tests\Unit;


use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;
use Roelhem\GraphQL\GraphQL;
use Roelhem\GraphQL\Tests\TestCase;

class HelperTest extends TestCase
{


    public function testInstance()
    {
        $instance = app(GraphQL::class);
        $this->assertInstanceOf(GraphQL::class, $instance);
        $otherInstance = app(GraphQL::class);
        $this->assertTrue($instance === $otherInstance);
        $differentInstance = new GraphQL(app(TypeRepositoryContract::class));
        $this->assertFalse($instance === $differentInstance);
    }

    /** @return GraphQL */
    protected function getHelper()
    {
        return app(GraphQL::class);
    }




    public function testType()
    {
        $helper = $this->getHelper();
        /** @var TypeRepositoryContract $repo */
        $repo = app(TypeRepositoryContract::class);
        foreach ($repo->getNames() as $typeName) {
            $type = $helper->type($typeName);
            $this->assertTrue($type === $repo->get($typeName), 'Testing type "'.$typeName.'"');
        }


    }

}