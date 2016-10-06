<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;

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

    public function __construct(Circle $circle)
    {
        $this->circle = $circle;
    }

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

    /**
     * {@inheritdoc}
     */
    public function onPick(Snake $snake): void
    {
        $snake->length += $this->value;
    }
}
