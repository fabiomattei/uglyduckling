<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\ValidationBuilder;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Gump;

/**
 * User: Fabio
 * Date: 07/10/2018
 * Time: 07:53
 */
class JsonResourceBasicController extends Controller {

    protected $resource; // Json structure
    /* TODO remove following parameter */
    protected $internalGetParameters;
    protected $resourceName;
    
    /**
     * This function allows to set a resource name to load for a particular instance
     * This helps in case a resource want to be set at programming time and not 
     * at run time.
     * @param string $resourceName   the name of the json resource we want to load
     */
    public function setResourceName(string $resourceName) {
        $this->resourceName = $resourceName;
    }

    /**
     * This method has to be implemented by inherited class
     * It checks if a user belongs to a group that has access the requested resource
     */
    public function check_authorization_resource_request(): bool {
        if( !isset($this->resource->allowedgroups) ) {
            //$this->applicationBuilder->getLogger()->write('ERROR :: allowedgroups array undefined for resource ' . $this->resourceName, __FILE__, __LINE__);
            return false;
        }
        if ( in_array( $this->pageStatus->getSessionWrapper()->getSessionGroup(), $this->resource->allowedgroups ) ) {
            return true;
        } else {
            //$this->applicationBuilder->getLogger()->write('ERROR :: illegal access to resource' . $this->resourceName .' from user having group set to **' . $this->pageStatus->getSessionWrapper()->getSessionGroup() .'** ', __FILE__, __LINE__);
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
            $this->resourceName = filter_input(INPUT_POST | INPUT_GET, 'res', FILTER_SANITIZE_STRING);    
        }
        if ( ! $this->resourceName ) {
            return false;
        } else {
            if ( strlen( $this->resourceName ) > 0 ) {
                $this->resource = $this->applicationBuilder->getJsonloader()->loadResource( $this->resourceName );
                return true;
            } else {
                throw new \Exception('Resource undefined');
            }
        }
        return false;
    }

    /**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function second_check_get_request() {
        // checking if resource defines any get parameter
        if(!isset($this->resource->get->request) OR !isset($this->resource->get->request->parameters)) return true;

        $this->secondGump = new Gump;

        $val = new ValidationBuilder;
        $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $this->resource, $this->applicationBuilder->getJsonloader() );
        $validation_rules = $val->getValidationRoules( $parametersGetter->getGetParameters() );
        $filter_rules = $val->getValidationFilters( $parametersGetter->getGetParameters() );

        if ( count( $validation_rules ) == 0 ) {
            return true;
        } else {
            $parms = $this->secondGump->sanitize( $this->getParameters );
            $this->secondGump->validation_rules( $validation_rules );
            $this->secondGump->filter_rules( $filter_rules );
            $this->internalGetParameters = $this->secondGump->run( $parms );
            $this->pageStatus->setGetParameters( $this->internalGetParameters );
            $this->unvalidated_parameters = $parms;
            if ( $this->internalGetParameters === false ) {
                $this->readableErrors = $this->secondGump->get_readable_errors(true);
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
        if ( isset($this->postParameters['csrftoken']) AND $this->postParameters['csrftoken'] == $this->pageStatus->getSessionWrapper()->getCsrfToken() ) {
            $this->secondGump = new Gump;

            $val = new ValidationBuilder;
            $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $this->resource, $this->applicationBuilder->getJsonloader() );
            $validation_rules = $val->getValidationRoules( $parametersGetter->getPostParameters() );
            $filter_rules = $val->getValidationFilters( $parametersGetter->getPostParameters() );

            if ( count( $validation_rules ) == 0 ) {
                return true;
            } else {
                $parms = $this->secondGump->sanitize( array_merge(
                        is_null($this->postParameters) ? array() : $this->postParameters,
                        is_null($this->filesParameters) ? array() : $this->filesParameters
                    )
                );
                $this->secondGump->validation_rules( $validation_rules );
                $this->secondGump->filter_rules( $filter_rules );
                $this->postParameters = $this->secondGump->run( $parms );
                $this->pageStatus->setPostParameters( $this->postParameters );
                $this->unvalidated_parameters = $parms;
                if ( $this->postParameters === false ) {
                    $this->readableErrors = $this->secondGump->get_readable_errors(true);
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            throw new \Exception('Illegal csrftoken Exception');
        }
    }

    /**
     * This method implements POST Request logic for all posible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function postRequest() {
        $this->queryExecutor = $this->pageStatus->getQueryExecutor();

        $conn = $this->pageStatus->getDbconnection()->getDBH();

        // performing transactions
        if (isset($this->resource->post->transactions)) {
            $returnedIds = new QueryReturnedValues;
            try {
                //$conn->beginTransaction();
                $this->queryExecutor->setDBH( $conn );
                foreach ($this->resource->post->transactions as $transaction) {
                    $this->queryExecutor->setQueryStructure( $transaction );
                    if ( $this->queryExecutor->getSqlStatmentType() == QueryExecuter::INSERT) {
                        if (isset($transaction->label)) {
                            $returnedIds->setValue($transaction->label, $this->queryExecutor->executeSql());
                        } else {
                            $returnedIds->setValueNoKey($this->queryExecutor->executeSql());
                        }
                    } else {
                        $this->queryExecutor->executeSql();
                    }
                }
                //$conn->commit();
            }
            catch (\PDOException $e) {
                $conn->rollBack();
                $this->applicationBuilder->getLogger()->write($e->getMessage(), __FILE__, __LINE__);
            }
        }

        // performing usecases
        if (isset($this->resource->post->usecases) and is_array($this->resource->post->usecases)) {
            foreach ($this->resource->post->usecases as $jsonusecase) {
                $useCase = $this->pageStatus->getUseCasesIndex()->getUseCase($jsonusecase, $this->pageStatus);
                $useCase->performAction();
            }
        }

        // if resource->post->sessionupdates is set I need to update the session
        if ( isset($this->resource->post->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->post->sessionupdates );

        // redirect
        if (isset($this->resource->post->redirect)) {
            $this->jsonRedirector($this->resource->post->redirect);
        } else {
            $this->redirectToPreviousPage();
        }
    }

    public function showPage() {
        $time_start = microtime(true);

        $this->applicationBuilder->getJsonloader()->loadIndex();

        if ($this->pageStatus->getServerWrapper()->isGetRequest()) {
            $this->pageStatus->getSessionWrapper()->createCsrfToken();
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

        $this->loadTemplate();

        $time_end = microtime(true);
        if ( ($time_end - $time_start) > 5 ) {
            $this->applicationBuilder->getLogger()->write('WARNING TIME :: ' . $this->resource->name . ' - TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    public function show_second_get_error_page() {
        throw new \Exception('Mismatch with get parameters');
    }

    public function jsonRedirector( $jsonRedirect ): void
    {
        if ( isset( $jsonRedirect->internal ) and $jsonRedirect->internal->type === 'onepageback') {
            $this->redirectToPreviousPage();
        } elseif ( isset( $jsonRedirect->internal ) and $jsonRedirect->internal->type === 'twopagesback') {
            $this->redirectToSecondPreviousPage();
        } elseif ( isset( $jsonRedirect->action ) ) {
            $this->redirectToPage(
                $this->applicationBuilder->make_resource_url( $jsonRedirect->action, $this->applicationBuilder->getJsonloader(), $this->pageStatus )
            );
        } else {
            $this->redirectToPreviousPage();
        }
    }

}