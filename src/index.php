<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

$severWrapper = new Firststep\Common\Wrappers\ServerWrapper;
$sessionWrapper = new Firststep\Common\Wrappers\SessionWrapper;

$setup = new Firststep\Common\Setup\Setup();
$setup->setAppNameForPageTitle("Try app");
$setup->setPrivateTemplateFileName('application');
$setup->setPublicTemplateFileName('public');
$setup->setBasePath('http://localhost:18080/');
$setup->setPathToApp('/uglyduckling/');
$setup->setYamlPath('./Custom/index.json');

$dbconnection = new Firststep\Common\Database\DBConnection( 
	'mysql:host=mariadb:3306;dbname=',
	'firststep',
	'root',
	'password'
);

$request = new Firststep\Common\Request\Request();
$request->setServerRequestURI( $severWrapper->getRequestURI() );

$router = new Firststep\Common\Router\Router( $setup->getBasePath() );

$controller = $router->getController( $request->getAction() );

$yamlLoader = new Firststep\Common\Json\JsonLoader();
$yamlLoader->setIndexPath($setup->getYamlPath());

if ( $sessionWrapper->isUserLoggedIn() ) {
	// settings for logged in user
	$controller->makeAllPresets(
		$router,
    	$setup, 
    	$request,
		$severWrapper,
		$sessionWrapper,
		new Firststep\Common\SecurityCheckers\PrivateSecurityChecker(),
		$dbconnection,
    	new Firststep\Common\Redirectors\URLRedirector(),
		$yamlLoader,
    	new Firststep\Common\Loggers\EchoLogger(),
    	new Firststep\Common\Blocks\BaseMessages()
	);
} else {
	// settings for user that has not logged in the system
	$controller->makeAllPresets(
		$router,
    	$setup, 
    	$request,
		$severWrapper,
		$sessionWrapper,
		new Firststep\Common\SecurityCheckers\PublicSecurityChecker(),
		$dbconnection,
    	new Firststep\Common\Redirectors\URLRedirector(),
		$yamlLoader,
    	new Firststep\Common\Loggers\EchoLogger(),
    	new Firststep\Common\Blocks\BaseMessages()
	);
}
$controller->setParameters( $request->getParameters() );

// $controller->setRequest( $request );
// $controller->setControllerPath( OFFICE, CHAPTER, CONTROLLER );
$controller->showPage();

$sessionWrapper->endOfRound();

echo 'Controller: ' . get_class( $controller );
echo $controller->getInfo();
