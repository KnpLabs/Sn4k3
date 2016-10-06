<?php

require __DIR__ . '/vendor/autoload.php';

use React\EventLoop\Factory;
use Sn4k3\Game;

$loop = Factory::create();
$game = new Game($loop);
$game->run();
