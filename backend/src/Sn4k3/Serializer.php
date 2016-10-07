<?php

namespace Sn4k3;

use Sn4k3\Geometry\CircleList;
use Sn4k3\Model\Snake;

class Serializer
{
    public static function serializeGame(Game $game) : array
    {
        return [
            'players' => self::serializePlayers($game->getPlayers())
        ];
    }

    public static function serializePlayers(array $players) : array
    {
        $data = [];

        foreach ($players as $player) {
            $data[] = [
                'name' => $player->name,
                'snake' => self::serializeSnake($player->snake),
                'score' => $player->score,
            ];
        }

        return $data;
    }

    public static function serializeSnake(Snake $snake) : array
    {
        return [
            'direction' => $snake->direction,
            'head_angle' => $snake->headAngle,
            'length' => $snake->length,
            'body_parts' => self::serializeBodyParts($snake->getCircleList())
        ];
    }

    public static function serializeBodyParts(CircleList $bodyParts) : array
    {
        $data = [];

        foreach ($bodyParts as $part) {
            $data[] = [
                'center_point' => $part->centerPoint,
                'radius' => $part->radius
            ];
        }

        return $data;
    }
}
