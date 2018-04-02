<?php

namespace Firststep\Controllers;

// use templates\blocks\message\Messages;
use Firststep\Exceptions\ErrorPageException;
use Firststep\Exceptions\AuthorizationException;
use Firststep\Redirectors\Redirector;
use Firststep\Loggers\Logger;
use Firststep\Request\Request;
use Firststep\Setup\Setup;
use GUMP;

class Controller {

    public $get_validation_rules = array();
    public $get_filter_rules = array();
    public $post_validation_rules = array();
    public $post_filter_rules = array();

    public function __construct( Setup $setup, Request $request, Redirector $urlredirector, Logger $logger ) {
        $this->setup         = $setup;
        $this->request       = $request;
        $this->urlredirector = $urlredirector;
        $this->logger        = $logger;
        $this->gump          = new GUMP();

        // setting an array containing all parameters
        $this->parameters = array();

        // $this->messages = new Messages();

        $this->title = $this->setup->getAppNameForPageTitle();
        $this->menucontainer = array();
        $this->topcontainer = array();
        // $this->messagescontainer = array( $this->messages );
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

        /*
        $this->messages->info = $_SESSION['msginfo'];
        $this->messages->warning = $_SESSION['msgwarning'];
        $this->messages->error = $_SESSION['msgerror'];
        $this->messages->success = $_SESSION['msgsuccess'];
        $this->flashvariable = $_SESSION['flashvariable'];
        here we sould be calling the end of round in globals object
        */

        

        if ( !$this->request->isSessionValid() ) {
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

        if ($this->request->isGetRequest()) {
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
     * Redirect the script to a selected page
     * it creates the url using the library function make_url form loaders.php
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToPage($group = 'main', $action = '', $parameters = '', $extension = '.html') {
        $this->urlredirector->setURL( make_url($group, $action, $parameters, $extension) );
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

        require_once 'templates/' . $this->templateFile . '.php';
    }

    function addToHeadAndToFoot($container) {
        if (isset($container)) {
            if (gettype($container) == 'array') {
                foreach ($container as $obj) {
                    $this->addToHead .= $obj->addToHead();
                    $this->addToFoot .= $obj->addToFoot();
                }
            }
            if (gettype($container) == 'object') {
                $this->addToHead .= $container->addToHead();
                $this->addToFoot .= $container->addToFoot();
            }
        }
    }

}
