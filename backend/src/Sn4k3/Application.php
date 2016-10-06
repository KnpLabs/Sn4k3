<?php

namespace Sn4k3;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Sn4k3\Socket\WebSocket;
use Thruway\ClientSession;

class Application
{
    const CROSSBAR_WEBSOCKET_PORT = 7777;
    const CROSSBAR_WEBSOCKET_PATH = 'sn4k3';

    const EVENT_INCOMING_ACTION = 'action';
    const EVENT_INCOMING_JOIN = 'join';
    const EVENT_OUTGOING_TICK = 'tick';

    /**
     * @var LoopInterface
     */
    private $loop;
    
    /**
     * @var Game
     */
    private $game;
    
    /**
     * @var WebSocket
     */
    private $webSocket;

    public function __construct()
    {
        $this->loop = Factory::create();
        $this->game = new Game($this->loop);
        $this->webSocket = new WebSocket(self::CROSSBAR_WEBSOCKET_PORT, self::CROSSBAR_WEBSOCKET_PATH, $this->loop);

        $this->listenIncomingMessages();
        $this->broadcastTick();
        $this->listenForNewPlayers();

        $this->webSocket->start();
        $this->game->run();
        $this->loop->run();
    }

    private function listenIncomingMessages()
    {
        $promise = $this->webSocket->promiseSession();

        $promise->then(function (ClientSession $session) {
            $session->subscribe(self::EVENT_INCOMING_ACTION, function ($_, $args) {
                if (!isset($args->playerName, $args->direction)) {
                    return;
                }

                $event = new Event();
                $event->player = $this->game->getPlayerByName($args->playerName);
                $event->direction = $args->direction;

                $this->game->addEvent($event);
            });
        });
    }

    private function listenForNewPlayers()
    {
        $this->webSocket->promiseSession()->then(function (ClientSession $session) {
            $session->subscribe(self::EVENT_INCOMING_JOIN, function($_, $args) {
                if (!isset($args->playerName) || strlen($args->playerName) < 3) {
                    return;
                }

                $this->game->initializePlayer($args->playerName);
            });
        });
    }

    private function broadcastTick()
    {
        $this->game->on(Game::EVENT_TICK, function () {
            $this->webSocket->promiseSession()->then(function (ClientSession $session) {
                $data = Serializer::serializeGame($this->game);
                $session->publish(self::EVENT_OUTGOING_TICK, null, $data);
            });
        });
    }
}
