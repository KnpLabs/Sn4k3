<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Point;

interface CollisionableInterface
{
    /**
     * @return Point
     */
    public function getPosition(): Point;

    /**
     * @param CollisionableInterface $collisionable
     *
     * @return bool
     */
    public function collides(CollisionableInterface $collisionable): bool;
}
