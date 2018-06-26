<?php

namespace Tests\Unit;

use App\Enums\MembershipStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MembershipStatusTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $outsider = MembershipStatus::OUTSIDER();

        $this->assertInstanceOf(MembershipStatus::class, $outsider);

        $this->assertEquals(0, $outsider->value);
        $this->assertEquals('OUTSIDER', $outsider->name);
        $this->assertEquals('Outsider', $outsider->camel_name);
        $this->assertEquals(0, $outsider->ordinal);

        $this->assertEquals('Outsider', $outsider->label);
    }
}
