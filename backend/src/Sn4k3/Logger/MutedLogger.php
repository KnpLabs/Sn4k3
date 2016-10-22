<?php

namespace Sn4k3\Logger;

use Psr\Log\AbstractLogger;

class MutedLogger extends AbstractLogger
{
    public function log($level, $message, array $context = array())
    {
    }
}
