<?php

namespace Sn4k3\Tests\GameEngine;

use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use Sn4k3\Game;
use Sn4k3\Serializer;

class SerializerTest extends TestCase
{
    public function test it should serialize empty game()
    {
        $loop = Factory::create();
        $game = new Game($loop);

        static::assertEquals([
            'p' => [],
            'm' => [
                'f' => [],
            ],
        ], Serializer::serializeGame($game));
    }

    public function test it should serialize game with players()
    {
        $loop = Factory::create();
        $game = new Game($loop);
        $game->initializePlayer('Player1');

        $serialized = Serializer::serializeGame($game);

        static::assertEquals('Player1', $serialized['p'][0]['n']);
    }
}
