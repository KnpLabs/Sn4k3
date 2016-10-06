<?php

namespace Sn4k3;

use React\EventLoop\Factory;
use Sn4k3\Socket\WebSocket;
use Thruway\ClientSession;

class Application
{
    const ACTION = 'action';
    const TICK = 'tick';
    const JOIN = 'join';

    private $loop;
    private $game;
    private $webSocket;

    public function __construct()
    {
        $this->loop = Factory::create();
        $this->game = new Game($this->loop);
        $this->webSocket = new WebSocket(7777, 'sn4k3', $this->loop);
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
            $session->subscribe(self::ACTION, function ($_, $args) {
                if (!isset($args->playerName) || !isset($args->direction)) {
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
            $session->subscribe(self::JOIN, function($_, $args) {
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
                $session->publish(self::TICK, null, $data);
            });
        });
    }
}
