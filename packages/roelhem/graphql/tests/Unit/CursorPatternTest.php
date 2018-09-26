<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 21:25
 */

namespace Roelhem\GraphQL\Tests\Unit;


use Carbon\Carbon;
use Roelhem\GraphQL\Paginators\Cursor;
use Roelhem\GraphQL\Paginators\CursorPattern;
use Roelhem\GraphQL\Tests\TestCase;

class CursorPatternTest extends TestCase
{

    public function testPacking() {

        $carbon = Carbon::now();

        $cursor = new Cursor([
            'string' => 'hallo',
            'integer' => 123,
            'boolean' => false,
            'carbon' => $carbon,
        ]);

        $pattern = new CursorPattern([
            'carbon' => 'datetime',
            'integer' => 'i'
        ]);

        $res = $pattern->pack($cursor);

        $this->assertEquals(pack('ii',$carbon->timestamp, 123),$res);
        $this->assertNotEquals(pack('ii', 123, $carbon->timestamp),$res);

        $unpacked = $pattern->unpack($res);

        $this->assertEquals(123, $unpacked['integer']);
        $this->assertInstanceOf(Carbon::class, $unpacked['carbon']);
        $this->assertEquals(0, $carbon->diffInSeconds($unpacked['carbon']));
    }

    public function testSerializing() {
        $now = Carbon::now();

        $cursor1 = new Cursor([
            'date' => $now,
            'id' => 1,
        ]);

        $cursor2 = new Cursor([
            'date' => $now,
            'id' => 2,
        ]);

        $pattern = new CursorPattern([
            'date' => 'datetime',
            'id' => 'n',
        ]);

        $s1 = $pattern->serialize($cursor1);
        $s2 = $pattern->serialize($cursor2);

        var_dump($s1);
        var_dump($s2);

        var_dump($pattern->serialize(new Cursor([
            'date' => Carbon::createFromTimestamp(2542643643),
            'id' => 154365,
        ])));

        $this->assertNotEquals($s1, $s2);
        $this->assertEquals($pattern->serialize($cursor1), $s1);

    }

}