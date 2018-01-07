<?php

namespace Firststep\Loggers;

/**
 * Created fabio
 * Date: 07/01/2018
 * Time: 19:01
 */

class EchoLogger implements Logger {

    public function write($message, $file='', $line='') {
        $message = date("Y-m-d H:i:s") .' - '.$message;
        $message .= $file=='' ? '' : " in $file";
        $message .= $line=='' ? '' : " on line $line";
        $message .= "\n";
        echo '<b>Logger:</b> '.$message;
    }

}
