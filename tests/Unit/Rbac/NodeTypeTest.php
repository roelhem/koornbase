<?php

namespace Tests\Unit\Rbac;

use MabeEnum\Enum;
use Roelhem\RbacGraph\Enums\NodeType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NodeTypeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInstance()
    {
        $nodeType = NodeType::ROLE();

        $this->assertInstanceOf(Enum::class, $nodeType);
        $this->assertInstanceOf(NodeType::class, $nodeType);

        var_dump($nodeType);
    }

    public function testLists()
    {
        dump(NodeType::getEnumerators());
        dump(NodeType::getValues());
        dump(NodeType::getNames());
        dump(NodeType::getOrdinals());
        dump(NodeType::getConstants());

        $this->assertTrue(true);
    }

    public function testInitialization() {
        $this->assertEquals(NodeType::ROLE(), NodeType::ROLE());
        $this->assertEquals(NodeType::ROLE(), NodeType::get(NodeType::ROLE));
        $this->assertEquals(NodeType::ROLE(), NodeType::get(NodeType::ROLE()));

    }
}
