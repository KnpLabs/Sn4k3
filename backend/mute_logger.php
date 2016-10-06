<?php

use Psr\Log\AbstractLogger;
use Thruway\Logging\Logger as ThruLogger;

class Logger extends AbstractLogger
{
    public function log($level, $message, array $context = array())
    {
    }
}

ThruLogger::set(new Logger());
