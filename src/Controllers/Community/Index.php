<?php

namespace Firststep\Controllers\Community;

use Firststep\Common\Controllers\Controller;

use Firststep\Templates\Blocks\Menus\PublicMenu;
use Firststep\Templates\Blocks\Login\LoginForm;
use Firststep\BusinessLogic\User\Daos\UserDao;
use Firststep\BusinessLogic\User\UseCases\UserCanLogIn;
use Firststep\Common\Router\Router;

/**
 * This class cares about the login process.
 * The get method cares about visualizing the interface, the post method cares about eventually allow 
 * the user to log in or not
 */
class Index extends Controller {
	
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
	
    public $post_validation_rules = array(
		'email'	   => 'max_len,255',
		'password' => 'max_len,255',
    );
    public $post_filter_rules = array(
		'email'    => 'trim',
		'password' => 'trim',
    );
	
	public function postRequest() {
		$dao = new UserDao();
		$usecase = new UserCanLogIn( $this->parameters, $dao );
		$usecase->performAction();

		if ($usecase->getUserCanLogIn()) {
			$user = $dao->getOneByFields( array( 'usr_email' => $this->parameters['email'] ) );
			$_SESSION['user_id']    = $user->usr_id;
			$_SESSION['username']   = $user->usr_name;
			$_SESSION['logged_in']  = true;
		    $_SESSION['ip']         = $_SERVER['REMOTE_ADDR'];
		    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['last_login'] = time();
			$_SESSION['office_id']  = $user->usr_usrofid;
			$_SESSION['site_id']    = $user->usr_siteid;
			
	        // redirecting to assets list
			$this->redirectToPage( $this->router->make_url( Router::ROUTE_OFFICE_INBOX ) );
		} else {
	        // redirecting to assets list
			$this->redirectToPage( $this->router->make_url( Router::ROUTE_COMMUNITY_LOGIN ) );
		}
	}
	
}
