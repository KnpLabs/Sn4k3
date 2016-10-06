<?php

namespace Sn4k3\Socket;

use Evenement\EventEmitterTrait;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use Thruway\ClientSession;
use Thruway\Peer\Client;
use Thruway\Transport\PawlTransportProvider;

class WebSocket
{
    use EventEmitterTrait;

    private $client;
    private $session;

    public function __construct($port, $path, LoopInterface $loop)
    {
        $this->client = new Client(
            'realm1',
            $loop
        );

        $this->client->addTransportProvider(new PawlTransportProvider(
            sprintf('ws://127.0.0.1:%s/%s', $port, $path)
        ));

        $this->client->on('open', [$this, 'createSession']);
    }

    public function start()
    {
        $this->client->start(false);
    }

    public function getSession()
    {
        return $this->session;
    }

    public function promiseSession()
    {
        $defer = new Deferred();

        $this->on('session.start', function (ClientSession $session) use ($defer) {
            $defer->resolve($session);
        });

        if ($this->session) {
            $defer->resolve($this->session);
        }

        return $defer->promise();
    }

    public function createSession(ClientSession $session)
    {
        $this->session = $session;
        $this->emit('session.start', [$session]);
    }
}