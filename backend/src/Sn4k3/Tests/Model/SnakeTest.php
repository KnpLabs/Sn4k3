<?php

namespace Sn4k3\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\Point;
use Sn4k3\Model\Map;
use Sn4k3\Model\Player;
use Sn4k3\Model\Snake;

class SnakeTest extends TestCase
{
    /**
     * @dataProvider provideAngles
     *
     * @param int $angle
     * @param int $expectedX
     * @param int $expectedY
     */
    public function test calculation of next point(int $angle, int $expectedX, int $expectedY)
    {
        $map = new Map();
        $snake = new Snake($map, new Player($map));
        $snake->length = 1;
        $snake->headAngle = $angle;
        $snake->getCircleList()->prepend(new Circle(new Point(0, 0), 5));

        $resultPoint = $snake->calculateNextCoordinatePoint();

        static::assertEquals($resultPoint->x, $expectedX, 'Expected X is invalid');
        static::assertEquals($resultPoint->y, $expectedY, 'Expected Y is invalid');
    }

    /**
     * Turns all around the circle clockwise, based on {0,0} coordinates.
     *
     * @return array
     */
    public function provideAngles()
    {
        return [
            0 => [0,     0,  10],
            1 => [45,    7,   7],
            2 => [90,   10,   0],
            3 => [135,   7,  -7],
            4 => [180,   0, -10],
            5 => [225,  -7,  -7],
            6 => [270, -10,   0],
            7 => [305,  -8,   6],
            8 => [360,   0,  10],
        ];
    }
}
