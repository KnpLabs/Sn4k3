<?php

namespace Sn4k3\Geometry;

class Rectangle implements PhysicalObjectInterface
{
    /**
     * @var Point
     */
    public $northWest;

    /**
     * @var Point
     */
    public $southEast;

}
