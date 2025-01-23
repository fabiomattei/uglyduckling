<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\SecurityCheckers\SecurityChecker;
use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\Loggers\Logger;
use Fabiom\UglyDuckling\Framework\Mailer\BaseMailer;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;
use Fabiom\UglyDuckling\Framework\Utils\ServerWrapper;
use Fabiom\UglyDuckling\Framework\Utils\SessionWrapper;
use Fabiom\UglyDuckling\Framework\Utils\StringUtils;

class BaseController {
    
    public string $templateFile;
    public /* GUMP */
        $gump;
    public /* array */
        $get_validation_rules = [];
    public /* array */
        $get_filter_rules = [];
    public /* array */
        $post_validation_rules = [];
    public /* array */
        $post_filter_rules = [];
    public /* array */
        $getParameters;
    public /* array */
        $postParameters;
    public /* array */
        $filesParameters;

    public /* array */
        $parameters;
    public DBConnection $dbconnection;
    public string $classCompleteName;
    public string $className;
    public string $chapter;
    public string $viewFile;
    public $controllerPointer;
    public string $appTitle;
    public string $headViewFile = '';
    public string $footViewFile = '';
    public Logger $logger;
    public SecurityChecker $securityChecker;
    public BaseMailer $mailer;
    public array $groupsIndex;
    public $unvalidated_parameters;
    public $menubuilder;
    public $menucontainer;
    public $controllerName;
    public PageStatus $pageStatus;
    public $readableErrors;

    public function __construct() {
        $this->gump = new \GUMP();
        $this->parameters = [];
    }

    public function setControllerName( $controllerName ) {
        $this->controllerName = $controllerName;
    }

    public function setPageStatus( PageStatus $pageStatus ) {
        $this->pageStatus = $pageStatus;
    }

    public function setDBConnection( DBConnection $dbconnection ) {
        $this->dbconnection = $dbconnection;
    }

    public function setGroupsIndex( $groupsIndex ) {
        $this->groupsIndex = $groupsIndex;
    }

    /**
     * This method makes all necessary presets to activate a controller
     * @throws \Exception
     */
    public function makeAllPresets(DBConnection $dbconnection, Logger $logger, SecurityChecker $securityChecker, BaseMailer $mailer) {
        // setting an array containing all parameters
        $this->parameters = [];
        $this->logger = $logger;
        $this->securityChecker = $securityChecker;
        $this->mailer = $mailer;
        $this->dbconnection = $dbconnection;

        if ( !$this->securityChecker->isSessionValid(
            SessionWrapper::getSessionLoggedIn(),
            SessionWrapper::getSessionIp(),
            SessionWrapper::getSessionUserAgent(),
            SessionWrapper::getSessionLastLogin(),
            ServerWrapper::getRemoteAddress(),
            ServerWrapper::getHttpUserAgent() ) ) {
                header('Location: ' . getenv("BASE_PATH") . getenv("PATH_TO_APP"));
        }
    }

