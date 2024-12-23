<?php

namespace Fabiom\UglyDuckling\Framework\Loggers;

class EchoLogger implements Logger {

    public function write($message, $file='', $line='') {
        $message = date("Y-m-d H:i:s") .' - '.$message;
        $message .= ($file=='' ? '' : " in $file");
        $message .= ($line=='' ? '' : " on line $line");
        $message .= "\n";
        echo '<b>Logger:</b> '.$message;
    }

}
