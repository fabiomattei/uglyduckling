<?php

namespace Fabiom\UglyDuckling\Framework\Loggers;

/**
 * Created fabio
 * Date: 14/03/2020
 * Time: 19:34
 *
 * This logger is a dev/null logger, all messages sent here are lost
 */

class MuteLogger implements Logger {

    public function write($message, $file='', $line='') {
        // nothing to do every message sent here is lost
    }

}
