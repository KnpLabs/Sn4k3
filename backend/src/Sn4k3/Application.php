<?php

namespace Sn4k3;

use React\EventLoop\Factory;
use Sn4k3\Socket\WebSocket;
use Thruway\ClientSession;

class Application
{
    const ACTION = 'action';
    const TICK = 'tick';

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

        $this->game->run();
        $this->loop->run();
    }

    private function listenIncomingMessages()
    {
        $promise = $this->webSocket->promiseSession();

        $promise->then(function (ClientSession $session) {
            $session->subscribe(self::ACTION, function ($args) {
                list($playerName, $direction) = $args;

                $event = new Event();
                $event->player = $this->game->getPlayerByName($playerName);
                $event->direction = $direction;

                $this->game->addEvent($event);
            });
        });
    }

    private function broadcastTick()
    {
        $this->game->on(Game::EVENT_TICK, function () {
            $this->webSocket->promiseSession()->then(function (ClientSession $session) {
                $session->publish(self::TICK, Serializer::serializeGame($this->game));
            });
        });
    }
}
