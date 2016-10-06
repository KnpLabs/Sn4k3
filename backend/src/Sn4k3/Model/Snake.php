<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;
use Sn4k3\Geometry\Point;

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
     * From 0 to 360°.
     * Represents the clock.
     *
     * @var int
     */
    public $headAngle = 0;

    /**
     * Up, down, left, right
     *
     * @var string
     */
    public $direction;

    /**
     * The amount of pixels crossed on each tick.
     *
     * @var int
     */
    public $speed = 10;

    public function __construct($speed = null, $headAngle = null)
    {
        $this->bodyParts = new CircleList();

        if (null !== $speed) {
            $this->speed = $speed;
        }

        if (null !== $headAngle) {
            $this->headAngle = $headAngle;
        }
    }

    /**
     * @param Circle $circle
     */
    public function addBodyPart(Circle $circle)
    {
        $this->bodyParts->add($circle);
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

    /**
     * Using trigonometry to get coordinates of the next point.
     */
    public function calculateNextCoordinatePoint(): Point
    {
        $headPoint = $this->getCircleList()->first()->centerPoint;

        $angleInRadians = deg2rad($this->headAngle);

        $y = $headPoint->y + ($this->speed * cos($angleInRadians));
        $x = $headPoint->x + ($this->speed * sin($angleInRadians));

        return new Point(round($x), round($y));
    }
}
