<?php

/**
 * User: Fabio Mattei
 * Date: 24/06/2019
 * Time: 09:49
 */

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages;
use Fabiom\UglyDuckling\Common\Exceptions\ErrorPageException;
use Fabiom\UglyDuckling\Common\Exceptions\AuthorizationException;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\LinkBuilder;
use Fabiom\UglyDuckling\Common\Redirectors\Redirector;
use Fabiom\UglyDuckling\Common\Loggers\Logger;
use Fabiom\UglyDuckling\Common\Request\Request;
use Fabiom\UglyDuckling\Common\Setup\Setup;
use Fabiom\UglyDuckling\Common\Router\RoutersContainer;
use Fabiom\UglyDuckling\Common\Json\JsonLoader;
use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;
use Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;
use Fabiom\UglyDuckling\Common\SecurityCheckers\SecurityChecker;
use GUMP;

class Controller {

    public /* RoutersContainer */ $routerContainer;
    public /* Setup */ $setup;
    public /* Request */ $request;
    public /* ServerWrapper */ $serverWrapper;
    public /* SessionWrapper */ $sessionWrapper;
    public /* SecurityChecker */ $securityChecker;
    public /* DBConnection */ $dbconnection;
    public /* Redirector */ $urlredirector;
    public /* JsonLoader */ $jsonloader;
    public /* Logger */ $logger;
    public /* BaseHTMLMessages */ $messages;
    public /* HtmlTemplateLoader */ $htmlTemplateLoader;
    public /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;
    public /* LinkBuilder */ $linkBuilder;
    public /* GUMP */ $gump;
    public /* array */ $get_validation_rules = array();
    public /* array */ $get_filter_rules = array();
    public /* array */ $post_validation_rules = array();
    public /* array */ $post_filter_rules = array();
    public /* array */ $post_get_validation_rules = array();
    public /* array */ $post_get_filter_rules = array();
    public /* array */ $getParameters;
    public /* array */ $postParameters;
    public /* array */ $filesParameters;

    /**
     * This method makes all necessary presets to activate a controller
     *
     * @param RoutersContainer $routerContainer
     * @param Setup $setup
     * @param Request $request
     * @param ServerWrapper $serverWrapper
     * @param SessionWrapper $sessionWrapper
     * @param SecurityChecker $securityChecker
     * @param DBConnection $dbconnection
     * @param Redirector $urlredirector
     * @param JsonLoader $jsonloader
     * @param Logger $logger
     * @param BaseHTMLMessages $messages
     * @param HtmlTemplateLoader $htmlTemplateLoader
     * @param JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer
     * @throws \Exception
     */
    public function makeAllPresets(
        RoutersContainer $routerContainer,
        Setup $setup,
        Request $request,
        ServerWrapper $serverWrapper,
        SessionWrapper $sessionWrapper,
        SecurityChecker $securityChecker,
        DBConnection $dbconnection,
        Redirector $urlredirector,
        JsonLoader $jsonloader,
        Logger $logger,
        BaseHTMLMessages $messages,
        HtmlTemplateLoader $htmlTemplateLoader,
        JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer,
        LinkBuilder $linkBuilder
		) {
		$this->routerContainer                = $routerContainer;
        $this->setup                          = $setup;
        $this->request                        = $request;
		$this->serverWrapper                  = $serverWrapper;
		$this->sessionWrapper                 = $sessionWrapper;
		$this->securityChecker                = $securityChecker;
		$this->dbconnection                   = $dbconnection;
        $this->urlredirector                  = $urlredirector;
		$this->jsonloader                     = $jsonloader;
        $this->logger                         = $logger;
        $this->messages                       = $messages;
        $this->htmlTemplateLoader             = $htmlTemplateLoader;
        $this->jsonTemplateFactoriesContainer = $jsonTemplateFactoriesContainer;
        $this->linkBuilder                    = $linkBuilder;
        $this->gump                           = new GUMP();

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
            $parms = $this->gump->sanitize( $this->getParameters );
            $this->gump->validation_rules( $this->get_validation_rules );
            $this->gump->filter_rules( $this->get_filter_rules );
            $this->getParameters = $this->gump->run( $parms );
			$this->unvalidated_parameters = $parms;
            if ( $this->getParameters === false ) {
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
			$out = false;
			
			// checking get parameters in post request
            $parms = $this->gump->sanitize( $this->getParameters );
            $this->gump->validation_rules( $this->post_get_validation_rules );
            $this->gump->filter_rules( $this->post_get_filter_rules );
            $this->getParameters = $this->gump->run( $parms );
			$this->unvalidated_parameters = $parms;
            if ( $this->getParameters === false ) {
				$this->readableErrors = $this->gump->get_readable_errors(true);
                $out = false;
            } else {
                $out = true;
            }

			// checking post parameters in post request			
            $parms = $this->gump->sanitize( $this->postParameters );
            $this->gump->validation_rules( $this->post_validation_rules );
            $this->gump->filter_rules( $this->post_filter_rules );
            $this->postParameters = $this->gump->run( $parms );
			$this->unvalidated_parameters = $parms;
            if ( $this->postParameters === false ) {
				$this->readableErrors = $this->gump->get_readable_errors(true);
                $out = false;
            } else {
                $out = true;
            }
			
			return $out;
        }
    }

