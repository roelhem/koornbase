<?php

namespace Tests\Unit\Rbac;

use Roelhem\RbacGraph\Builders\RbacBuilder;
use Roelhem\RbacGraph\Contracts\Builder as BuilderContract;
use Roelhem\RbacGraph\Contracts\Graph as GraphContract;
use Tests\TestCase;

class RbacBuilderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $builder = new RbacBuilder();

        $this->assertInstanceOf(GraphContract::class, $builder);
        $this->assertInstanceOf(BuilderContract::class, $builder);
    }
}
