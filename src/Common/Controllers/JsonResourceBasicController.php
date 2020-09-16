<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Database\QuerySet;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\ValidationBuilder;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Gump;

/**
 * User: Fabio
 * Date: 07/10/2018
 * Time: 07:53
 */
class JsonResourceBasicController extends Controller {

	public /* array */ $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public /* array */ $get_filter_rules     = array( 'res' => 'trim' );
    protected $resource;
    protected $internalGetParameters;

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
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_get_request(): bool {
        if(!isset($this->resource->allowedgroups)) return false;
        return in_array($this->pageStatus->getSessionWrapper()->getSessionGroup(), $this->resource->allowedgroups);
    }

    /**
     * Check the presence of res variable in GET or POST array
     * Filter the string
     * load the json resource in $this->resource
     */
    public function check_and_load_resource() {
        $resource_name = filter_input(INPUT_POST | INPUT_GET, 'res', FILTER_SANITIZE_STRING);
        if ( ! $resource_name ) {
            return false;
        } else {
            if ( strlen( $resource_name ) > 0 ) {
                $this->resource = $this->applicationBuilder->getJsonloader()->loadResource( $resource_name );
                return true;
            }
        }
        return false;
    }

	/**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function check_post_request() {
    	$this->secondGump = new Gump;

    	$val = new ValidationBuilder;
        $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $this->resource, $this->applicationBuilder->getJsonloader() );
    	$validation_rules = $val->postValidationRoules( $parametersGetter->getPostParameters() );
    	$filter_rules = $val->postValidationFilters( $parametersGetter->getPostParameters() );

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
			$this->unvalidated_parameters = $parms;
            if ( $this->postParameters === false ) {
				$this->readableErrors = $this->secondGump->get_readable_errors(true);
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * This method implements POST Request logic for all posible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function postRequest() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->queryExecuter->setLogger($this->applicationBuilder->getLogger());

        $this->postresource = $this->applicationBuilder->getJsonloader()->loadResource( $this->postParameters['res'] );

        $conn = $this->applicationBuilder->getDbconnection()->getDBH();

        // performing transactions
        if (isset($this->postresource->post->transactions)) {
            $returnedIds = new QueryReturnedValues;
            try {
                //$conn->beginTransaction();
                $this->queryExecuter->setDBH( $conn );
                foreach ($this->postresource->post->transactions as $transaction) {
                    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                    $this->queryExecuter->setQueryStructure( $transaction );
                    $this->queryExecuter->setPostParameters( $this->postParameters );
                    $this->queryExecuter->setLogger( $this->applicationBuilder->getLogger() );
                    $this->queryExecuter->setSessionWrapper( $this->pageStatus->getSessionWrapper() );
                    $this->queryExecuter->setQueryReturnedValues( $returnedIds );
                    if ( $this->queryExecuter->getSqlStatmentType() == QueryExecuter::INSERT) {
                        if (isset($transaction->label)) {
                            $returnedIds->setValue($transaction->label, $this->queryExecuter->executeSql());
                        } else {
                            $returnedIds->setValueNoKey($this->queryExecuter->executeSql());
                        }
                    } else {
                        $this->queryExecuter->executeSql();
                    }
                }
                //$conn->commit();
            }
            catch (\PDOException $e) {
                $conn->rollBack();
                $this->applicationBuilder->getLogger()->write($e->getMessage(), __FILE__, __LINE__);
            }
        }

        // session updates
        if (isset($this->postresource->post->sessionupdates)) {
            $querySet = new QuerySet;

            $this->queryExecuter->setDBH($conn);
            $this->queryExecuter->setQueryBuilder($this->queryBuilder);
            $this->queryExecuter->setPageStatus($this->pageStatus);

            if (isset($this->postresource->post->sessionupdates->queryset) AND is_array($this->postresource->post->sessionupdates->queryset)) {
                foreach ($this->postresource->post->sessionupdates->queryset as $query) {
                    $this->queryExecuter->setQueryStructure($query);
                    $result = $this->queryExecuter->executeSql();
                    $entity = $result->fetch();
                    if (isset($query->label)) {
                        $querySet->setResult($query->label, $entity);
                    } else {
                        $querySet->setResultNoKey($entity);
                    }
                }
            }

            if (isset($this->postresource->post->sessionupdates->sessionvars) AND is_array($this->postresource->post->sessionupdates->sessionvars)) {
                foreach ($this->postresource->post->sessionupdates->sessionvars as $sessionvar) {
                    if ( isset( $sessionvar->querylabel ) AND isset( $sessionvar->sqlfield ) ) {
                        if ( isset($querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield}) ) {
                            $this->pageStatus->getSessionWrapper()->setSessionParameter($sessionvar->name, $querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield} );
                        }
                    }

                    if ( isset( $sessionvar->constantparamenter ) OR isset( $sessionvar->getparameter ) OR isset( $sessionvar->postparameter )) {
                        $this->pageStatus->getSessionWrapper()->setSessionParameter( $sessionvar->name, $this->pageStatus->getValue($sessionvar) );
                    }
                }
            }
        }

        // redirect
        if (isset($this->postresource->post->redirect)) {
            if (isset($this->postresource->post->redirect->internal) AND $this->postresource->post->redirect->internal->type === 'onepageback') {
                $this->redirectToPreviousPage();
            } elseif (isset($this->postresource->post->redirect->internal) AND $this->postresource->post->redirect->internal->type === 'twopagesback') {
                $this->redirectToSecondPreviousPage();
            } elseif ( isset($this->postresource->post->redirect->action) AND isset($this->postresource->post->redirect->action->resource) ) {
                $this->redirectToPage(
                    $this->applicationBuilder->getRouterContainer()->makeRelativeUrl(
                        $this->applicationBuilder->getJsonloader()->getActionRelatedToResource($this->postresource->post->redirect->action->resource), 'res='.$this->postresource->post->redirect->action->resource
                    )
                );
            } else {
                $this->redirectToPreviousPage();
            }
        } else {
            $this->redirectToPreviousPage();
        }
    }

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_post_request(): bool {
        if(!isset($this->resource->allowedgroups)) return false;
        return in_array($this->pageStatus->getSessionWrapper()->getSessionGroup(), $this->resource->allowedgroups);
    }

    public function showPage() {
        $time_start = microtime(true);

        $this->applicationBuilder->getJsonloader()->loadIndex();

        if ($this->pageStatus->getServerWrapper()->isGetRequest()) {
            if ( $this->check_and_load_resource() ) {
	            if ( $this->check_authorization_get_request() ) {
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
                if ( $this->check_authorization_post_request() ) {
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
            $this->applicationBuilder->getLogger()->write('WARNING TIME :: ' . $this->request->getInfo() . ' - TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    public function show_second_get_error_page() {
        throw new \Exception('Mismatch with get parameters');
    }

}
