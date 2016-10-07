<?php

namespace Sn4k3\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\Point;
use Sn4k3\Model\Food;
use Sn4k3\Model\Map;
use Sn4k3\Model\Player;
use Sn4k3\Model\Snake;

class FoodTest extends TestCase
{
    public function test picking food()
    {
        $map = new Map();

        $food = new Food(new Circle(new Point(0, 0), 0));
        $map->foods[] = $food;

        $snake = new Snake($map);
        $snake->length = 1;

        $food->onPick($snake);

        static::assertEmpty($map->foods);
        static::assertEquals(6, $snake->length);
    }
}
