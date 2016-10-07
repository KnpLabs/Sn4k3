<?php

namespace Sn4k3\Geometry;

/**
 * Just an array of Circle objects.
 */
class CircleList implements \ArrayAccess, \Countable, \IteratorAggregate
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
     * @param Circle $circle
     */
    public function append(Circle $circle)
    {
        $this[] = $circle;
    }

    public function prepend(Circle $circle)
    {
        array_unshift($this->circles, $circle);
    }

    /**
     * @return Circle
     */
    public function first(): Circle
    {
        reset($this->circles);

        return current($this->circles);
    }

    /**
     * @return Circle
     */
    public function last(): Circle
    {
        reset($this->circles);

        return end($this->circles);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
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

        if (null === $offset) {
            $this->circles[] = $value;
        } else {
            $this->circles[$offset] = $value;
        }
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
    public function count(): integer
    {
        return count($this->circles);
    }

    /**
     * @return Circle|null
     */
    public function pop()
    {
        return array_pop($this->circles);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->circles);
    }
}
