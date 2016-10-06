<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\CircleList;

class Snake implements CollisionableInterface
{
    /**
     * @var Player
     */
    public $player;

    /**
     * First body part is the head, last is the tail.
     *
     * @var CircleList
     */
    public $bodyParts;

    /**
     * Number of body parts.
     * If > count(bodyParts), it means we have to create a new bodyPart.
     *
     * @var int
     */
    public $length;

    /**
     * From 0 to 360°
     *
     * @var int
     */
    public $headAngle;

    public function __construct()
    {
        $this->bodyParts = new CircleList();
    }

    /**
     * {@inheritdoc}
     */
    public function getCircleList(): CircleList
    {
        return $this->bodyParts;
    }

    /**
     * @param CollisionableInterface $collisionable
     *
     * @return bool
     */
    public function collidesWith(CollisionableInterface $collisionable): bool
    {
        // TODO: Implement collidesWith() method.
    }
}
