<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonDefaultTemplateFactory;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Menu\MenuJsonTemplate;
use Fabiom\UglyDuckling\Framework\DataBase\QueryExecuter;
use Fabiom\UglyDuckling\Framework\Json\JsonLoader;
use Fabiom\UglyDuckling\Framework\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Framework\Utils\FileUpload;
use Fabiom\UglyDuckling\Framework\Utils\SessionWrapper;
use Fabiom\UglyDuckling\Framework\Utils\UrlServices;

class JsonResourceController extends CommonController {

    protected $resource; // Json structure
    /* TODO remove following parameter */
    protected $internalGetParameters;

    protected $secondGump;
    public /* array */ $parameters;
    public /* array */ $postParameters;
    public $queryExecutor;
    protected $menubuilder;
    protected string $title;
    protected string $templateFile;
    protected $menucontainer;
    protected $leftcontainer;
    protected $centralcontainer;
    public $unvalidated_parameters;
    public $readableErrors;

    /**
     * This method has to be implemented by inherited class
     * It checks if a user belongs to a group that has access the requested resource
     */
    public function check_authorization_resource_request(): bool {
        if( !isset($this->resource->allowedgroups) ) {
            //$this->logger->write('ERROR :: allowedgroups array undefined for resource ' . $this->resourceName, __FILE__, __LINE__);
            return false;
        } elseif ( count($this->resource->allowedgroups) == 0) {
            return true;
        } elseif ( in_array( $_SESSION['group'], $this->resource->allowedgroups ) ) {
            return true;
        } else {
            //$this->logger->write('ERROR :: illegal access to resource' . $this->resourceName .' from user having group set to **' . $_SESSION['group'] .'** ', __FILE__, __LINE__);
            return false;
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

    public function getRequest() {
        $menuresource = JsonLoader::loadResource( $this->groupsIndex, $_SESSION['group'] );
        $this->menubuilder = new MenuJsonTemplate( $this->pageStatus, $this->resourceName, $this->resource->name);
        $this->menubuilder->setMenuStructure( $menuresource );

        // if resource->get->sessionupdates is set I need to update the session
        if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

        $returnedVariables = [];

        // performing usecases
        if (isset($this->resource->get->usecases) and is_array($this->resource->get->usecases)) {
            foreach ($this->resource->get->usecases as $jsonusecase) {
                $useCase = new $this->useCasesIndex[$jsonusecase->name]( $jsonusecase, $this->pageStatus );
                $useCase->loadParameters();
                $useCase->performAction();
                if ( isset($jsonusecase->returnedvariable) ) {
                    $returnedVariables[$jsonusecase->returnedvariable] = $useCase->returnValue();
                }
            }
        }

        $this->pageStatus->setReturnedVariables( $returnedVariables );

        $this->title = APP_NAME . ' :: Dashboard';
        $this->templateFile = $this->resource->templatefile ?? TEMPLATE_FILE_NAME;

        $this->menucontainer    = [ $this->menubuilder->createMenu() ];
        $this->leftcontainer    = [];
        $this->centralcontainer = [ JsonDefaultTemplateFactory::getHTMLBlock( $this->resourceIndex, $this->jsonResourceTemplates, $this->jsonTabTemplates, $this->pageStatus, $this->resourceName ) ];
    }

    /**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function second_check_get_request() {
        // checking if resource defines any get parameter
        if(!isset($this->resource->get->request) OR !isset($this->resource->get->request->parameters)) return true;

        $this->secondGump = new \GUMP();

        $parametersGetter = BasicParameterGetter::parameterGetterFactory( $this->resource, $this->resourceIndex );
        $validation_rules = $parametersGetter->getValidationRoules();
        $filter_rules = $parametersGetter->getFiltersRoules();

        if ( count( $validation_rules ) == 0 ) {
            return true;
        } else {
            $parms = $this->secondGump->sanitize( $_GET );
            $this->secondGump->validation_rules( $validation_rules );
            $this->secondGump->filter_rules( $filter_rules );
            $this->internalGetParameters = $this->secondGump->run( $parms );
            $this->pageStatus->setGetParameters( $this->internalGetParameters );
            $this->unvalidated_parameters = $parms;
            if ( $this->internalGetParameters === false ) {
                $this->readableErrors = $this->secondGump->get_readable_errors(true);
                $this->setError($this->readableErrors);
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
        //if ( isset($_POST['csrftoken']) AND $_POST['csrftoken'] == $_SESSION['csrftoken'] ) {
        $this->secondGump = new \GUMP;

        $parametersGetter = BasicParameterGetter::parameterGetterFactory( $this->resource, $this->resourceIndex );
        $validation_rules = $parametersGetter->getPostValidationRoules();
        $filter_rules = $parametersGetter->getPostFiltersRoules();

        if ( count( $validation_rules ) == 0 ) {
            return true;
        } else {
            $this->secondGump->validation_rules($validation_rules);
            //$gump->set_fields_error_messages($filter_rules);
            $this->secondGump->filter_rules($filter_rules);
            $this->postParameters = $this->secondGump->run(array_merge(
                is_null($_GET) ? [] : $_GET,
                is_null($_POST) ? [] : $_POST,
                is_null($_FILES) ? [] : $_FILES
            ));
            $this->pageStatus->setPostParameters( $this->postParameters );

            if ($this->secondGump->errors()) {
                $this->readableErrors = $this->secondGump->get_readable_errors(true);
                $this->setError($this->readableErrors);
                return false;
            } else {
                return true;
            }
        }

        //} else {
        //    throw new \Exception('Illegal csrftoken Exception');
        //}
    }

    /**
     * This method implements POST Request logic for all possible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function postRequest() {
        $out = '';

        $this->queryExecutor = $this->pageStatus->getQueryExecutor();

        $conn = $this->pageStatus->getDbconnection()->getDBH();

        $returnedIds = $this->pageStatus->getQueryReturnedValues();

        if (isset($this->resource->post->fileuploads)) {
            foreach ($this->resource->post->fileuploads as $metaFile) {

                if ( isset($metaFile->path) ) {
                    $folders = explode('/', $metaFile->path);
                    //echo $metaFile->path;
                    //print_r($folders);
                    $path = getcwd().'/';
                    foreach ($folders as $folder) {
                        if ($folder != ''){
                            $path .= $folder.'/';
                            //echo $path.'<br>';
                            if (!is_dir($path)) {
                                mkdir($path, 0755);
                            }
                        }
                    }

                    $file = FileUpload::uploadFile($metaFile->field, false, $metaFile->randomname,  $path);
                    // print_r($file);
                    // echo $metaFile->path.$file['filename'];
                    if (isset($file) AND array_key_exists('error', $file) AND empty($file['error'])) {
                        $returnedIds->setValue($metaFile->field, $metaFile->path.$file['filename'] );
                    }
                    //if (is_array($file['error'])) {
                    //    $message = '';
                    //    foreach ($file['error'] as $msg) {
                    //        $message .= '<p>'.$msg.'</p>';
                    //    }
                    //} else {
                    //    $message = "File uploaded successfully ".$file['filepath'].$file['filename'];
                    //}
                }

            }
        }

        // performing transactions
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

        // performing inplace edits
        if (isset($this->resource->post->inplaceeditor)) {
            foreach ($this->resource->post->inplaceeditor->updates as $inplaceTransaction) {
                if ( $inplaceTransaction->fieldcontent == $this->pageStatus->getValue($inplaceTransaction->fieldvalue) ) {
                    $this->queryExecutor->setQueryStructure( $inplaceTransaction->updatequery );
                    if ( $this->queryExecutor->getSqlStatmentType() == QueryExecuter::INSERT) {
                        if (isset($transaction->label)) {
                            $returnedIds->setValue($inplaceTransaction->label, $this->queryExecutor->executeSql());
                        } else {
                            $returnedIds->setValueNoKey($this->queryExecutor->executeSql());
                        }
                    } else {
                        $this->queryExecutor->executeSql();
                    }

                    $this->queryExecutor->setQueryStructure( $inplaceTransaction->reloadquery );
                    if ( $this->queryExecutor->getSqlStatmentType() == QueryExecuter::INSERT) {
                        if (isset($transaction->label)) {
                            $returnedIds->setValue($inplaceTransaction->label, $this->queryExecutor->executeSql());
                        } else {
                            $returnedIds->setValueNoKey($this->queryExecutor->executeSql());
                        }
                    } else {
                        $out .= $this->queryExecutor->executeSql();
                    }
                }
            }
        }

        // performing usecases
        if (isset($this->resource->post->usecases) and is_array($this->resource->post->usecases)) {
            foreach ($this->resource->post->usecases as $jsonusecase) {
                $useCase = new $this->useCasesIndex[$jsonusecase->name]( $jsonusecase, $this->pageStatus );
                $useCase->loadParameters();
                $useCase->performAction();
            }
        }

        // if resource->post->sessionupdates is set I need to update the session
        if ( isset($this->resource->post->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->post->sessionupdates );

        // redirect
        if (isset($this->resource->post->redirect)) {
            $this->jsonRedirector($this->resource->post->redirect);
        }

        echo $out;
    }

    public function showPage() {
        $time_start = microtime(true);

        if ($this->isGetRequest()) {
            SessionWrapper::createCsrfToken();
            if ( $this->check_and_load_resource() ) {
                if ( $this->check_authorization_resource_request() ) {
                    if ( $this->second_check_get_request() ) {
                        $this->getRequest();
                    } else {
                        $this->show_second_get_error_page();
                    }
                } else {
                    $this->show_get_authorization_error_page();
                }
            } else {
                $this->show_get_error_page();
            }
        } else {
            if ( $this->check_and_load_resource() ) {
                if ( $this->check_authorization_resource_request() ) {
                    if ( $this->check_post_request() ) {
                        $this->postRequest();
                    } else {
                        $this->show_post_error_page();
                    }
                } else {
                    $this->show_post_authorization_error_page();
                }
            } else {
                $this->show_post_error_page();
            }
        }

        if ($this->isGetRequest()) {
            $this->loadTemplate();
        }

        $time_end = microtime(true);
        if ( ($time_end - $time_start) > 5 ) {
            $this->logger->write('WARNING TIME :: ' . $this->resource->name . ' - TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    public function show_second_get_error_page() {
        if ( defined('APPLICATION_ENVIRONMENT') and APPLICATION_ENVIRONMENT === 'development' ) {
            print_r($this->readableErrors);
        } {
            $this->redirectToPreviousPage();
            // header('Location: ' . getenv("BASE_PATH") . getenv("PATH_TO_APP"));
        }
    }

    function loadTemplate() {
        require_once TEMPLATES_DIRECTORY . $this->templateFile . '.php';
    }

    /**
     * Method to manage a redirect defined in a json file
     *
     * @param $jsonRedirect
     */
    public function jsonRedirector( $jsonRedirect ): void {
        if ( isset( $jsonRedirect->internal ) and $jsonRedirect->internal->type === 'onepageback') {
            $this->redirectToPreviousPage();
        } elseif ( isset( $jsonRedirect->internal ) and $jsonRedirect->internal->type === 'twopagesback') {
            $this->redirectToSecondPreviousPage();
        } elseif ( isset( $jsonRedirect->action ) ) {
            $this->redirectToPage(
                UrlServices::make_resource_url( $jsonRedirect->action, $this->pageStatus )
            );
        } else {
            $this->redirectToPreviousPage();
        }
    }
}