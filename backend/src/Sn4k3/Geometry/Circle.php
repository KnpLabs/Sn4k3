<?php

namespace Sn4k3\Geometry;

class Circle
{
    const DEFAULT_RADIUS = 10;

    /**
     * @var Point
     */
    public $centerPoint;

    /**
     * @var int
     */
    public $radius;

    /**
     * @param Point $centerPoint
     * @param int $radius
     */
    public function __construct(Point $centerPoint, int $radius = self::DEFAULT_RADIUS)
    {
        $this->centerPoint = $centerPoint;
        $this->radius = $radius;
    }
}
