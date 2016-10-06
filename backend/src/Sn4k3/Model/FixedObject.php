<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\CircleList;

class FixedObject implements CollisionableInterface
{
    /**
     * @var CircleList
     */
    public $circle;

    /**
     * {@inheritdoc}
     */
    public function getCircleList(): CircleList
    {
        return new CircleList([$this->circle]);
    }

    /**
     * {@inheritdoc}
     */
    public function collidesWith(CollisionableInterface $collisionable): bool
    {
        // TODO: Implement collides() method.
    }
}
