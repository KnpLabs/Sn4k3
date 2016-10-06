<?php

namespace Sn4k3\Geometry;

class Circle
{
    /**
     * @var Point
     */
    public $centerPoint;

    /**
     * @var int
     */
    public $radius;

    public function __construct(Point $centerPoint, int $radius)
    {
        $this->centerPoint = $centerPoint;
        $this->radius = $radius;
    }
}
