<?php

namespace Sn4k3\Model;

class Snake
{
    /**
     * @var Player
     */
    public $player;

    /**
     * First body part is the head, last is the tail.
     *
     * @var SnakeBodypart[]
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
     * From 0 to 360°
     *
     * @var int
     */
    public $headAngle;

    /**
     * Up, down, left, right
     *
     * @var string
     */
    public $direction;
}
