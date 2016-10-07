<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;
use Sn4k3\Geometry\Point;
use Sn4k3\Math\CollisionsManager;

class Snake implements CollisionableInterface
{
    const DEFAULT_HEAD_ANGLE = 0; // In degrees.
    const DEFAULT_HEAD_ANGLE_TICK = 10; // In degrees.
    const DEFAULT_SPEED = 10; // In pixels.
    const DEFAULT_LENGTH = 35; // In terms of "body parts"

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
     * @var bool
     */
    public $destroyed = false;

    /**
     * First body part is the head, last is the tail.
     *
     * @var CircleList
     */
    protected $bodyParts;

    public function __construct(Map $map, Player $player, int $speed = self::DEFAULT_SPEED, int $headAngle = self::DEFAULT_HEAD_ANGLE)
    {
        $randomPosition = new Circle(new Point(
            random_int(-300, 300),
            random_int(-300, 300)
        ));

        $randomAngle = random_int(0, 360);

        $this->bodyParts = new CircleList();
        $this->player = $player;
        $this->bodyParts[] = $randomPosition;
        $this->speed = $speed;
        $this->headAngle = $headAngle !== 0 ?: $randomAngle;
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
        if ($this->length < self::DEFAULT_LENGTH) {
            $this->length++;
        }

        $this->changeHeadAngle();
        $this->checkFoodCollisions();
        $collides = $this->checkOtherCollisions();

        if (!$collides) {
            $newPoint = $this->calculateNextCoordinatePoint();
            $numberOfBodyParts = $this->bodyParts->count();

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
        if ($this->player->keyPressed) {
            // Will help handle positive or negative angles.
            $directionRatio = $this->direction === Player::DIRECTION_LEFT ? 1 : -1;

            $this->headAngle += ($directionRatio * self::DEFAULT_HEAD_ANGLE_TICK);

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
     * Checks if the snake's head is colliding with any other snake.
     *
     * @return bool
     */
    public function checkOtherCollisions()
    {
        foreach ($this->map->snakes as $snake) {
            if ($snake !== $this) {
                // Handle "other" snakes first.
                $headCircleList = new CircleList([$this->getHead()]);
                if (CollisionsManager::testCollisionablesCollision($headCircleList, $snake)) {
                    echo sprintf(
                        '%s is colliding with %s',
                        $this->player->name, $snake->player->name
                    ), PHP_EOL;
                    return true;
                }
            } else {
                // Handle self-collision.
                // In this case, we remove the head and check collision between head & other parts of the body
                $head = $this->getHead();
                $headCircleList = new CircleList([$head]);

                $body = new CircleList();

                // Skip a fixed number of body parts to avoid constant collision.
                $numberOfBodyPartsToSkip = 3;
                $i = 0;
                foreach ($this->bodyParts as $part) {
                    if ($i < $numberOfBodyPartsToSkip) {
                        $i++;
                        continue;
                    }
                    $body->append($part);
                }

                if (CollisionsManager::testCollisionablesCollision($headCircleList, $body)) {
                    echo sprintf(
                        '%s is colliding with %s',
                        $this->player->name, $snake->player->name
                    ), PHP_EOL;
                    return true;
                }
            }
        }

        return false;
    }
}