    /**
     * This method has to be overriden, if id does not it throws an unhandled ErrorPageException
     * The ovverriding method need to show the page containing the errors that prevent the validation to pass
     *
     * @throws ErrorPageException
     */
    public function show_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

    /**
     * This method has to be overriden, if id does not it throws an unhandled ErrorPageException
     * The ovverriding method need to show the page containing the errors that prevent the validation to pass
     *
     * @throws ErrorPageException
     */
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
            $this->logger->write('WARNING TIME :: ' . $this->request->getInfo() . ' - TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
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
     * Return the SessionWrapper variable set for this controller
     */
    function getSessionWrapper() : SessionWrapper {
        return $this->sessionWrapper;
    }

    /**
     * Function for setting parameters array
     */
    public function setGetParameters( $parameters ) {
        if (is_array($parameters)) {
            $this->getParameters = $parameters;
        }
    }
	
    /**
     * Function for setting parameters array
     */
    public function setPostParameters( $parameters ) {
        if (is_array($parameters)) {
            $this->postParameters = $parameters;
        }
    }

    /**
     * Function for setting parameters array
     */
    public function setFilesParameters( $parameters ) {
        if (is_array($parameters)) {
            $this->filesParameters = $parameters;
        }
    }

    /**
     * Redirect the script to $_SESSION['prevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToPreviousPage() {
        // avoid end of round here...
        $this->urlredirector->setURL($this->sessionWrapper->getSecondRequestedURL());
        $this->urlredirector->redirect();
    }

    /**
     * Redirect the script to $_SESSION['prevprevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToSecondPreviousPage() {
        // avoid end of round here...
        $this->urlredirector->setURL($this->sessionWrapper->getThirdRequestedURL());
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

        require_once $this->setup->getHTMLTemplatePath() . $this->templateFile . '.php';
    }

    function addToHeadAndToFoot($container) {
        $addToHeadDictionary = array();
        $addToFootDictionary = array();

        if (isset($container)) {
            if (gettype($container) == 'array') {
                foreach ($container as $obj) {
                    $addToHeadDictionary[get_class($obj)] = $obj->addToHeadOnce();
                    $addToFootDictionary[get_class($obj)] = $obj->addToFootOnce();
                }
            }
            if (gettype($container) == 'object') {
                $addToHeadDictionary[get_class($obj)] = $obj->addToHeadOnce();
                $addToFootDictionary[get_class($obj)] = $obj->addToFootOnce();
            }
        }
        foreach ($addToHeadDictionary as $htmlBlock => $blockContent) {
            $this->addToHead .= $blockContent;
        }
        foreach ($addToFootDictionary as $htmlBlock => $blockContent) {
            $this->addToFoot .= $blockContent;
        }

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
        $this->securityChecker = $securityChecker;
        $this->logger          = $logger;
*/
    public function getInfo(): string {
        return '<br>'.$this->routerContainer->getInfo().'<br>'.$this->request->getInfo().'<br>';
    }

}