    public function isGetRequest() {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    public function isPostRequest() {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }

    public function isSessionValid() {
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

    /**
     * Method to override (eventually)
     */
    public function getRequest()
    {
        echo 'not implemented yet';
    }

    /**
     * Method to override (eventually)
     */
    public function postRequest()
    {
        echo 'not implemented yet';
    }

    /**
     * Function for setting parameters array
     */
    public function setGetParameters($parameters) {
    }

    /**
     * Function for setting parameters array
     */
    public function setPostParameters($parameters) {
    }

    /**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function check_get_request()
    {
        if (count($this->get_validation_rules) == 0) {
            return true;
        } else {
            $parms = $this->gump->sanitize($_GET);
            $this->gump->validation_rules($this->get_validation_rules);
            $this->gump->filter_rules($this->get_filter_rules);
            $this->getParameters = $this->gump->run($parms);
            $this->unvalidated_parameters = $parms;
            if ($this->getParameters === false) {
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
    public function check_post_request()
    {
        if (count($this->post_validation_rules) == 0) {
            return true;
        } else {
            $out = false;

            // checking post parameters in post request
            $parms = $this->gump->sanitize(array_merge($_POST, $_FILES));
            $this->gump->validation_rules($this->post_validation_rules);
            $this->gump->filter_rules($this->post_filter_rules);
            $this->postParameters = $this->gump->run($parms);
            $this->unvalidated_parameters = $parms;
            if ($this->postParameters === false) {
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
         * $this->pageStatus->logger->write(
            'ERROR :: show_get_authorization_error_page illegal access from user **' .
            $_SESSION['username'] .
            '** having group set to **' .
            $_SESSION['group'] .
            '** ', __FILE__, __LINE__);
        */
        $this->redirectToDefaultPage();
    }

    public function show_post_authorization_error_page()
    {
        /*
         * $this->pageStatus->logger->write(
            'ERROR :: show_get_authorization_error_page illegal access from user **' .
            $_SESSION['username'] .
            '** having group set to **' .
            $_SESSION['group'] .
            '** ', __FILE__, __LINE__);
        */
        $this->redirectToDefaultPage();
    }

    /**
     * Saving the request made to webserver but only for get requests
     * It saves the STRING in $_SESSION['request'] variable and moves the previous request
     * to STRING $_SESSION['prevrequest']
     *
     * @param $request STRING containing URL complete of parameters
     */
    public function setRequest($request) {
        if ( $this->isGetRequest() ) {
            $this->request = $request;
            $_SESSION['prevprevrequest'] = ( isset($_SESSION['prevrequest']) ? $_SESSION['prevrequest'] : '' );
            $_SESSION['prevrequest'] = ( isset($_SESSION['request']) ? $_SESSION['request'] : '' );
            $_SESSION['request'] = $request;
        }
    }

    public function showPage() {
        $time_start = microtime(true);

        if ($this->isGetRequest()) {
            $this->createCsrfToken();
            if ($this->check_authorization_get_request()) {
                if ($this->check_get_request()) {
                    $this->getRequest();
                    $this->viewFile .= 'Get.php';
                } else {
                    $this->show_get_error_page();
                    $this->viewFile .= 'GetError.php';
                }
            } else {
                $this->check_authorization_get_request();
            }
        } else {
            if ($this->check_authorization_post_request()) {
                if ($this->check_post_request()) {
                    $this->postRequest();
                    $this->viewFile .= 'Post.php';
                } else {
                    $this->show_post_error_page();
                    $this->viewFile .= 'PostError.php';
                }
            } else {
                $this->check_authorization_post_request();
            }
        }

        $this->loadTemplate();

        $time_end = microtime(true);
        if (($time_end - $time_start) > 5) {
            $this->pageStatus->logger->write('WARNING TIME :: ' . $this->request->getInfo() . ' - TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    // ** next section load textual messages for messages block
    function setSuccess(string $success) {
        $_SESSION['msgsuccess'] = $success;
    }

    function setError(string $error) {
        $_SESSION['msgerror'] = $error;
    }

    function setInfo(string $info) {
        $_SESSION['msginfo'] = $info;
    }

    function setWarning(string $warning) {
        $_SESSION['msgwarning'] = $warning;
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

    /**
     * Redirect the script to $_SESSION['prevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToPreviousPage() {
        header('Location: ' . $_SESSION['prevrequest'] );
        exit();
    }

    /**
     * Redirect the script to $_SESSION['prevprevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToSecondPreviousPage() {
        // avoid end of round here...
        header('Location: ' . $_SESSION['prevprevrequest'] );
        exit();
    }

    /**
     * Redirect the script to a selected url
     */
    public function redirectToPage($url) {
        header('Location: ' . $url );
        exit();
    }

    /**
     * Redirect the script to a selected url
     */
    public function redirectToDefaultPage() {
        header('Location: login.html');
        exit();
    }

    // taken from page script
    function loadTemplate() {
        /*
        $file = 'views/' . $filename. '.php';
        if( !is_readable($file) ){
            throw new Exception("View $file not found!", 1);
        }
        */
        /*
        print_r(get_declared_classes());
        print_r(get_object_vars($this->controllerPointer));
        */
        ob_start() && extract(get_object_vars($this->controllerPointer), EXTR_SKIP);
        require_once 'src/Templates/' . $this->templateFile . '.php';
        return ob_end_flush();
    }

    /*
            $this->securityChecker = $securityChecker;
            $this->logger          = $logger;
    */
    public function getInfo(): string {
        return '<br>' . $this->applicationBuilder->getRouterContainer()->getInfo() . '<br>' . $this->pageStatus->getRequest()->getInfo() . '<br>';
    }

    public function createCsrfToken() {
        $_SESSION['csrftoken'] = StringUtils::generateRandomString( 40 );
    }

}