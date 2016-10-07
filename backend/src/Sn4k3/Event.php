<?php

namespace Sn4k3;

use Sn4k3\Model\Player;

class Event
{
    /**
     * @var Player
     */
    protected $player;

    /**
     * @var string
     */
    protected $direction;

    /**
     * @var bool
     */
    protected $keyPressed;

    public function __construct(Player $player, string $direction, bool $keyPressed)
    {
        $this->player = $player;
        $this->direction = $direction;
        $this->keyPressed = $keyPressed;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return boolean
     */
    public function isKeyPressed(): bool
    {
        return $this->keyPressed;
    }
}
