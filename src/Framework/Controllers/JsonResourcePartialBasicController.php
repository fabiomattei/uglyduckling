<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\Json\JsonLoader;
use Fabiom\UglyDuckling\Framework\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Framework\Loggers\Logger;
use Fabiom\UglyDuckling\Framework\Mailer\BaseMailer;
use Fabiom\UglyDuckling\Framework\SecurityCheckers\SecurityChecker;
use Fabiom\UglyDuckling\Framework\Status\Logics;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;
use Fabiom\UglyDuckling\Framework\Utils\ServerWrapper;
use Fabiom\UglyDuckling\Framework\Utils\SessionWrapper;

class JsonResourcePartialBasicController extends ControllerNoCSRFTokenRenew {

    const CONTROLLER_NAME = 'partial';

    protected $resource; // Json structure
    protected $resourceName;
    public array $resourceIndex;
    public array $groupsIndex;
    public array $useCasesIndex;
    protected $jsonTabTemplates;
    protected $jsonResourceTemplates;
    public DBConnection $dbconnection;
    public Logger $logger;
    public SecurityChecker $securityChecker;
    public BaseMailer $mailer;
    public PageStatus $pageStatus;

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

    /**
     * Check the presence of res variable in GET or POST array
     * Filter the string
     * load the json resource in $this->resource
     */
    public function check_and_load_resource() {
        if ( isset($this->resourceName) AND $this->resourceName != '' ) {
            // nothing to do here
        } else {
            $this->resourceName = filter_input(INPUT_POST | INPUT_GET, 'res', FILTER_UNSAFE_RAW);
        }
        if ( ! $this->resourceName ) {
            return false;
        } else {
            if ( strlen( $this->resourceName ) > 0 ) {
                $this->resource = JsonLoader::loadResource( $this->resourceIndex, $this->resourceName );
                return true;
            } else {
                throw new \Exception('Resource undefined');
            }
        }
        return false;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        // loading json resource
        if ( ! $jsonResourceName ) {
            echo 'missing resource name';
            $jsonResource = new \stdClass;
        } else {
            if ( strlen( $jsonResourceName ) > 0 ) {
                $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource( $jsonResourceName );
            } else {
                $jsonResource = new \stdClass;
            }
        }

        // if json resource was correctly loaded
        if ( is_object( $jsonResource ) ) {
            $this->templateFile = 'empty';

            // if json resource has parameters
            if(!isset($jsonResource->get->request) OR !isset($jsonResource->get->request->parameters)) {
                if ( isset($jsonResource->get->sessionupdates) ) $this->pageStatus->updateSession( $jsonResource->get->sessionupdates );

                $myBlocks = $this->applicationBuilder->getHTMLBlock( $jsonResource );
                echo $myBlocks->show();
            } else {
                $secondGump = new \Gump;

                $parametersGetter = BasicParameterGetter::parameterGetterFactory( $jsonResource, $this->applicationBuilder );
                $validation_rules = $parametersGetter->getValidationRoules();
                $filter_rules = $parametersGetter->getFiltersRoules();

                if ( count( $validation_rules ) == 0 ) {
                    // nothing to do
                } else {
                    $parms = $secondGump->sanitize( $_GET );
                    $secondGump->validation_rules( $validation_rules );
                    $secondGump->filter_rules( $filter_rules );
                    $cleanGETParameters = $secondGump->run( $parms );
                    $this->pageStatus->setGetParameters( $cleanGETParameters );
                    $this->unvalidated_parameters = $parms;
                    if ($secondGump->errors()) {
                        $this->readableErrors = $secondGump->get_readable_errors(true);
                    } else {
                        if ( isset($jsonResource->get->sessionupdates) ) $this->pageStatus->updateSession( $jsonResource->get->sessionupdates );

                        $myBlocks = $this->applicationBuilder->getHTMLBlock( $jsonResource );
                        echo $myBlocks->show();
                    }
                }
            }
        } else {
            echo 'resource '.$jsonResourceName.' undefined';
        }
    }

    /**
     * This method implements POST Request logic for all possible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function postRequest() {
        $this->templateFile = "empty";

        $this->applicationBuilder->getJsonloader()->loadIndex();

        // GETTING json resource name from parameter
        $jsonResourceName = filter_input(INPUT_POST | INPUT_GET, 'res', FILTER_UNSAFE_RAW);
        if ( ! $jsonResourceName ) {
            if ( isset( $_POST['res'] ) ) {
                $jsonResourceName = filter_var($_POST['res'], FILTER_UNSAFE_RAW);
            }
        }

        // loading json resource
        if ( ! $jsonResourceName ) {
            echo 'missing resource name';
            $jsonResource = new \stdClass;
        } else {
            if ( strlen( $jsonResourceName ) > 0 ) {
                $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource( $jsonResourceName );
            } else {
                $jsonResource = new \stdClass;
            }
        }

        // checking parameters
        $secondGump = new \Gump;
        if( isset($jsonResource->post->request) AND isset($jsonResource->post->request->postparameters)) {
            $parametersGetter = BasicParameterGetter::parameterGetterFactory( $jsonResource, $this->applicationBuilder );
            $validation_rules = $parametersGetter->getPostValidationRoules();
            $filter_rules = $parametersGetter->getPostFiltersRoules();

            $parms = $secondGump->sanitize( array_merge($_GET, $_POST) );
            $secondGump->validation_rules( $validation_rules );
            $secondGump->filter_rules( $filter_rules );
            $cleanPostParameters = $secondGump->run( $parms );
            $this->pageStatus->setPostParameters( $cleanPostParameters );
            $this->unvalidated_parameters = $parms;
        }
        if ($secondGump->errors()) {
            $this->pageStatus->addErrors( $secondGump->get_readable_errors() );
        } else {
            Logics::performTransactions( $this->pageStatus, $this->applicationBuilder, $jsonResource );

            Logics::performUseCases( $this->pageStatus, $this->applicationBuilder, $jsonResource );

            // if resource->get->sessionupdates is set I need to update the session
            if ( isset($this->resource->post->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->post->sessionupdates );


        }
        echo Logics::performAjaxCallPost( $this->pageStatus, $this->applicationBuilder, $jsonResource );
    }
}
