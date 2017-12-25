<?php

namespace Firststep\Controllers;

// use templates\blocks\message\Messages;
// use core\libs\gump\GUMP;

class Controller {

    public $get_validation_rules = array();
    public $get_filter_rules = array();
    public $post_validation_rules = array();
    public $post_filter_rules = array();

    public function __construct( $setup, $request, $homeRedirector ) {
        $this->setup          = $setup;
        $this->request        = $request;
        $this->homeRedirector = $homeRedirector;

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

        // $this->gump = new GUMP();

        if ( !$this->request->isSessionValid() ) {
            $this->homeRedirector->redirect();
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
        throw new \Exception('General malfuction!!!');
    }

    public function show_post_error_page() {
        throw new \Exception('General malfuction!!!');
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
        throw new \Exception('Authorization error!!!');
    }

    public function show_post_authorization_error_page() {
        throw new \Exception('Authorization error!!!');
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
            $logger = new Logger();
            $logger->write('WARNING TIME :: ' . $_SERVER["REQUEST_METHOD"] . ' ' . $_SERVER['PHP_SELF'] . ' ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    // ** next section load textual messages for messages block
    function setSuccess($success) {
        $this->messages->setSuccess($success);
    }

    function setError($error) {
        $this->messages->setError($error);
    }

    function setInfo($info) {
        $this->messages->setInfo($info);
    }

    function setWarning($warning) {
        $this->messages->setWarning($warning);
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
    function setFlashVariable( $flashvariable ) {
        $this->request->setSessionFlashVariable( $flashvariable );
    }

    /**
     * This method return a variable set in the prevoius broser request.
     * To have a better understanging look at setFlashVariable description
     * 
     * @return [string] [variable that last for a request in the same session]
     */
    function getFlashVariable() {
        return $this->request->getSessionFlashVariable();
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
     * Saving the request made to webserver
     * It saves the STRING in $_SESSION['request'] variable and moves the previous request
     * to STRING $_SESSION['prevrequest']
     *
     * @param $request STRING containing URL complete of parameters
     */
    public function setRequest( $requestedUrl ) {
        $this->request->setRequestedURL( $requestedUrl );
    }

    /**
     * Redirect the script to $_SESSION['prevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToPreviousPage() {
        if ($this->messages->info != '')
            $_SESSION['msginfo'] = $this->messages->info;
        if ($this->messages->warning != '')
            $_SESSION['msgwarning'] = $this->messages->warning;
        if ($this->messages->error != '')
            $_SESSION['msgerror'] = $this->messages->error;
        if ($this->messages->success != '')
            $_SESSION['msgsuccess'] = $this->messages->success;
        if (isset($this->flashvariable) AND $this->flashvariable != '')
            $_SESSION['flashvariable'] = $this->flashvariable;
        header('Location: ' . BASEPATH . $_SESSION['prevrequest']);
    }

    /**
     * Redirect the script to $_SESSION['prevprevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToSecondPreviousPage() {
        if ($this->messages->info != '')
            $_SESSION['msginfo'] = $this->messages->info;
        if ($this->messages->warning != '')
            $_SESSION['msgwarning'] = $this->messages->warning;
        if ($this->messages->error != '')
            $_SESSION['msgerror'] = $this->messages->error;
        if ($this->messages->success != '')
            $_SESSION['msgsuccess'] = $this->messages->success;
        if (isset($this->flashvariable) AND $this->flashvariable != '')
            $_SESSION['flashvariable'] = $this->flashvariable;
        header('Location: ' . BASEPATH . $_SESSION['prevprevrequest']);
    }

    /**
     * Redirect the script to a selected page
     * it creates the url using the library function make_url form loaders.php
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToPage($group = 'main', $action = '', $parameters = '', $extension = '.html') {
        if ($this->messages->info != '')
            $_SESSION['msginfo'] = $this->messages->info;
        if ($this->messages->warning != '')
            $_SESSION['msgwarning'] = $this->messages->warning;
        if ($this->messages->error != '')
            $_SESSION['msgerror'] = $this->messages->error;
        if ($this->messages->success != '')
            $_SESSION['msgsuccess'] = $this->messages->success;
        if (isset($this->flashvariable) AND $this->flashvariable != '')
            $_SESSION['flashvariable'] = $this->flashvariable;
        header( 'Location: ' . make_url($group, $action, $parameters, $extension) );
    }

    /**
     * Saving URL controller PATH in the controller
     *
     * @param $family      STRING coming from URL slicing
     * @param $subfamily   STRING coming from URL slicing
     * @param $aggregator  STRING coming from URL slicing
     */
    public function setControllerPath($family, $subfamily, $aggregator) {
        $this->family = $family;
        $this->subfamily = $subfamily;
        $this->aggregator = $aggregator;
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
