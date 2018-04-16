<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';
	
echo 'Hello World!!!';

$setup = new Firststep\Setup\Setup();
$setup->setAppNameForPageTitle("Try app");
$setup->setPrivateTemplateFileName('application.php');
$setup->setPublicTemplateFileName('public.php');
$setup->setBasePath("TODO");

$request = new Firststep\Request\Request();
$request->setSessionMsgInfo( $_SESSION['msginfo'] ?? '' );
$request->setSessionMsgWarning( $_SESSION['msgwarning'] ?? '' );
$request->setSessionMsgError( $_SESSION['msgerror'] ?? '' );
$request->setSessionMsgSuccess( $_SESSION['msgsuccess'] ?? '' );
$request->setSessionFlashVariable( $_SESSION['flashvariable'] ?? '' );

if (isset($_SESSION['logged_in'])) {
	$request->setSessionLoggedId( $_SESSION['logged_in'] ?? '' ); // TODO check this
	$request->setSessionIp( $_SESSION['ip'] ?? '' );
	$request->setSessionUserAgent( $_SESSION['user_agent'] ?? '' );
	$request->setSessionLastLogin( $_SESSION['last_login'] ?? '' );
	$request->setSecurityChecker( new Firststep\SecurityCheckers\PrivateSecurityChecker() );
} else {
	$request->setSecurityChecker( new Firststep\SecurityCheckers\PublicSecurityChecker() );
}

$request->setServerRequestMethod( $_SERVER["REQUEST_METHOD"] );
$request->setServerPhpSelf( $_SERVER["PHP_SELF"] );
$request->setServerRemoteAddress( $_SERVER['REMOTE_ADDR'] );
$request->setServerHttpUserAgent( $_SERVER['HTTP_USER_AGENT'] );

unset($_SESSION['msginfo']);
unset($_SESSION['msgwarning']);
unset($_SESSION['msgerror']);
unset($_SESSION['msgsuccess']);
unset($_SESSION['flashvariable']);
	
$controller = new Firststep\Controllers\Controller( 
    $setup, 
    $request, 
    new Firststep\Redirectors\FakeRedirector(), 
    new Firststep\Loggers\EchoLogger(),
    new Firststep\Blocks\BaseMessages()
);

$_SESSION['msginfo'] = $request->getSessionMsgInfo();
$_SESSION['msgwarning'] = $request->getSessionMsgWarning();
$_SESSION['msgerror'] = $request->getSessionMsgError();
$_SESSION['msgsuccess'] = $request->getSessionMsgSuccess();
$_SESSION['flashvariable'] = $request->getSessionFlashVariable();

echo 'Controller loaded!!!';
