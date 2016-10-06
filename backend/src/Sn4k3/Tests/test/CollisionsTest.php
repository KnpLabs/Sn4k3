<?php

use PHPUnit\Framework\TestCase;
use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;
use Sn4k3\Geometry\Point;
use Sn4k3\Math\CollisionsManager;
use Sn4k3\Model\Food;
use Sn4k3\Model\Snake;

class CollisionsTest extends TestCase
{
    public function test two distants circles dont collide()
    {
        $circleA = new Circle(new Point(0, 0), 10);
        $circleB = new Circle(new Point(100, 100), 10);

        $this->assertFalse(CollisionsManager::testCirclesCollision($circleA, $circleB));
    }

    public function test two near circles do collide()
    {
        $circleA = new Circle(new Point(0, 0), 10);
        $circleB = new Circle(new Point(12, 12), 10);

        $this->assertTrue(CollisionsManager::testCirclesCollision($circleA, $circleB));
    }

    public function test snake and far food dont collide()
    {
        $snake = new Snake(new CircleList([
            new Circle(new Point(0, 0), 5),
            new Circle(new Point(-4, -4), 5),
        ]));
        $food = new Food(new Circle(new Point(20, 20), 10));

        $this->assertFalse(CollisionsManager::testCollisionablesCollision($snake, $food));
    }

    public function test snake and near food do collide()
    {
        $snake = new Snake(new CircleList([
            new Circle(new Point(0, 0), 5),
            new Circle(new Point(-4, -4), 5),
        ]));
        $food = new Food(new Circle(new Point(6, 6), 10));

        $this->assertTrue(CollisionsManager::testCollisionablesCollision($snake, $food));
    }

    public function test close snakes do collide()
    {
        $snakeA = new Snake(new CircleList([
            new Circle(new Point(0, 0), 5),
            new Circle(new Point(-4, -4), 5),
        ]));
        $snakeB = new Snake(new CircleList([
            new Circle(new Point(2, 2), 5),
            new Circle(new Point(6, 6), 5),
        ]));

        $this->assertTrue(CollisionsManager::testCollisionablesCollision($snakeA, $snakeB));
    }
}
