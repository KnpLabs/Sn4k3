<?php

namespace Sn4k3\Behaviour;

/**
 * To be used with DestroyableInterface.
 */
trait DestroyableTrait
{
    /**
     * {@inheritdoc}
     */
    public function destroy()
    {
        foreach ($this->getVarsToDestroy() as $p => $v) {
            if (is_object($v) && $v instanceof self) {
                $v->destroy();
            }
            $this->$p = null;
            $v = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getVarsToDestroy()
    {
        return get_object_vars($this);
    }
}
