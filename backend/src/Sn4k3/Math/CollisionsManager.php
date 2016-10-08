<?php

namespace Sn4k3\Math;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\Rectangle;
use Sn4k3\Model\CollisionableInterface;

final class CollisionsManager
{
    /**
     * Checks if there's a collision between any couple of circles between two CollisionableInterface.
     *
     * @param CollisionableInterface $objectA
     * @param CollisionableInterface $objectB
     *
     * @return bool
     */
    public static function testCollisionablesCollision(CollisionableInterface $objectA, CollisionableInterface $objectB): bool
    {
        foreach ($objectA->getCircleList() as $currentACircle) {
            foreach ($objectB->getCircleList() as $currentBCircle) {
                if (self::testCirclesCollision($currentACircle, $currentBCircle)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Test collision between two circles only.
     *
     * @param Circle $circleA
     * @param Circle $circleB
     *
     * @return bool
     */
    public static function testCirclesCollision(Circle $circleA, Circle $circleB): bool
    {
        $deltaX = $circleA->centerPoint->x - $circleB->centerPoint->x;
        $deltaY = $circleA->centerPoint->y - $circleB->centerPoint->y;

        // First naive guess.
        if ($deltaX > 75 || $deltaY > 75) {
            return false;
        }

        // Square test (less performance consuming)
        $rect1 = Rectangle::createFromCircle($circleA);
        $rect2 = Rectangle::createFromCircle($circleB);
        if (!static::testRectanglesCollision($rect1, $rect2)) {
            return false;
        }

        $distance = sqrt(pow($deltaX, 2) + pow($deltaY, 2));

        $radiusSum = $circleA->radius + $circleB->radius;

        return ($distance < $radiusSum);
    }

    /**
     * @param Rectangle $rectangleA
     * @param Rectangle $rectangleB
     *
     * @return bool
     */
    public static function testRectanglesCollision(Rectangle $rectangleA, Rectangle $rectangleB): bool
    {
        return
            $rectangleA->x < $rectangleB->x + $rectangleB->width &&
            $rectangleA->x + $rectangleA->width > $rectangleB->x &&
            $rectangleA->y < $rectangleB->y + $rectangleB->height &&
            $rectangleA->height + $rectangleA->y > $rectangleB->y
        ;
    }
}
