<?php

require __DIR__ . '/../vendor/autoload.php';
	
echo 'Hello World!!!';
	
$controller = new Firststep\Controllers\Controller( 
    new Firststep\Setup\Setup(), 
    new Firststep\Request\Request(), 
    new Firststep\Redirectors\FakeRedirector(), 
    new Firststep\Loggers\EchoLogger(),
    new Firststep\Blocks\BaseMessages()
);

echo 'Controller loaded!!!';
