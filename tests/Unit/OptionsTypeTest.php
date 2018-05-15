<?php

namespace Tests\Unit;

use App\GroupCategory;
use App\Types\OptionsType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OptionsTypeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @throws
     */
    public function testEmptyOptionsType()
    {
        $o = new OptionsType([
            'a' => true,
            'b' => false,
            'c' => 'hoi'
        ]);

        $this->assertTrue($o instanceof OptionsType);

        $this->assertTrue($o->a);
        $this->assertFalse($o->b);
        $this->assertEquals('hoi', $o->c);

        $this->assertTrue($o['a']);
        $this->assertFalse($o['b']);
        $this->assertEquals('hoi',$o['c']);

        $o['a'] = false;
        $o->b = true;

        $this->assertFalse($o['a']);
        $this->assertTrue($o['b']);

        $o->reset('a');
        unset($o['b']);

        $this->assertTrue($o['a']);
        $this->assertFalse($o['b']);
    }

    public function testOptionsOnGroupCategories() {

        echo GroupCategory::WhereOptions(['showOnPersonsPage'=> true])->get()->toJson();

        $this->assertTrue(true);

    }
}
