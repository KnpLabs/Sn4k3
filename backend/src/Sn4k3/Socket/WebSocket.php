<?php

namespace Sn4k3\Socket;

use Evenement\EventEmitterTrait;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Thruway\ClientSession;
use Thruway\Peer\Client;
use Thruway\Transport\PawlTransportProvider;

class WebSocket
{
    use EventEmitterTrait;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ClientSession
     */
    private $session;

    public function __construct($host, $port, $path, LoopInterface $loop)
    {
        $this->client = new Client(
            'realm1',
            $loop
        );

        $this->client->addTransportProvider(new PawlTransportProvider(
            sprintf('ws://'.$host.':%s/%s', $port, $path)
        ));

        $this->client->on('open', [$this, 'createSession']);
    }

    public function start()
    {
        $this->client->start(false);
    }

    /**
     * @return ClientSession
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return PromiseInterface
     */
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

    /**
     * @param ClientSession $session
     */
    public function createSession(ClientSession $session)
    {
        $this->session = $session;
        $this->emit('session.start', [$session]);
    }
}
