<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\Loggers\Logger;
use Fabiom\UglyDuckling\Framework\Mailer\BaseMailer;
use Fabiom\UglyDuckling\Framework\SecurityCheckers\SecurityChecker;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;
use Fabiom\UglyDuckling\Framework\Utils\ServerWrapper;
use Fabiom\UglyDuckling\Framework\Utils\SessionWrapper;

class CommonController {

    protected $resourceName;
    public PageStatus $pageStatus;
    public array $resourceIndex;
    public array $groupsIndex;
    public array $useCasesIndex;
    protected $jsonTabTemplates;
    protected $jsonResourceTemplates;
    public Logger $logger;
    public SecurityChecker $securityChecker;
    public BaseMailer $mailer;
    public DBConnection $dbconnection;

    /**
     * This function allows to set a resource name to load for a particular instance
     * This helps in case a resource want to be set at programming time and not
     * at run time.
     * @param string $resourceName   the name of the json resource we want to load
     */
    public function setResourceName(string $resourceName) {
        $this->resourceName = $resourceName;
    }

    public function setPageStatus($pageStatus) {
        $this->pageStatus = $pageStatus;
    }

    public function setResourceIndex( $resourceIndex ) {
        $this->resourceIndex = $resourceIndex;
    }

    public function setGroupsIndex( $groupsIndex ) {
        $this->groupsIndex = $groupsIndex;
    }

    public function setUseCasesIndex( $useCasesIndex ) {
        $this->useCasesIndex = $useCasesIndex;
    }

    public function setJsonTagTemplates( $index_json_tag_templates ) {
        $this->jsonTabTemplates = $index_json_tag_templates;
    }

    public function setJsonResourceTemplates( $index_json_resource_templates ) {
        $this->jsonResourceTemplates = $index_json_resource_templates;
    }

    public function setJsonSmallPartialTemplates( $index_json_smallpartial_templates ) {
        $this->index_json_smallpartial_templates = $index_json_smallpartial_templates;
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

    public function show_get_authorization_error_page() {
        if ( defined('APPLICATION_ENVIRONMENT') and APPLICATION_ENVIRONMENT === 'development' ) {
            $this->pageStatus->logger->write(
                'ERROR :: show_get_authorization_error_page illegal access from user **' .
                $_SESSION['username'] .
                '** having group set to **' .
                $_SESSION['group'] .
                '** ', __FILE__, __LINE__);
        } {
            $this->redirectToDefaultPage();
        }
    }

    public function show_post_authorization_error_page() {
        if ( defined('APPLICATION_ENVIRONMENT') and APPLICATION_ENVIRONMENT === 'development' ) {
            $this->pageStatus->logger->write(
                'ERROR :: show_get_authorization_error_page illegal access from user **' .
                $_SESSION['username'] .
                '** having group set to **' .
                $_SESSION['group'] .
                '** ', __FILE__, __LINE__);
        } {
            $this->redirectToDefaultPage();
        }
    }

    /**
     * Redirect the script to a selected url
     */
    public function redirectToDefaultPage() {
        // header('Location: ' . getenv("BASE_PATH") . getenv("PATH_TO_APP"));
        if (defined('BASE_PATH') AND defined('DEFAULT_PAGE')) {
            header('Location: ' . BASE_PATH . DEFAULT_PAGE );
        } else if (defined('BASE_PATH')) {
            header('Location: ' . BASE_PATH . 'index.html' );
        }
        header('Location: index.html');

        exit();
    }

    /**
     * This method has to be overriden, if id does not it throws an unhandled ErrorPageException
     * The ovverriding method need to show the page containing the errors that prevent the validation to pass
     *
     * @throws Exception
     */
    public function show_get_error_page() {
        if ( defined('APPLICATION_ENVIRONMENT') and APPLICATION_ENVIRONMENT === 'development' ) {
            print_r($this->readableErrors);
        } {
            header('Location: ' . getenv("BASE_PATH") . getenv("PATH_TO_APP"));
        }
    }

    /**
     * This method has to be overriden, if id does not it throws an unhandled ErrorPageException
     * The ovverriding method need to show the page containing the errors that prevent the validation to pass
     *
     * @throws Exception
     */
    public function show_post_error_page() {
        if ( defined('APPLICATION_ENVIRONMENT') and APPLICATION_ENVIRONMENT === 'development' ) {
            print_r($this->readableErrors);
        } {
            header('Location: ' . getenv("BASE_PATH") . getenv("PATH_TO_APP"));
        }
    }

    /**
     * Redirect the script to $_SESSION['prevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToPreviousPage() {
        if (defined('BASE_PATH')) {
            header('Location: ' . BASE_PATH . $_SESSION['prevrequest'] );
        }
        header('Location: ' . $_SESSION['prevrequest'] );
        exit();
    }

    /**
     * Redirect the script to $_SESSION['prevprevrequest'] with a header request
     * It send flash messages to new controller [info, warning, error, success]
     */
    public function redirectToSecondPreviousPage() {
        if (defined('BASE_PATH')) {
            header('Location: ' . BASE_PATH . $_SESSION['prevprevrequest'] );
        }
        header('Location: ' . $_SESSION['prevprevrequest'] );
        exit();
    }

    /**
     * Redirect the script to a selected url
     */
    public function redirectToPage($url) {
        if (defined('BASE_PATH')) {
            header('Location: ' . BASE_PATH . $url );
        }
        header('Location: ' . $url );
        exit();
    }
}
