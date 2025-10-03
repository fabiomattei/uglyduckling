<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\DataBase\QueryExecuter;
use Fabiom\UglyDuckling\Framework\Json\JsonLoader;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonDefaultTemplateFactory;
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
    public $jsonTabTemplates;
    public $jsonResourceTemplates;
    public $index_json_smallpartial_templates;
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
        $this->templateFile = "empty";
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
     * @throws GeneralException
     */
    public function getRequest() {
        // loading json resource
        if ( strlen( $this->resourceName ) > 0 ) {
            $this->resource = JsonLoader::loadResource( $this->resourceIndex, $this->resourceName );
        } else {
            throw new \Exception('Resource undefined');
        }

        // if json resource was correctly loaded
        if ( is_object( $this->resource ) ) {
            $this->templateFile = 'empty';

            // if json resource has parameters
            if(!isset($this->resource->get->request) OR !isset($this->resource->get->request->parameters)) {
                if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

                $myBlocks = JsonDefaultTemplateFactory::getHTMLBlock( $this->resourceIndex, $this->jsonResourceTemplates, $this->jsonTabTemplates, $this->pageStatus, $this->resourceName );
                echo $myBlocks->show();
            } else {
                $secondGump = new \GUMP;

                $parametersGetter = BasicParameterGetter::parameterGetterFactory( $this->resource, $this->resourceIndex );
                $validation_rules = $parametersGetter->getValidationRoules();
                $filter_rules = $parametersGetter->getFiltersRoules();

                if ( count( $validation_rules ) == 0 ) {
                    // nothing to do
                    $myBlocks = JsonDefaultTemplateFactory::getHTMLBlock( $this->resourceIndex, $this->jsonResourceTemplates, $this->jsonTabTemplates, $this->pageStatus, $this->resourceName );
                    echo $myBlocks->show();
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
                        if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

                        $myBlocks = JsonDefaultTemplateFactory::getHTMLBlock( $this->resourceIndex, $this->jsonResourceTemplates, $this->jsonTabTemplates, $this->pageStatus, $this->resourceName );
                        echo $myBlocks->show();
                    }
                }
            }
        } else {
            echo 'resource '.$this->resourceName.' undefined';
        }
    }

    /**
     * This method implements POST Request logic for all possible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function postRequest() {
        $this->templateFile = 'empty';
        // loading json resource
        if ( strlen( $this->resourceName ) > 0 ) {
            $this->resource = JsonLoader::loadResource( $this->resourceIndex, $this->resourceName );
        } else {
            throw new \Exception('Resource undefined');
        }

        $conn = $this->pageStatus->getDbconnection()->getDBH();

        $returnedIds = $this->pageStatus->getQueryReturnedValues();

        // checking parameters
        $secondGump = new \GUMP;
        if( isset($this->resource->post->request) AND isset($this->resource->post->request->postparameters)) {
            $parametersGetter = BasicParameterGetter::parameterGetterFactory( $this->resource, $this->resourceIndex );
            $validation_rules = $parametersGetter->getPostValidationRoules();
            $filter_rules = $parametersGetter->getPostFiltersRoules();

            $secondGump->validation_rules($validation_rules);
            //$gump->set_fields_error_messages($filter_rules);
            $secondGump->filter_rules($filter_rules);
            $this->postParameters = $secondGump->run(array_merge(
                is_null($_GET) ? [] : $_GET,
                is_null($_POST) ? [] : $_POST,
                is_null($_FILES) ? [] : $_FILES
            ));
            $this->pageStatus->setPostParameters( $this->postParameters );
        }
        if ($secondGump->errors()) {
            $this->pageStatus->addErrors(
                (is_array($secondGump->get_readable_errors(true)) ? join('', $secondGump->get_readable_errors(true)) : $secondGump->get_readable_errors(true))
            );
        } else {
            if (isset($this->resource->post->transactions)) {
                try {
                    //$conn->beginTransaction();
                    $this->pageStatus->getQueryExecutor()->setDBH($conn);
                    foreach ($this->resource->post->transactions as $transaction) {
                        $this->pageStatus->getQueryExecutor()->setQueryStructure($transaction);
                        if ($this->pageStatus->getQueryExecutor()->getSqlStatmentType() == \Fabiom\UglyDuckling\Common\Database\QueryExecuter::INSERT) {
                            if (isset($transaction->label)) {
                                $returnedIds->setValue($transaction->label, $this->pageStatus->getQueryExecutor()->executeSql());
                            } else {
                                $returnedIds->setValueNoKey($this->pageStatus->getQueryExecutor()->executeSql());
                            }
                        } else if ($this->pageStatus->getQueryExecutor()->getSqlStatmentType() == QueryExecuter::SELECT) {
                            if (isset($transaction->label)) {
                                $returnedIds->setValue($transaction->label, $this->pageStatus->getQueryExecutor()->executeSql());
                            } else {
                                $returnedIds->setValueNoKey($this->pageStatus->getQueryExecutor()->executeSql());
                            }
                        } else {
                            $this->pageStatus->getQueryExecutor()->executeSql();
                        }
                    }
                    //$conn->commit();
                } catch (\PDOException $e) {
                    $this->pageStatus->addError("There was an error in the transaction");
                    $conn->rollBack();
                    $this->logger->write($e->getMessage(), __FILE__, __LINE__);
                }
            }
            
            Logics::performUseCases( $this->pageStatus, $this->resource, $this->useCasesIndex );

            // if resource->get->sessionupdates is set I need to update the session
            if ( isset($this->resource->post->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->post->sessionupdates );
        }
        echo Logics::performAjaxCallPost( $this->pageStatus, $this->resource );
    }
}
