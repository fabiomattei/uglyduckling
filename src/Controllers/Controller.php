<?php

namespace Firststep\Controllers;

// use templates\blocks\message\Messages;
// use core\libs\gump\GUMP;

class Controller {

    public $get_validation_rules = array();
    public $get_filter_rules = array();
    public $post_validation_rules = array();
    public $post_filter_rules = array();

    public function __construct() {
        // setting an array containing all parameters
        $this->parameters = array();

        // $this->messages = new Messages();

        $this->title = APPNAMEFORPAGETITLE;
        $this->menucontainer = array();
        $this->topcontainer = array();
        $this->messagescontainer = array($this->messages);
        $this->leftcontainer = array();
        $this->rightcontainer = array();
        $this->centralcontainer = array();
        $this->secondcentralcontainer = array();
        $this->thirdcentralcontainer = array();
        $this->bottomcontainer = array();
        $this->sidebarcontainer = array();
        $this->templateFile = PRIVATETEMPLATE;

        $this->addToHead = '';
        $this->addToFoot = '';

        if (isset($_SESSION['msginfo'])) {
            $this->messages->info = $_SESSION['msginfo'];
            unset($_SESSION['msginfo']);
        }
        if (isset($_SESSION['msgwarning'])) {
            $this->messages->warning = $_SESSION['msgwarning'];
            unset($_SESSION['msgwarning']);
        }
        if (isset($_SESSION['msgerror'])) {
            $this->messages->error = $_SESSION['msgerror'];
            unset($_SESSION['msgerror']);
        }
        if (isset($_SESSION['msgsuccess'])) {
            $this->messages->success = $_SESSION['msgsuccess'];
            unset($_SESSION['msgsuccess']);
        }
        if (isset($_SESSION['flashvariable'])) {
            $this->flashvariable = $_SESSION['flashvariable'];
            unset($_SESSION['flashvariable']);
        }

        // $this->gump = new GUMP();

        if ( !$this->isSessionValid() ) {
            header('Location: ' . BASEPATH . 'public/login.html');
            die();
        }
    }

    /**
     * Setting the object containing all request attributes
     * 
     * @param Firststep\Request\Request $request it contains all the attributes of the page
     */
    public function setRequest( $request ) {
        $this->request = $request;
    }
	
	public function setOffice( $office ) {
		$this->office = $office;
	}

    public function isGetRequest() {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    public function isPostRequest() {
        return $_SERVER["REQUEST_METHOD"] == "POST";
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

        if ($this->isGetRequest()) {
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

    private function isSessionValid() {
        // check if user logged in
        if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'])) {
            return false;
        }

        // check if ip matches
        if (!isset($_SESSION['ip']) || !isset($_SERVER['REMOTE_ADDR'])) {
            return false;
        }
        if (!$_SESSION['ip'] === $_SERVER['REMOTE_ADDR']) {
            return false;
        }

        // check user agent
        if (!isset($_SESSION['user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        if (!$_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
            return false;
        }

        // check elapsed time
        $max_elapsed = 60 * 60 * 24; // 1 day
        // return false if value is not set
        if (!isset($_SESSION['last_login'])) {
            return false;
        }
        if (!($_SESSION['last_login'] + $max_elapsed) >= time()) {
            return false;
        }

        return true;
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
    function setFlashVariable($flashvariable) {
        $_SESSION['flashvariable'] = $flashvariable;
    }

    /**
     * This method return a variable set in the prevoius broser request.
     * To have a better understanging look at setFlashVariable description
     * 
     * @return [string] [variable that last for a request in the same session]
     */
    function getFlashVariable() {
        return $this->flashvariable;
    }

    /*     * * functions for setting parameters array */

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
    public function setRequestedUrl($requestedUrl) {
        $_SESSION['prevprevrequest'] = ( isset($_SESSION['prevrequest']) ? $_SESSION['prevrequest'] : '' );
        $_SESSION['prevrequest'] = ( isset($_SESSION['request']) ? $_SESSION['request'] : '' );
        $_SESSION['request'] = $requestedUrl;
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
