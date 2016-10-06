<?php

namespace Sn4k3;

use Evenement\EventEmitterTrait;
use React\EventLoop\LoopInterface;
use Sn4k3\Model\Player;

class Game
{
    const EVENT_TICK = 'event_tick';

    use EventEmitterTrait;

    /**
     * In milliseconds.
     *
     * @var int
     */
    private $tickInterval = 1000;

    /**
     * @var Player[]
     */
    private $players = [];

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var Event[]
     */
    private $awaitingEvents = [];

    /**
     * @var bool
     */
    private $isRunning = false;

    /**
     * @param LoopInterface $loop
     * @param int|null $tickInterval
     */
    public function __construct(LoopInterface $loop, $tickInterval = null)
    {
        $this->loop = $loop;

        if (null !== $tickInterval) {
            $this->tickInterval = (int) $tickInterval;
        }
    }

    /**
     * Run the loop for the game to start.
     */
    public function run()
    {
        if ($this->isRunning) {
            throw new \LogicException('Game is already running');
        }

        // Execute $this->tick() on every tick interval
        $this->loop->addPeriodicTimer($this->tickInterval / 1000, [$this, 'tick']);
        $this->isRunning = true;
    }

    /**
     * Execute every action while ticking:
     */
    public function tick()
    {
        while ($event = array_shift($this->awaitingEvents)) {
            echo sprintf(
                'Player %s changed direction to %s',
                $event->player, $event->direction
            ), PHP_EOL;
        }

        foreach ($this->players as $player) {
            $player->makeMove();
        }

        echo 'I am a tick, please implement me', PHP_EOL;

        $this->emit(self::EVENT_TICK, [$this]);
    }

    /**
     * @param Event $event
     */
    public function addEvent(Event $event)
    {
        $this->awaitingEvents[] = $event;
    }

    /**
     * @param string $name
     * @param string $direction
     */
    public function changeDirection(string $name, $direction)
    {
        $player = $this->getPlayerByName($name);

        if (!$player->canChangeDirection($direction)) {
            throw new \InvalidArgumentException('Come on, you are snek, you cannot move towards your back.');
        }

        $player->changeDirection($direction);
    }

    /**
     * @param string $name
     */
    public function initializePlayer(string $name)
    {
        $playerExists = $this->getPlayerByName($name, false);

        if ($playerExists) {
            return;
        }

        $player = new Player();
        $player->hash = substr(md5(random_bytes(64)), 0, 16);
        $player->name = $name;

        $this->players[$player->hash] = $player;
    }

    /**
     * @param string $name
     *
     * @return Player
     */
    public function getPlayerByName(string $name, $exceptional = true): Player
    {
        foreach ($this->players as $player) {
            if ($player->name === $name) {
                return $player;
            }
        }

        if ($exceptional) {
            throw new \InvalidArgumentException('No such player');
        }
    }

    /**
     * @param $hash
     *
     * @return Player
     */
    public function getPlayerByHash(string $hash): Player
    {
        if (array_key_exists($hash, $this->players)) {
            return $this->players[$hash];
        }

        throw new \InvalidArgumentException('No such player');
    }

    /**
     * @return Model\Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }
}
