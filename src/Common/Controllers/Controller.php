<?php

/**
 * User: Fabio Mattei
 * Date: 24/06/2019
 * Time: 09:49
 */

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Exceptions\ErrorPageException;
use Fabiom\UglyDuckling\Common\Exceptions\AuthorizationException;
use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;
use GUMP;

class Controller {

    public ApplicationBuilder $applicationBuilder;
    public PageStatus $pageStatus;
    public string $templateFile;
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
     * @param ApplicationBuilder $routerContainer
     * @param PageStatus $PageStatus
     * @throws \Exception
     */
    public function makeAllPresets(
        ApplicationBuilder $applicationBuilder,
        PageStatus $pageStatus
		) {
		$this->applicationBuilder    = $applicationBuilder;
        $this->pageStatus            = $pageStatus;
        $this->gump                  = new GUMP();

        // setting an array containing all parameters
        $this->parameters = array();

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle();
        $this->menucontainer = array();
        $this->topcontainer = array();
        $this->messagescontainer = array( $this->applicationBuilder->getMessages() );
        $this->leftcontainer = array();
        $this->rightcontainer = array();
        $this->centralcontainer = array();
        $this->secondcentralcontainer = array();
        $this->thirdcentralcontainer = array();
        $this->bottomcontainer = array();
        $this->sidebarcontainer = array();
        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateFileName();

        $this->addToHead = '';
        $this->addToFoot = '';
        $this->subAddToHead = '';
        $this->subAddToFoot = '';

        $this->applicationBuilder->getMessages()->info = $this->pageStatus->getSessionWrapper()->getMsgInfo();
        $this->applicationBuilder->getMessages()->warning = $this->pageStatus->getSessionWrapper()->getMsgWarning();
        $this->applicationBuilder->getMessages()->error = $this->pageStatus->getSessionWrapper()->getMsgError();
        $this->applicationBuilder->getMessages()->success = $this->pageStatus->getSessionWrapper()->getMsgSuccess();
        $this->flashvariable = $this->pageStatus->getSessionWrapper()->getFlashVariable();

        if ( !$this->applicationBuilder->getSecurityChecker()->isSessionValid(
			$this->pageStatus->getSessionWrapper()->getSessionLoggedIn(),
            $this->pageStatus->getSessionWrapper()->getSessionIp(),
            $this->pageStatus->getSessionWrapper()->getSessionUserAgent(),
            $this->pageStatus->getSessionWrapper()->getSessionLastLogin(),
            $this->pageStatus->getServerWrapper()->getRemoteAddress(),
            $this->pageStatus->getServerWrapper()->getHttpUserAgent() ) ) {
            $this->applicationBuilder->getRedirector()->setURL($this->applicationBuilder->getSetup()->getBasePath() . 'public/login.html');
            $this->applicationBuilder->getRedirector()->redirect();
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
        /*
         * $this->applicationBuilder->getLogger()->write(
            'ERROR :: show_get_authorization_error_page illegal access from user **' .
            $this->pageStatus->getSessionWrapper()->getSessionUsename() .
            '** having group set to **' .
            $_SESSION['group'] .
            '** ', __FILE__, __LINE__);
        */
        $this->redirectToDefaultPage();
    }

    public function show_post_authorization_error_page() {
        /*
         * $this->applicationBuilder->getLogger()->write(
            'ERROR :: show_get_authorization_error_page illegal access from user **' .
            $this->pageStatus->getSessionWrapper()->getSessionUsename() .
            '** having group set to **' .
            $_SESSION['group'] .
            '** ', __FILE__, __LINE__);
        */
        $this->redirectToDefaultPage();
    }

    public function showPage() {
        $time_start = microtime(true);

        if ($this->pageStatus->getServerWrapper()->isGetRequest()) {
            $this->pageStatus->getSessionWrapper()->createCsrfToken();
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
            $this->applicationBuilder->getLogger()->write('WARNING TIME :: ' . $this->request->getInfo() . ' - TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    // ** next section load textual messages for messages block
    function setSuccess( string $success ) {
        $this->pageStatus->getSessionWrapper()->setMsgSuccess( $success );
    }

    function setError( string $error ) {
        $this->pageStatus->getSessionWrapper()->setMsgError( $error );
    }

    function setInfo( string $info ) {
        $this->pageStatus->getSessionWrapper()->setMsgInfo( $info );
    }

    function setWarning( string $warning ) {
        $this->pageStatus->getSessionWrapper()->setMsgWarning( $warning );
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
        $this->pageStatus->getSessionWrapper()->setFlashVariable( $flashvariable );
    }

    /**
     * This method return a variable set in the prevoius broser request.
     * To have a better understanging look at setFlashVariable description
     * 
     * @return [string] [variable that last for a request in the same session]
     */
    function getFlashVariable() : string {
        return $this->pageStatus->getSessionWrapper()->getFlashVariable();
    }

    /**
     * Return the SessionWrapper variable set for this controller
     */
    function getSessionWrapper() : SessionWrapper {
        return $this->pageStatus->getSessionWrapper();
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
        $this->applicationBuilder->getRedirector()->setURL($this->pageStatus->getSessionWrapper()->getSecondRequestedURL());
        $this->applicationBuilder->getRedirector()->redirect();
    }

    /**
     * Redirect the script to $_SESSION['prevprevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToSecondPreviousPage() {
        // avoid end of round here...
        $this->applicationBuilder->getRedirector()->setURL($this->pageStatus->getSessionWrapper()->getThirdRequestedURL());
        $this->applicationBuilder->getRedirector()->redirect();
    }

    /**
     * Redirect the script to a selected url
     */
    public function redirectToPage( $url ) {
        $this->applicationBuilder->getRedirector()->setURL( $url );
        $this->applicationBuilder->getRedirector()->redirect();
    }

    /**
     * Redirect the script to a selected url
     */
    public function redirectToDefaultPage() {
        $this->applicationBuilder->getRedirector()->setURL(
            $this->applicationBuilder->getRouterContainer()->makeRelativeUrl(
                $this->applicationBuilder->getRouterContainer()->getDefaultController()::CONTROLLER_NAME
            )
        );
        $this->applicationBuilder->getRedirector()->redirect();
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

        require_once $this->applicationBuilder->getSetup()->getHTMLTemplatePath() . $this->templateFile . '.php';
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

        /* new add once section */
        if (isset($container)) {
            if (gettype($container) == 'array') {
                $arraysHeads = array_reduce( $container, function ($carry, $htmlBlock) { return array_merge($carry, $htmlBlock->newAddToHeadOnce() ); }, [] );
                $this->addToHead .= array_reduce( $arraysHeads, function ($carry, $htmlCode) { return $carry . ' ' . $htmlCode; }, '' );
                $arraysFoots = array_reduce( $container, function ($carry, $htmlBlock) { return array_merge($carry, $htmlBlock->newAddToFootOnce() ); }, [] );
                $this->addToFoot .= array_reduce( $arraysFoots, function ($carry, $htmlCode) { return $carry . ' ' . $htmlCode; }, '' );
            }
            if (gettype($container) == 'object') {
                $this->addToHead .= array_reduce( $container->newAddToHeadOnce(), function ($carry, $htmlCode) { return $carry . ' ' . $htmlCode; }, '' );
                $this->addToFoot .= array_reduce( $container->newAddToFootOnce(), function ($carry, $htmlCode) { return $carry . ' ' . $htmlCode; }, '' );
            }
        }
        /* new add once section end */
    }

/*
        $this->securityChecker = $securityChecker;
        $this->logger          = $logger;
*/
    public function getInfo(): string {
        return '<br>'.$this->applicationBuilder->getRouterContainer()->getInfo().'<br>'.$this->pageStatus->getRequest()->getInfo().'<br>';
    }

}
