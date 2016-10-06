<?php

namespace Sn4k3\Model;

class Snake
{
    /**
     * @var Player
     */
    private $player;

    /**
     * First body part is the head, last is the tail.
     *
     * @var SnakeBodypart[]
     */
    private $bodyParts;

    /**
     * Number of body parts.
     * If > count(bodyParts), it means we have to create a new bodyPart.
     *
     * @var int
     */
    private $length;

    /**
     * From 0 to 360°
     * @var float
     */
    private $headAngle;
}
