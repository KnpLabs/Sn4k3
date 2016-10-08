<?php

namespace Sn4k3\Behaviour;

trait Destroyable
{
    public function destroy()
    {
        foreach (get_object_vars($this) as $p => $v) {
            $this->$p = null;
        }
    }
}