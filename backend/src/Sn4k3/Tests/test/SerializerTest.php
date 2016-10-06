<?php

use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use Sn4k3\Game;
use Sn4k3\Serializer;

class SerializerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_serialize_empty_game()
    {
        $loop = Factory::create();
        $game = new Game($loop);

        $this->assertEquals([
            'players' => []
        ], Serializer::serializeGame($game));
    }

    /**
     * @test
     */
    public function it_should_serialize_game_with_players()
    {
        $loop = Factory::create();
        $game = new Game($loop);
        $game->initializePlayer('Player1');

        $serialized = Serializer::serializeGame($game);

        $this->assertEquals('Player1', $serialized['players'][0]['name']);
    }
}
