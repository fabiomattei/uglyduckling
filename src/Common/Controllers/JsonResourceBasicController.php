<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
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

    public function loadResource() {
    	$this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );
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
    	$parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $this->resource, $this->jsonloader );
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
        return in_array($this->sessionWrapper->getSessionGroup(), $this->resource->allowedgroups);
    }

	/**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function check_post_request() {
    	$this->secondGump = new Gump;

    	$val = new ValidationBuilder;
        $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $this->resource, $this->jsonloader );
    	$validation_rules = $val->postValidationRoules( $parametersGetter->getPostParameters() );
    	$filter_rules = $val->postValidationFilters( $parametersGetter->getPostParameters() );

        if ( count( $validation_rules ) == 0 ) {
            return true;
        } else {
            $parms = $this->secondGump->sanitize( array_merge($this->postParameters, $this->filesParameters) );
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
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_post_request(): bool {
        if(!isset($this->resource->allowedgroups)) return false;
        return in_array($this->sessionWrapper->getSessionGroup(), $this->resource->allowedgroups);
    }

    public function showPage() {
        $time_start = microtime(true);

        $this->jsonloader->loadIndex();

        if ($this->serverWrapper->isGetRequest()) {			
            if ( $this->check_get_request() ) {
	            $this->loadResource();
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
            if ( $this->check_post_request() ) {
	            $this->loadResource();
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
        if (($time_end - $time_start) > 5) {
            $this->logger->write('WARNING TIME :: ' . $this->request->getServerRequestMethod() . ' ' . $this->request->getServerPhpSelf() . ' ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    public function show_second_get_error_page() {
        throw new \Exception('Mismatch with get parameters');
    }

}