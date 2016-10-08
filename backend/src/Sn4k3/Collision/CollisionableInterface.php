<?php

namespace Sn4k3\Collision;

use Sn4k3\Geometry\CircleList;

interface CollisionableInterface
{
    /**
     * @return CircleList
     */
    public function getCircleList(): CircleList;

    /**
     * @param CollisionableInterface $target
     */
    public function collide(CollisionableInterface $target);
}
