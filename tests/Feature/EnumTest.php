<?php

namespace Tests\Feature;

use App\Enums\OAuthProviders;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnumTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

        $values = OAuthProviders::asOrderedArray('social');

        print_r($values);

        $this->assertTrue(true);
    }
}
