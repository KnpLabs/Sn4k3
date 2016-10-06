<?php

namespace Sn4k3\Math;

use Sn4k3\Geometry\Circle;
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

        $distance = sqrt(pow($deltaX, 2) + pow($deltaY, 2));

        $radiusSum = $circleA->radius + $circleB->radius;

        return ($distance < $radiusSum);
    }
}
