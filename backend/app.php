<?php

require_once __DIR__ . '/vendor/autoload.php';

use React\EventLoop\Factory;
use Sn4k3\Game;

$loop = Factory::create();
$game = new Game($loop, 50);
$game->run();
$loop->run();
