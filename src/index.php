<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

$severWrapper = new Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper;
$sessionWrapper = new Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;
$htmlTemplateLoader = new Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;
$htmlTemplateLoader->setPath('Templates/HTML/');

$messagesBlock = new Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages();
$messagesBlock->setHtmlTemplateLoader( $htmlTemplateLoader );

$setup = new Fabiom\UglyDuckling\Common\Setup\Setup();
$setup->setAppNameForPageTitle("Try app");
$setup->setPrivateTemplateFileName('application');
$setup->setPrivateTemplateWithSidebarFileName('applicationwithsidebar');
$setup->setPublicTemplateFileName('public');
$setup->setEmptyTemplateFileName('empty');
$setup->setBasePath('http://localhost:18080/');
$setup->setPathToApp('/uglyduckling/');
$setup->setJsonPath('./Custom/Json/index.json');

$dbconnection = new Fabiom\UglyDuckling\Common\Database\DBConnection( 
	'mysql:host=mariadb:3306;dbname=',
	'firststep',
	'user',
	'userp'
);

$request = new Fabiom\UglyDuckling\Common\Request\Request();
$request->setServerRequestURI( $severWrapper->getRequestURI() );

$routerContainer = new Fabiom\UglyDuckling\Common\Router\RoutersContainer( $setup->getBasePath() );
$routerContainer->addRouter( new Fabiom\UglyDuckling\Common\Router\Router( $setup->getBasePath() ) );

$controller = $routerContainer->getController( $request->getAction() );

$jsonloader = new Fabiom\UglyDuckling\Common\Json\JsonLoader();
$jsonloader->setIndexPath($setup->getJsonPath());

$jsonTemplateFactoriesContainer = new Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer;
$jsonTemplateFactoriesContainer->addJsonTemplateFactory( new Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonDefaultTemplateFactory );

if ( $sessionWrapper->isUserLoggedIn() ) {
	// settings for logged in user
	$controller->makeAllPresets(
		$routerContainer,
    	$setup, 
    	$request,
		$severWrapper,
		$sessionWrapper,
		new Fabiom\UglyDuckling\Common\SecurityCheckers\PrivateSecurityChecker(),
		$dbconnection,
    	new Fabiom\UglyDuckling\Common\Redirectors\URLRedirector(),
		$jsonloader,
    	new Fabiom\UglyDuckling\Common\Loggers\EchoLogger(),
    	$messagesBlock,
        $htmlTemplateLoader,
        $jsonTemplateFactoriesContainer
	);
} else {
	// settings for user that has not logged in the system
	$controller->makeAllPresets(
		$routerContainer,
    	$setup, 
    	$request,
		$severWrapper,
		$sessionWrapper,
		new Fabiom\UglyDuckling\Common\SecurityCheckers\PublicSecurityChecker(),
		$dbconnection,
    	new Fabiom\UglyDuckling\Common\Redirectors\URLRedirector(),
		$jsonloader,
    	new Fabiom\UglyDuckling\Common\Loggers\EchoLogger(),
    	$messagesBlock,
        $htmlTemplateLoader,
        $jsonTemplateFactoriesContainer
	);
}
$controller->setGetParameters( $_GET );
$controller->setPostParameters( $_POST );

$sessionWrapper->setRequestedURL( $severWrapper->getRequestURI() );
$controller->showPage();

$sessionWrapper->endOfRound();

// echo 'Controller: ' . get_class( $controller );
echo $controller->getInfo();
