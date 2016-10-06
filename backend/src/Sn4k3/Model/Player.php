<?php

namespace Sn4k3\Model;

class Player
{
    /**
     * @var string
     */
    public $hash;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Snake
     */
    public $snake;

    /**
     * @var int
     */
    public $score;

    public function canChangeDirection($direction)
    {
        switch ($direction) {
            case 'up':
                return $this->snake->direction !== 'down';

            case 'down':
                return $this->snake->direction !== 'up';

            case 'left':
                return $this->snake->direction !== 'right';

            case 'right':
                return $this->snake->direction !== 'left';

            default:
                return false;
        }
    }

    public function changeDirection($direction)
    {
        if ($this->canChangeDirection($direction)) {
            $this->snake->direction = $direction;
        }
    }

    public function makeMove()
    {

    }
}
