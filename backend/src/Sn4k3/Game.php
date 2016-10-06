<?php

namespace Sn4k3;

use React\EventLoop\LoopInterface;
use Sn4k3\Model\Player;

class Game
{
    /**
     * In milliseconds, corresponds to the tick interval.
     *
     * @var int
     */
    private $tickInterval = 1000;

    /**
     * @var array
     */
    private $players = [];

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var array
     */
    private $awaitingEvents = [];

    /**
     * @var bool
     */
    private $isRunning = false;

    public function __construct(LoopInterface $loop, $tickInterval = null)
    {
        $this->loop = $loop;
        if (null !== $tickInterval) {
            $this->tickInterval = (int) $tickInterval;
        }
    }

    public function run()
    {
        if ($this->isRunning) {
            throw new \LogicException('Game is already running');
        }

        $this->loop->addPeriodicTimer($this->tickInterval / 1000, [$this, 'tick']);
        $this->isRunning = true;
        $this->loop->run();
    }

    public function tick()
    {
        while ($event = array_shift($awaitingEvents)) {
            var_dump(sprintf('Player %s changed direction to %s'));
        }

        foreach ($this->players as $player) {
            $player->makeMove();
        }

        var_dump('I am a tick, please implement me');
    }

    public function addEvent(Event $event)
    {
        $this->awaitingEvents[] = $event;
    }

    public function changeDirection($name, $direction)
    {
        $player = $this->getPlayerByName($name);

        if (!$player->canChangeDirection($direction)) {
            throw new \InvalidArgumentException('Come on, you are snek, you cannot move towards your back.');
        }

        $player->changeDirection($direction);
    }

    public function initializePlayer($name)
    {
        $player = new Player();
        $player->hash = substr(md5(random_bytes(64)), 0, 16);
        $player->name = $name;

        $this->players[$player->hash] = $player;
    }

    public function getPlayerByName($name) : Player
    {
        foreach ($this->players as $player) {
            if ($player->name === $name) {
                return $player;
            }
        }

        throw new \InvalidArgumentException('No such player');
    }

    public function getPlayerByHash($hash)
    {
        if (isset($this->players[$hash])) {
            return $this->players[$hash];
        }

        throw new \InvalidArgumentException('No such player');
    }
}
