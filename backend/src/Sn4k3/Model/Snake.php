<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;
use Sn4k3\Math\CollisionsManager;

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

    /**
     * Up, down, left, right
     *
     * @var string
     */
    public $direction;

    public function __construct(CircleList $bodyParts)
    {
        $this->bodyParts = $bodyParts;
    }

    /**
     * {@inheritdoc}
     */
    public function getCircleList(): CircleList
    {
        return $this->bodyParts;
    }

    public function getHead(): Circle
    {
        return $this->bodyParts[0];
    }

    /**
     * @param CollisionableInterface $collisionable
     *
     * @return bool
     */
    public function collidesWith(CollisionableInterface $collisionable): bool
    {
        $myHead = $this->getHead();

        return CollisionsManager::testCollisionablesCollision([$myHead], $collisionable);
    }
}
