<?php

namespace Sn4k3\Geometry;

/**
 * Just an array of Circle objects.
 */
class CircleList implements \ArrayAccess, \Traversable, \Countable
{
    /**
     * @var Circle[]
     */
    private $circles = [];

    /**
     * @param Circle[] $circles
     */
    public function __construct(array $circles = [])
    {
        foreach ($circles as $circle) {
            $this[] = $circle;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->circles);
    }

    /**
     * {@inheritdoc}
     *
     * @return Circle|null
     */
    public function offsetGet($offset)
    {
        return $this->circles[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Circle) {
            throw new \InvalidArgumentException(sprintf(
                'Objects added in circle list must extend Circle, "%s" given.',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $this->circles[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->circles[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->circles);
    }
}
