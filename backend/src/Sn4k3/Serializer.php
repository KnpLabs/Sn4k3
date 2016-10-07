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
            'players' => self::serializePlayers($game->getPlayers()),
            'map' => static::serializeMap($game->getMap()),
        ];
    }

    public static function serializeMap(Map $map): array
    {
        return [
            'foods' => static::serializeFoods($map->foods),
        ];
    }

    public static function serializeFoods(array $foods)
    {
        $data = [];

        foreach ($foods as $food) {
            if (!$food instanceof Food) {
                throw new \InvalidArgumentException('Foods array should be populated with Food objects.');
            }
            $data[] = [
                'circle' => static::serializeCircle($food->circle),
                'value' => $food->value,
            ];
        }

        return $data;
    }

    /**
     * @param Player[] $players
     *
     * @return array
     */
    public static function serializePlayers(array $players) : array
    {
        $data = [];

        foreach ($players as $player) {
            $data[] = [
                'name' => $player->name,
                'color' => $player->color,
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
            'body_parts' => self::serializeCircleList($snake->getCircleList()),
            'destroyed' => $snake->destroyed,
        ];
    }

    public static function serializeCircleList(CircleList $bodyParts) : array
    {
        $data = [];

        foreach ($bodyParts as $part) {
            $data[] = static::serializeCircle($part);
        }

        return $data;
    }

    public static function serializeCircle(Circle $circle): array
    {
        return [
            'center_point' => static::serializePoint($circle->centerPoint),
            'radius' => $circle->radius,
        ];
    }

    public static function serializePoint(Point $point)
    {
        return [
            'x' => $point->x,
            'y' => $point->y,
        ];
    }
}
