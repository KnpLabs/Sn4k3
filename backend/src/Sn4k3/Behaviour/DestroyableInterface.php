<?php

namespace Sn4k3\Behaviour;

interface DestroyableInterface
{
    /**
     * Destroy the item itself.
     */
    public function destroy();

    /**
     * Get a list of vars/objects to destroy.
     *
     * @return array
     */
    public function getVarsToDestroy(): array;
}
