<?php

namespace Sn4k3\Model;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;

class FixedObject implements CollisionableInterface
{
    /**
     * @var CircleList
     */
    public $circle;

    public function __construct(Circle $circle)
    {
        $this->circle = $circle;
    }

    /**
     * {@inheritdoc}
     */
    public function getCircleList(): CircleList
    {
        return new CircleList([$this->circle]);
    }

}
