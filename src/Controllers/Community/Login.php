<?php

namespace Fabiom\UglyDuckling\Controllers\Community;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Setup\SessionJsonSetup;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\PublicMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Login\LoginForm;
use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\BusinessLogic\User\UseCases\UserCanLogIn;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Loggers\EchoLogger;

/**
 * This class cares about the login process.
 * The get method cares about visualizing the interface, the post method cares about eventually allow 
 * the user to log in or not
 */
class Login extends Controller {

     private /* UserDao */ $userDao;
     private /* UserCanLogIn */ $userCanLogIn;
     private /* QueryExecuter */ $queryExecuter;
     private /* QueryBuilder */ $queryBuilder;
	
    function __construct() {
        $this->logger = new EchoLogger;
		$this->userDao = new UserDao;
		$this->userCanLogIn = new UserCanLogIn;
		$this->queryExecuter = new QueryExecuter;
        $this->queryExecuter->setLogger($this->logger);
		$this->queryBuilder = new QueryBuilder;
    }
	
	public function getRequest() {
		$error = '';
		
		if ( isset( $this->parameters[0] )  AND $this->parameters[0] != '' ) {
			$error = 'error';
		}
		
		$this->title            = $this->setup->getAppNameForPageTitle() . ' :: Access page';
		$this->menucontainer    = array( new PublicMenu( $this->setup->getAppNameForPageTitle(), 'login' ) );
		$this->centralcontainer = array( new LoginForm( $this->setup->getAppNameForPageTitle(), $error ) );
		$this->templateFile     = 'login';
	}
	
    public /* array */ $post_validation_rules = array(
		'email'	   => 'max_len,255',
		'password' => 'max_len,255',
    );
    public /* array */ $post_filter_rules = array(
		'email'    => 'trim',
		'password' => 'trim',
    );
	
	public function postRequest() {
		$this->userDao->setDBH( $this->dbconnection->getDBH() );
		$this->userCanLogIn->setUserDao( $this->userDao );
		$this->userCanLogIn->setParameters( $this->postParameters );
		$this->userCanLogIn->performAction();

		if ($this->userCanLogIn->getUserCanLogIn()) {
			$user = $this->userDao->getOneByFields( array( 'usr_email' => $this->postParameters['email'] ) );
			$this->pageStatus->getSessionWrapper()->setSessionUserId( $user->usr_id );
			$this->pageStatus->getSessionWrapper()->setSessionUsername( $user->usr_name );
			$this->pageStatus->getSessionWrapper()->setSessionGroup( $user->usr_defaultgroup );
			$this->pageStatus->getSessionWrapper()->setSessionLoggedIn( true );
			$this->pageStatus->getSessionWrapper()->setSessionIp( $this->serverWrapper->getRemoteAddress() );
			$this->pageStatus->getSessionWrapper()->setSessionUserAgent( $this->serverWrapper->getHttpUserAgent() );
			$this->pageStatus->getSessionWrapper()->setSessionLastLogin( time() );
			
			if ( $this->pageStatus->getSetup()->isSessionSetupPathSet() ) {
                 SessionJsonSetup::loadSessionVariables(
                     $this->applicationBuilder->getSetup()->getSessionSetupPath(),
                     $this->queryBuilder,
                     $this->queryExecuter,
                     $this->applicationBuilder->getDbconnection(),
                     $this->pageStatus->getSessionWrapper()
                 );
			}

            $this->applicationBuilder->getJsonloader()->loadIndex();
            $groupresource = $this->applicationBuilder->getJsonloader()->loadResource( $this->pageStatus->getSessionWrapper()->getSessionGroup() );
			
	        // redirecting to main page
			// $this->redirectToPage( $this->router->makeRelativeUrl( Router::ROUTE_OFFICE_INBOX ) );
			if ( $user->usr_defaultgroup == 'administrationgroup' ) {
				$this->redirectToPage( $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( Router::ROUTE_ADMIN_DASHBOARD ) );
			} else {
				$this->redirectToPage( $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( Router::ROUTE_OFFICE_ENTITY_DASHBOARD, 'res='.$groupresource->defaultaction ) );
			}
			
		} else {
	        // redirecting to main page
			$this->redirectToPage( $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( Router::ROUTE_COMMUNITY_LOGIN ) );
		}
	}
	
}
