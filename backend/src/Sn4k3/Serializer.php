<?php

namespace Sn4k3;

use Sn4k3\Geometry\Circle;
use Sn4k3\Geometry\CircleList;
use Sn4k3\Geometry\Point;
use Sn4k3\Model\Food;
use Sn4k3\Model\Map;
use Sn4k3\Model\Player;
use Sn4k3\Model\Snake;

class Serializer
{
    public static function serializeGame(Game $game) : array
    {
        return [
            'p' => self::serializePlayers($game->getPlayers()),
            'm' => static::serializeMap($game->getMap()),
        ];
    }

    public static function serializeMap(Map $map): array
    {
        return [
            'f' => static::serializeFoods(array_values($map->foods)),
        ];
    }

    public static function serializeFoods(array $foods)
    {
        return array_map(function(Food $food) {
            return [
                static::serializeCircle($food->circle),
                $food->value,
            ];
        }, $foods);
    }

    /**
     * @param Player[] $players a "hash => player" hash
     *
     * @return array
     */
    public static function serializePlayers(array $players) : array
    {
        return array_map(function(Player $player) {
            return [
                'n' => $player->name,
                'c' => $player->color,
                's' => self::serializeSnake($player->snake),
            ];
        }, array_values($players));
    }

    public static function serializeSnake(Snake $snake) : array
    {
        return [
            'd' => $snake->direction,
            'a' => $snake->headAngle,
            'l' => $snake->length,
            'b' => self::serializeCircleList($snake->getCircleList()),
            'dd' => $snake->destroyed,
        ];
    }

    public static function serializeCircleList(CircleList $bodyParts) : array
    {
        return array_map(function(Circle $bodyPart) {
            return static::serializeCircle($bodyPart);
        }, $bodyParts->getCirclesArray());
    }

    public static function serializeCircle(Circle $circle): array
    {
        return [
            static::serializePoint($circle->centerPoint),
            $circle->radius,
        ];
    }

    public static function serializePoint(Point $point)
    {
        return [
            $point->x,
            $point->y,
        ];
    }
}
