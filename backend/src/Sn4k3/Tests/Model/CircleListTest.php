<?php

namespace Sn4k3\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;
use Sn4k3\Geometry\Point;

class CircleListTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Objects added in circle list must extend Circle, "stdClass" given.
     */
    public function test accepts only circle objects()
    {
        $circleList = new CircleList();

        $circleList[] = new \stdClass();
    }

    public function test non existing returns null()
    {
        $circleList = new CircleList();

        static::assertNull($circleList['unexisting_key']);
    }

    public function test it accepts specified keys()
    {
        $circleList = new CircleList();

        $circle = new Circle(new Point(0, 0), 10);

        $circleList['key'] = $circle;

        static::assertEquals($circle, $circleList['key']);
    }
}
