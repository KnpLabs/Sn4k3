<?php

namespace Sn4k3\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sn4k3\Model\Map;
use Sn4k3\Model\Player;

class PlayerTest extends TestCase
{
    public function test only moves left and right()
    {
        $player = new Player(new Map());

        static::assertTrue($player->canChangeDirection('left'));
        static::assertTrue($player->canChangeDirection('right'));
        static::assertFalse($player->canChangeDirection('up'));
        static::assertFalse($player->canChangeDirection('down'));
    }

    public function test change direction also changes snake direction()
    {
        $player = new Player(new Map());

        $player->snake->direction = 'left';
        $player->changeDirection('left');
        static::assertEquals('left', $player->snake->direction);

        $player->snake->direction = 'right';
        $player->changeDirection('right');
        static::assertEquals('right', $player->snake->direction);
    }
}
