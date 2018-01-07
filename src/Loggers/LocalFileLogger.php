<?php

namespace Firststep\Loggers;

/**
 * Created by fabio
 * Date: 07/01/2018
 * Time: 18:56
 */

class LocalFileLogger {

    function __construct() {
        $this->logfile = 'logs/log'.date('Y-m-d').'.log';
    }

    public function write($message, $file='', $line='') {
        $message = date("Y-m-d H:i:s") .' - '.$message;
        $message .= $file=='' ? '' : " in $file";
        $message .= $line=='' ? '' : " on line $line";
        $message .= "\n";
        file_put_contents($this->logfile, $message, FILE_APPEND);
    }

}
