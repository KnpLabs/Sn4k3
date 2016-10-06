<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;

class FixedObject implements CollisionableInterface
{
    /**
     * @var Circle
     */
    public $circle;

    /**
     * {@inheritdoc}
     */
    public function getCircleArea(): Circle
    {
        return $this->circle;
    }

    /**
     * {@inheritdoc}
     */
    public function collidesWith(CollisionableInterface $collisionable): bool
    {
        // TODO: Implement collides() method.
    }
}
