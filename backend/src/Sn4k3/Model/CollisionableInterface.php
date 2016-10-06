<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\CircleList;

interface CollisionableInterface
{
    /**
     * @return CircleList
     */
    public function getCircleList(): CircleList;

    /**
     * @param CollisionableInterface $collisionable
     *
     * @return bool
     */
    public function collidesWith(CollisionableInterface $collisionable): bool;
}
