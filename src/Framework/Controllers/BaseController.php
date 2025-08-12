<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\Utils\StringUtils;

class BaseController extends CommonController {
    
    public string $templateFile;
    public /* GUMP */ $gump;
    public /* array */ $get_validation_rules = [];
    public /* array */ $get_filter_rules = [];
    public /* array */ $post_validation_rules = [];
    public /* array */ $post_filter_rules = [];
    public /* array */ $getParameters;
    public /* array */ $postParameters;
    public /* array */ $filesParameters;
    public /* array */ $parameters;

    public string $classCompleteName;
    public string $className;
    public string $chapter;
    public string $viewFile;
    public $controllerPointer;
    public string $appTitle;
    public string $headViewFile = '';
    public string $footViewFile = '';
    public array $groupsIndex;
    public $unvalidated_parameters;
    public $menubuilder;
    public $menucontainer;
    public $controllerName;
    public $readableErrors;
    public $request;
    public $flashvariable;

    public function __construct() {
        $this->gump = new \GUMP();
        $this->parameters = [];
    }

    public function setControllerName( $controllerName ) {
        $this->controllerName = $controllerName;
    }

    public function setDBConnection( DBConnection $dbconnection ) {
        $this->dbconnection = $dbconnection;
    }

    public function setTemplateFile( $templateFile ) {
        $this->templateFile = $templateFile;
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
                $this->redirectToPage('index.html');
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
                $this->redirectToPage('index.html');
            }
        }

        $this->loadTemplate();

        $time_end = microtime(true);
        if (($time_end - $time_start) > 5) {
            $this->pageStatus->logger->write('WARNING TIME :: ' . $this->request->getInfo() . ' - TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
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
        //ob_start() && extract(get_object_vars($this->controllerPointer), EXTR_SKIP);
        extract(get_object_vars($this->controllerPointer), EXTR_SKIP);
        require 'src/Templates/' . $this->templateFile . '.php';
        //return ob_end_flush();
    }

    /*
            $this->securityChecker = $securityChecker;
            $this->logger          = $logger;
    */
    public function getInfo(): string {
        return '<br>' . $this->request . '<br>';
    }

    public function createCsrfToken() {
        $_SESSION['csrftoken'] = StringUtils::generateRandomString( 40 );
    }

}