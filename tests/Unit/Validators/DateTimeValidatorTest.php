<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DateTimeValidatorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNormalUsage()
    {
        $data = [
            'a' => '2018-01-01',
            'b' => '2018-01-02',
            'c' => '2018-01-03',
            'd' => '2018-01-03',
        ];

        $validator = \Validator::make($data, [
            'a' => ['date','before_fields:b,c,d'],
            'b' => ['date','after_fields:a','before_fields:c,d'],
            'c' => ['date','after_fields:a,b','before_or_equal_fields:d'],
            'd' => ['date','after_or_equal_fields:a,b,c'],
        ]);

        $this->assertTrue($validator->passes());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNullValues()
    {
        $data = [
            'a' => '2018-01-01',
            'b' => null,
            'c' => '2018-01-03',
            'd' => null,
        ];

        $validator = \Validator::make($data, [
            'a' => ['date','before_fields:b,c,d'],
            'b' => ['nullable','date','after_fields:a','before_fields:c,d'],
            'c' => ['date','after_fields:a,b','before_or_equal_fields:d'],
            'd' => ['nullable','date','after_or_equal_fields:a,b,c'],
        ]);

        print_r($validator->errors());

        $this->assertTrue($validator->passes());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMissingFields()
    {
        $data = [
            'a' => '2018-01-01',
            'b' => '2018-01-02',
        ];

        $validator = \Validator::make($data, [
            'a' => ['date','before_fields:b,c'],
            'b' => ['date','after_fields:a','before_fields:c'],
            'c' => ['nullable','date','after_fields:a,b'],
        ]);

        $this->assertTrue($validator->passes());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFailing()
    {
        $data = [
            'a' => '2018-01-02',
            'b' => '2018-01-01',
        ];

        $validator = \Validator::make($data, [
            'a' => ['date','before_fields:b'],
            'b' => ['date','after_fields:a'],
        ]);

        print_r($validator->errors());

        $this->assertTrue($validator->fails());
    }
}
