<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;
use Sn4k3\Geometry\Point;
use Sn4k3\Math\CollisionsManager;

class Snake implements CollisionableInterface
{
    /**
     * Angles are in degrees.
     */
    const DEFAULT_HEAD_ANGLE = 0;
    const DEFAULT_HEAD_ANGLE_TICK = 10;
    const DEFAULT_AUTOGROW_NUMBER = 5;

    /**
     * In pixels.
     */
    const DEFAULT_SPEED = 10;

    /**
     * @var Player
     */
    public $player;

    /**
     * Number of body parts.
     * If > count(bodyParts), it means we have to create a new bodyPart.
     *
     * @var int
     */
    public $length = 1;

    /**
     * From 0 to 360°.
     * Represents the clock.
     *
     * @var int
     */
    public $headAngle;

    /**
     * Left, right
     *
     * @var string
     */
    public $direction;

    /**
     * The amount of pixels crossed on each tick.
     *
     * @var int
     */
    public $speed;

    /**
     * @var Map
     */
    public $map;

    /**
     * First body part is the head, last is the tail.
     *
     * @var CircleList
     */
    protected $bodyParts;

    public function __construct(Map $map, Player $player, int $speed = self::DEFAULT_SPEED, int $headAngle = self::DEFAULT_HEAD_ANGLE)
    {
        $this->bodyParts = new CircleList();
        $this->player = $player;
        $this->bodyParts[] = new Circle(new Point(0, 0));
        $this->speed = $speed;
        $this->headAngle = $headAngle;
        $this->map = $map;
    }

    /**
     * @param Circle $circle
     */
    public function appendBodyPart(Circle $circle)
    {
        $this->bodyParts->append($circle);
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
        return $this->bodyParts->first();
    }

    public function getTail(): Circle
    {

        return $this->bodyParts->last();
    }

    /**
     * Using trigonometry to get coordinates of the next point.
     */
    public function calculateNextCoordinatePoint(): Point
    {
        $headPoint = $this->getHead()->centerPoint;

        $angleInRadians = deg2rad($this->headAngle);

        $y = $headPoint->y + ($this->speed * cos($angleInRadians));
        $x = $headPoint->x + ($this->speed * sin($angleInRadians));

        var_dump(($this->speed * cos($angleInRadians)));
        var_dump($headPoint->y);

        return new Point(round($x), round($y));
    }

    /**
     * 1. Check head angle.
     * 2. Calculate next point.
     * 3. Evaluate collisions
     * 3. a. Handle collisions with Food
     * 3. b. Handle game over on collisions with fixed objects, walls and other snakes.
     * 4. Move the snake if he can, else, send game over!
     *
     * @return bool
     */
    public function move()
    {
        if ($this->length < self::DEFAULT_AUTOGROW_NUMBER) {
            $this->length++;
        }

        $this->changeHeadAngle();
        $this->checkFoodCollisions();
        $collides = $this->checkOtherCollisions();

        if (!$collides) {
            $newPoint = $this->calculateNextCoordinatePoint();
            $numberOfBodyParts = $this->bodyParts->count();

            //var_dump($newPoint);

            // Handle body parts movement.
            if ($this->length > $numberOfBodyParts) {
                // If we have more length than actual body parts,
                //  the "head" becomes a new point.
                $this->bodyParts->prepend(new Circle($newPoint));
            } elseif ($this->length === $numberOfBodyParts) {
                // If length is same as body parts count,
                //  we just move the tail to the beginning of the snake,
                //  so the snake looks like it's moving, but we just moved one circle.
                $this->bodyParts->pop();
                $this->bodyParts->prepend(new Circle($newPoint));
            }

            // Has moved successfully.
            return true;
        }

        // Has failed to move because it collided with a physical object.
        return false;
    }

    /**
     * Change the head angle based on current's snake direction,
     *  but only if a key is pressed by the player.
     *
     * @return true
     */
    public function changeHeadAngle()
    {
        var_dump($this);
        if ($this->player->keyPressed) {
            // Will help handle positive or negative angles.
            $directionRatio = $this->direction === Player::DIRECTION_LEFT ? -1 : 1;

            $this->headAngle += ($directionRatio * self::DEFAULT_HEAD_ANGLE_TICK);

            var_dump($this->headAngle);

            return true;
        }

        return false;
    }

    /**
     * If snake has a collision with food, we run the onPick method.
     *
     * @return bool
     */
    public function checkFoodCollisions()
    {
        foreach ($this->map->foods as $k => $food) {
            if (CollisionsManager::testCollisionablesCollision($this, $food)) {
                $food->onPick($this);

                return true;
            }
        }

        return false;
    }

    /**
     * Will check if collides with snakes or fixed objects.
     *
     * @return bool
     */
    public function checkOtherCollisions()
    {
        foreach ($this->map->snakes as $snake) {
            if (CollisionsManager::testCollisionablesCollision($this, $snake)) {
                return true;
            }
        }

        return false;
    }
}
