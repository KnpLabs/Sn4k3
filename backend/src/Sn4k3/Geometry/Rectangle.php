<?php

namespace Sn4k3\Geometry;

class Rectangle
{
    /**
     * @var int
     */
    public $x;

    /**
     * @var int
     */
    public $y;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     */
    public function __construct(int $x, int $y, int $width, int $height)
    {
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    public static function createFromCircle(Circle $circle): Rectangle
    {
        return new self(
            $circle->centerPoint->x - $circle->radius,
            $circle->centerPoint->y - $circle->radius,
            $circle->radius * 2,
            $circle->radius * 2
        );
    }
}
