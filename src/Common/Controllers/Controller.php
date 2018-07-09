<?php

namespace Firststep\Common\Controllers;

use Firststep\Common\Blocks\BaseMessages;
use Firststep\Common\Exceptions\ErrorPageException;
use Firststep\Common\Exceptions\AuthorizationException;
use Firststep\Common\Redirectors\Redirector;
use Firststep\Common\Loggers\Logger;
use Firststep\Common\Request\Request;
use Firststep\Common\Setup\Setup;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\DBConnection;
use Firststep\Common\Wrappers\ServerWrapper;
use Firststep\Common\Wrappers\SessionWrapper;
use Firststep\Common\SecurityCheckers\SecurityChecker;
use GUMP;

class Controller {

    public $get_validation_rules = array();
    public $get_filter_rules = array();
    public $post_validation_rules = array();
    public $post_filter_rules = array();

    public function makeAllPresets( 
		Router $router, 
		Setup $setup, 
		Request $request, 
		ServerWrapper $serverWrapper,
		SessionWrapper $sessionWrapper,
		SecurityChecker $securityChecker,
		DBConnection $dbconnection, 
		Redirector $urlredirector, 
		Logger $logger, 
		BaseMessages $messages 
		) {
		$this->router          = $router;
        $this->setup           = $setup;
        $this->request         = $request;
		$this->serverWrapper   = $serverWrapper;
		$this->sessionWrapper  = $sessionWrapper;
		$this->securityChecker = $securityChecker;
		$this->dbconnection    = $dbconnection;
        $this->urlredirector   = $urlredirector;
        $this->logger          = $logger;
        $this->messages        = $messages;
        $this->gump            = new GUMP();

        // setting an array containing all parameters
        $this->parameters = array();

        $this->title = $this->setup->getAppNameForPageTitle();
        $this->menucontainer = array();
        $this->topcontainer = array();
        $this->messagescontainer = array( $this->messages );
        $this->leftcontainer = array();
        $this->rightcontainer = array();
        $this->centralcontainer = array();
        $this->secondcentralcontainer = array();
        $this->thirdcentralcontainer = array();
        $this->bottomcontainer = array();
        $this->sidebarcontainer = array();
        $this->templateFile = $this->setup->getPrivateTemplateFileName();

        $this->addToHead = '';
        $this->addToFoot = '';
        $this->subAddToHead = '';
        $this->subAddToFoot = '';

        $this->messages->info = $this->sessionWrapper->getMsgInfo();
        $this->messages->warning = $this->sessionWrapper->getMsgWarning();
        $this->messages->error = $this->sessionWrapper->getMsgError();
        $this->messages->success = $this->sessionWrapper->getMsgSuccess();
        $this->flashvariable = $this->sessionWrapper->getFlashVariable();

        if ( !$this->securityChecker->isSessionValid( 
			$this->sessionWrapper->getSessionLoggedIn(), 
            $this->sessionWrapper->getSessionIp(),
            $this->sessionWrapper->getSessionUserAgent(),
            $this->sessionWrapper->getSessionLastLogin(),
            $this->serverWrapper->getRemoteAddress(),
            $this->serverWrapper->getHttpUserAgent() ) ) {
            $this->urlredirector->setURL($this->setup->getBasePath() . 'public/login.html');
            $this->urlredirector->redirect();
        }
    }

    /**
     * Method to override (eventually)
     */
    public function getRequest() {
        echo 'not implemented yet';
    }

    /**
     * Method to override (eventually)
     */
    public function postRequest() {
        echo 'not implemented yet';
    }

    /**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function check_get_request() {
        if ( count( $this->get_validation_rules ) == 0 ) {
            return true;
        } else {
            $parms = $this->gump->sanitize($this->parameters);
            $this->gump->validation_rules( $this->get_validation_rules );
            $this->gump->filter_rules( $this->get_filter_rules );
            $this->parameters = $this->gump->run( $parms );
			$this->unvalidated_parameters = $parms;
            if ( $this->parameters === false ) {
				$this->readableErrors = $this->gump->get_readable_errors(true);
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function check_post_request() {
        if ( count( $this->post_validation_rules ) == 0 ) {
            return true;
        } else {
            $parms = $this->gump->sanitize( $_POST );
            $this->gump->validation_rules( $this->post_validation_rules );
            $this->gump->filter_rules( $this->post_filter_rules );
            $this->parameters = $this->gump->run( $parms );
			$this->unvalidated_parameters = $parms;
            if ( $this->parameters === false ) {
				$this->readableErrors = $this->gump->get_readable_errors(true);
                return false;
            } else {
                return true;
            }
        }
    }

    public function show_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

    public function show_post_error_page() {
        throw new ErrorPageException('Error page exception function show_post_error_page()');
    }

    /**
     * This method has to be implemented by inerithed class
	 * It return true by defult for compatiblity issues
     */
    public function check_authorization_get_request() {
        return true;
    }

    /**
     * This method has to be implemented by inerithed class
	 * It return true by defult for compatiblity issues
     */
    public function check_authorization_post_request() {
        return true;
    }

