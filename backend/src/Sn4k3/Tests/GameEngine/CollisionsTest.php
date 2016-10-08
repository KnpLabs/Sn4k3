<?php

namespace Sn4k3\Tests\GameEngine;

use PHPUnit\Framework\TestCase;
use Sn4k3\Model\Player;
use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\Point;
use Sn4k3\Math\CollisionsManager;
use Sn4k3\Model\FixedObject;
use Sn4k3\Model\Food;
use Sn4k3\Model\Map;
use Sn4k3\Model\Snake;

class CollisionsTest extends TestCase
{
    public function test two distants circles dont collide()
    {
        $circleA = new Circle(new Point(0, 0));
        $circleB = new Circle(new Point(100, 100));

        static::assertFalse(CollisionsManager::testCirclesCollision($circleA, $circleB));
    }

    public function test two near circles do collide()
    {
        $circleA = new Circle(new Point(0, 0));
        $circleB = new Circle(new Point(12, 12));

        static::assertTrue(CollisionsManager::testCirclesCollision($circleA, $circleB));
    }

    public function test snake and far food dont collide()
    {
        $map = new Map();
        $snake = new Snake($map, new Player($map));
        $snake->appendBodyPart(new Circle(new Point(0, 0), 5));
        $snake->appendBodyPart(new Circle(new Point(-4, -4), 5));

        $food = new Food(new Circle(new Point(20, 20), 10));

        static::assertFalse(CollisionsManager::testCollisionablesCollision($snake, $food));
    }

    public function test snake and near food do collide()
    {
        $map = new Map();
        $snake = new Snake($map, new Player($map));
        $snake->appendBodyPart(new Circle(new Point(0, 0), 5));
        $snake->appendBodyPart(new Circle(new Point(-4, -4), 5));

        $food = new Food(new Circle(new Point(6, 6), 10));

        static::assertTrue(CollisionsManager::testCollisionablesCollision($snake, $food));
    }

    public function test snake and fixed object do collide()
    {
        $map = new Map();
        $snake = new Snake($map, new Player($map));
        $snake->appendBodyPart(new Circle(new Point(0, 0), 5));
        $snake->appendBodyPart(new Circle(new Point(-4, -4), 5));

        $fixedObject = new FixedObject(new Circle(new Point(6, 6)));

        static::assertTrue(CollisionsManager::testCollisionablesCollision($snake, $fixedObject));
    }

    public function test close snakes do collide()
    {
        $map = new Map();
        $snakeA = new Snake($map, new Player($map));
        $snakeA->appendBodyPart(new Circle(new Point(0, 0), 5));
        $snakeA->appendBodyPart(new Circle(new Point(-4, -4), 5));

        $map = new Map();
        $snakeB = new Snake($map, new Player($map));
        $snakeB->appendBodyPart(new Circle(new Point(2, 2), 5));
        $snakeB->appendBodyPart(new Circle(new Point(6, 6), 5));

        static::assertTrue(CollisionsManager::testCollisionablesCollision($snakeA, $snakeB));
    }
}
