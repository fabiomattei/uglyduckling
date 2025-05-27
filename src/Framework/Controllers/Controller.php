<?php

/**
 * User: Fabio Mattei
 * Date: 24/06/2019
 * Time: 09:49
 */

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\SecurityCheckers\SecurityChecker;
use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\Loggers\Logger;
use Fabiom\UglyDuckling\Framework\Mailer\BaseMailer;
use Fabiom\UglyDuckling\Framework\Utils\ServerWrapper;
use Fabiom\UglyDuckling\Framework\Utils\SessionWrapper;

class Controller extends CommonController {

    const CONTROLLER_NAME = 'controller';

    public string $templateFile;
    public /* GUMP */
        $gump;
    public /* array */
        $get_validation_rules = array();
    public /* array */
        $get_filter_rules = array();
    public /* array */
        $post_validation_rules = array();
    public /* array */
        $post_filter_rules = array();
    public /* array */
        $post_get_validation_rules = array();
    public /* array */
        $post_get_filter_rules = array();
    public /* array */
        $getParameters;
    public /* array */
        $postParameters;
    public /* array */
        $filesParameters;

    public $unvalidated_parameters;
    public $filteredParameters;

    public $title;
    public $menucontainer = array();
    public $topcontainer = array();
    public $messagescontainer = array();
    public $leftcontainer = array();
    public $rightcontainer = array();
    public $centralcontainer = array();
    public $secondcentralcontainer = array();
    public $thirdcentralcontainer = array();
    public $bottomcontainer = array();
    public $sidebarcontainer = array();

    public $addToHead = '';
    public $addToFoot = '';
    public $subAddToHead = '';
    public $subAddToFoot = '';
    public $parameters;
    public $flashvariable;
    public $readableErrors;
    public DBConnection $dbconnection;
    public array $groupsIndex;
    public $controllerName;

    /**
     * This method makes all necessary presets to activate a controller
     * @throws \Exception
     */
    public function makeAllPresets(DBConnection $dbconnection, Logger $logger, SecurityChecker $securityChecker, BaseMailer $mailer) {
        parent::makeAllPresets($dbconnection, $logger, $securityChecker, $mailer);
        $this->gump = new \GUMP();
    }

    public function setDBConnection( DBConnection $dbconnection ) {
        $this->dbconnection = $dbconnection;
    }

    public function setGroupsIndex( $groupsIndex ) {
        $this->groupsIndex = $groupsIndex;
    }

    public function setControllerName( $controllerName ) {
        $this->controllerName = $controllerName;
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

            // checking get parameters in post request
            $parms = $this->gump->sanitize($_GET);
            $this->gump->validation_rules($this->post_get_validation_rules);
            $this->gump->filter_rules($this->post_get_filter_rules);
            $this->getParameters = $this->gump->run($parms);
            $this->unvalidated_parameters = $parms;
            if ($this->getParameters === false) {
                $this->readableErrors = $this->gump->get_readable_errors(true);
                $out = false;
            } else {
                $out = true;
            }

            // checking post parameters in post request
            $parms = $this->gump->sanitize($_POST);
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
     * @throws Exception
     */
    public function show_get_error_page()
    {
        throw new \Exception('Error page exception function show_get_error_page()');
    }

    /**
     * This method has to be overriden, if id does not it throws an unhandled ErrorPageException
     * The ovverriding method need to show the page containing the errors that prevent the validation to pass
     *
     * @throws Exception
     */
    public function show_post_error_page()
    {
        throw new \Exception('Error page exception function show_post_error_page()');
    }

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_get_request()
    {
        return true;
    }

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_post_request()
    {
        return true;
    }

    public function showPage()
    {
        $time_start = microtime(true);

        if (ServerWrapper::isGetRequest()) {
            SessionWrapper::createCsrfToken();
            if ($this->check_authorization_get_request()) {
                if ($this->check_get_request()) {
                    $this->getRequest();
                } else {
                    $this->show_get_error_page();
                }
            } else {
                $this->check_authorization_get_request();
            }
        } else {
            if ($this->check_authorization_post_request()) {
                if ($this->check_post_request()) {
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
            $this->pageStatus->logger->write('WARNING TIME :: TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    /**
     * Function for setting parameters array
     */
    public function setGetParameters($parameters)
    {
        if (is_array($parameters)) {
            $this->getParameters = $parameters;
        }
    }

    /**
     * Function for setting parameters array
     */
    public function setPostParameters($parameters)
    {
        if (is_array($parameters)) {
            $this->postParameters = $parameters;
        }
    }

    /**
     * Function for setting parameters array
     */
    public function setFilesParameters($parameters)
    {
        if (is_array($parameters)) {
            $this->filesParameters = $parameters;
        }
    }

    // taken from page script
    function loadTemplate()
    {
        $this->addToHeadAndToFoot($this->menucontainer);
        $this->addToHeadAndToFoot($this->topcontainer);
        $this->addToHeadAndToFoot($this->messagescontainer);
        $this->addToHeadAndToFoot($this->leftcontainer);
        $this->addToHeadAndToFoot($this->centralcontainer);
        $this->addToHeadAndToFoot($this->secondcentralcontainer);
        $this->addToHeadAndToFoot($this->thirdcentralcontainer);
        $this->addToHeadAndToFoot($this->bottomcontainer);

        require_once 'src/Templates/' . $this->templateFile . '.php';
    }

    function addToHeadAndToFoot($container)
    {
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
                $arraysHeads = array_reduce($container, function ($carry, $htmlBlock) {
                    return array_merge($carry, $htmlBlock->newAddToHeadOnce());
                }, []);
                $this->addToHead .= array_reduce($arraysHeads, function ($carry, $htmlCode) {
                    return $carry . ' ' . $htmlCode;
                }, '');
                $arraysFoots = array_reduce($container, function ($carry, $htmlBlock) {
                    return array_merge($carry, $htmlBlock->newAddToFootOnce());
                }, []);
                $this->addToFoot .= array_reduce($arraysFoots, function ($carry, $htmlCode) {
                    return $carry . ' ' . $htmlCode;
                }, '');
            }
            if (gettype($container) == 'object') {
                $this->addToHead .= array_reduce($container->newAddToHeadOnce(), function ($carry, $htmlCode) {
                    return $carry . ' ' . $htmlCode;
                }, '');
                $this->addToFoot .= array_reduce($container->newAddToFootOnce(), function ($carry, $htmlCode) {
                    return $carry . ' ' . $htmlCode;
                }, '');
            }
        }
        /* new add once section end */
    }

    /*
            $this->securityChecker = $securityChecker;
            $this->logger          = $logger;
    */
    public function getInfo(): string
    {
        return '<br>Controller: ' . '<br>';
    }

}
