<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;

interface CollisionableInterface
{
    /**
     * @return Circle
     */
    public function getCircleArea(): Circle;

    /**
     * @param CollisionableInterface $collisionable
     *
     * @return bool
     */
    public function collidesWith(CollisionableInterface $collisionable): bool;
}
