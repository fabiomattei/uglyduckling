<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 07/01/2018
 * Time: 19:06
 */

namespace Firststep\Common\Loggers;

interface Logger {

    public function write($message, $file='', $line='');

}