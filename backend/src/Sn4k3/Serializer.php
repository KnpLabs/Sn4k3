<?php

namespace Sn4k3;

use Sn4k3\Geometry\CircleList;
use Sn4k3\Geometry\Point;
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
            'body_parts' => self::serializeBodyParts($snake->bodyParts)
        ];
    }

    public static function serializeBodyParts(CircleList $bodyParts) : array
    {
        $data = [];

        foreach ($bodyParts as $part) {
            $data[] = [
                'center_point' => static::serializePoint($part->centerPoint),
                'radius' => $part->radius
            ];
        }

        return $data;
    }

    public static function serializePoint(Point $point)
    {
        return [
            'x' => $point->x,
            'y' => $point->y
        ];
    }
}
