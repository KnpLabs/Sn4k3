<?php

namespace Sn4k3\Model;

interface PickableInterface extends CollisionableInterface
{
    /**
     * @param Snake $snake
     *
     * @return void
     */
    public function onPick(Snake $snake);
}
