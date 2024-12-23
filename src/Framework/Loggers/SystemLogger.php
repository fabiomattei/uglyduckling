<?php

namespace Fabiom\UglyDuckling\Framework\Loggers;

/**
 * Created fabio
 * Date: 07/01/2018
 * Time: 18:58
 */

class SystemLogger implements Logger {

    public function write($message, $file='', $line='') {
        $message = date("Y-m-d H:i:s") .' - '.$message;
        $message .= $file=='' ? '' : " in $file";
        $message .= $line=='' ? '' : " on line $line";
        $message .= "\n";
        syslog( LOG_ERR, '<b>Logger:</b> '.$message );
    }

}
