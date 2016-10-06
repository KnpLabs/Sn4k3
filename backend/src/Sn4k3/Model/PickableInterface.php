<?php

namespace Sn4k3\Model;

interface PickableInterface extends CollisionableInterface
{
    /**
     * @return void
     */
    public function onPick(Snake $snake): void;
}
