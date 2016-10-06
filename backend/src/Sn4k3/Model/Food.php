<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;

class Food implements PickableInterface
{
    /**
     * @var Circle
     */
    public $circle;

    /**
     * @var int
     */
    public $value;

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

    /**
     * {@inheritdoc}
     */
    public function onPick(Snake $snake): void
    {
        $snake->length += $this->value;
    }
}
