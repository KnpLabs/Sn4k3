<?php

namespace Sn4k3\Collision;

abstract class AbstractCollisonable implements CollisionableInterface
{
    public function collide(CollisionableInterface $target)
    {
        // By default, do nothing.
    }
}
