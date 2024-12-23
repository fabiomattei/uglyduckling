<?php

namespace Fabiom\UglyDuckling\Framework\Loggers;

interface Logger {
    public function write($message, $file='', $line='');
}
