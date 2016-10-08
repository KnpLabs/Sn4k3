<?php

namespace Sn4k3\Model;

use Sn4k3\Collision\AbstractCollisonable;
use Sn4k3\Collision\CollisionableInterface;
use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;

class Food extends AbstractCollisonable
{
    const DEFAULT_VALUE = 5;

    /**
     * @var Circle
     */
    public $circle;

    /**
     * @var int
     */
    public $value;

    /**
     * @var int
     */
    public $lifetime;

    /**
     * @var int
     */
    public $type;

    public function __construct(Circle $circle, int $value = self::DEFAULT_VALUE)
    {
        $this->circle = $circle;
        $this->value = $value;
        $this->type = random_int(0, 11);
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
    public function collide(CollisionableInterface $target)
    {
        if (!$target instanceof Snake) {
            return;
        }

        // Increase snake's length.
        $target->grow($this->value);

        $target->player->score += $this->value;

        // Search food object in map.
        $foodIndex = array_search($this, $target->map->foods, true);

        // Remove food object from the map.
        unset($target->map->foods[$foodIndex]);
    }
}