    public function show_get_authorization_error_page() {
        throw new AuthorizationException('Authorization exception function show_get_authorization_error_page()');
    }

    public function show_post_authorization_error_page() {
        throw new AuthorizationException('Authorization exception function show_post_authorization_error_page()');
    }

    public function showPage() {
        $time_start = microtime(true);

        if ($this->serverWrapper->isGetRequest()) {
			if ( $this->check_authorization_get_request() ) {
	            if ( $this->check_get_request() ) {
	                $this->getRequest();
	            } else {
	                $this->show_get_error_page();
	            }
			} else {
				$this->check_authorization_get_request();
			}
        } else {
			if ( $this->check_authorization_post_request() ) {
	            if ( $this->check_post_request() ) {
	                $this->postRequest();
	            } else {
	                $this->show_post_error_page();
	            }
			} else {
				$this->check_authorization_post_request();
			}
        }

        $this->loadTemplate();

        $time_end = microtime(true);
        if (($time_end - $time_start) > 5) {
            $this->logger->write('WARNING TIME :: ' . $this->request->getServerRequestMethod() . ' ' . $this->request->getServerPhpSelf() . ' ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    // ** next section load textual messages for messages block
    function setSuccess( string $success ) {
        $this->sessionWrapper->setMsgSuccess( $success );
    }

    function setError( string $error ) {
        $this->sessionWrapper->setMsgError( $error );
    }

    function setInfo( string $info ) {
        $this->sessionWrapper->setMsgInfo( $info );
    }

    function setWarning( string $warning ) {
        $this->sessionWrapper->setMsgWarning( $warning );
    }

    /**
     * This method give to the programmer the possibility of setting a flashvariable, a 
     * variable that will be active up the the next call.
     * This is ment to be used for instance to send variable from a GET form request to a 
     * Post form request or in any case a variable is meant to last only to the next browser
     * request.
     * The variable as not a specific type, maybe it is better to use it with strings
     * 
     * @param [string] $flashvariable [variable that last for a request in the same session]
     */
    function setFlashVariable( string $flashvariable ) {
        $this->sessionWrapper->setFlashVariable( $flashvariable );
    }

    /**
     * This method return a variable set in the prevoius broser request.
     * To have a better understanging look at setFlashVariable description
     * 
     * @return [string] [variable that last for a request in the same session]
     */
    function getFlashVariable() : string {
        return $this->sessionWrapper->getFlashVariable();
    }

    /**
     * Function for setting parameters array
     */
    public function setParameters($parameters) {
        if (is_array($parameters)) {
            $this->parameters = $parameters;
        }
    }

    /**
     * Redirect the script to $_SESSION['prevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToPreviousPage() {
        // avoid end of round here...
        $this->urlredirector->setURL($this->request->getSecondRequestedURL());
        $this->urlredirector->redirect();
    }

    /**
     * Redirect the script to $_SESSION['prevprevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToSecondPreviousPage() {
        // avoid end of round here...
        $this->urlredirector->setURL($this->request->getThirdRequestedURL());
        $this->urlredirector->redirect();
    }

    /**
     * Redirect the script to a selected url
     */
    public function redirectToPage( $url ) {
        $this->urlredirector->setURL( $url );
        $this->urlredirector->redirect();
    }

    // taken from page script
    function loadTemplate() {
        $this->addToHeadAndToFoot($this->menucontainer);
        $this->addToHeadAndToFoot($this->topcontainer);
        $this->addToHeadAndToFoot($this->messagescontainer);
        $this->addToHeadAndToFoot($this->leftcontainer);
        $this->addToHeadAndToFoot($this->centralcontainer);
        $this->addToHeadAndToFoot($this->secondcentralcontainer);
        $this->addToHeadAndToFoot($this->thirdcentralcontainer);
        $this->addToHeadAndToFoot($this->bottomcontainer);

        require_once 'Templates/' . $this->templateFile . '.php';
    }

    function addToHeadAndToFoot($container) {
        if (isset($container)) {
            if (gettype($container) == 'array') {
                foreach ($container as $obj) {
                    $this->addToHead .= $obj->addToHead();
                    $this->addToFoot .= $obj->addToFoot();
                    $this->subAddToHead .= $obj->subAddToHead();
                    $this->subAddToFoot .= $obj->subAddToFoot();
                }
            }
            if (gettype($container) == 'object') {
                $this->addToHead .= $container->addToHead();
                $this->addToFoot .= $container->addToFoot();
                $this->subAddToHead .= $container->subAddToHead();
                $this->subAddToFoot .= $container->subAddToFoot();
            }
        }
    }

/*
        $this->router          = $router;
        $this->setup           = $setup;
        $this->request         = $request;
        $this->serverWrapper   = $serverWrapper;
        $this->sessionWrapper  = $sessionWrapper;
        $this->securityChecker = $securityChecker;
        $this->dbconnection    = $dbconnection;
        $this->urlredirector   = $urlredirector;
        $this->logger          = $logger;
*/
    public function getInfo(): string {
        return '<br>'.$this->router->getInfo().'<br>'.$this->request->getInfo().'<br>';
    }

}
