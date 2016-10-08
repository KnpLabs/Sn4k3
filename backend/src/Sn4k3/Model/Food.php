<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;

class Food implements PickableInterface
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
    public function onPick(Snake $snake)
    {
        // Increase snake's length.
        $snake->grow($this->value);

        // Search food object in map.
        $foodIndex = array_search($this, $snake->map->foods, true);

        // Remove food object from the map.
        unset($snake->map->foods[$foodIndex]);
    }
}
