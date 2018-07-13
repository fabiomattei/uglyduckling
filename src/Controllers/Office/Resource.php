<?php

namespace Firststep\Controllers\Office;

use Firststep\Common\Controllers\Controller;
use Firststep\Common\Json\JsonBlockParser;

/**
 * 
 */
class Resource extends Controller {
	
    public $get_validation_rules = array( '0' => 'required|max_len,2500' );
    public $get_filter_rules     = array( '0' => 'trim' );
	
    public function check_authorization_get_request() {
        return true;
    }
	
    /**
     * Overwrite parent showPage method in order to add the functionality of loading a 
	 * resource.
     */
    public function showPage() {
		$this->jsonloader->loadIndex();
		parent::showPage(); 
    }
	
    /**
     * @throws GeneralException
     *
     * $this->parameters[0] resource key
     */
	public function getRequest() {
		$this->resource = $this->jsonloader->loadResource( $this->parameters[0] );
		
		$parameters = JsonParametersParser::parseResourceForParametersValidationRoules( $resource );
		$get_validation_rules = $get_validation_rules + $parameters['rules']; // array concatenation
		$get_filter_rules = $get_filter_rules + $parameters['filters']; // array concatenation
		
        if ( $this->check_get_request() ) {
            $this->internalGetRequest();
        } else {
            $this->internalShowGetErrorPage();
        }

		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Admin dashboard';
		$entity = new stdClass; // prepare query
		$this->centralcontainer = JsonBlockParser::parseResourceForBlock( $this->resource, $entity );
	}
	
	public function internalGetRequest() {
		# code...
	}
	
	public function internalShowGetErrorPage() {
		# code...
	}
	
	
    public function check_authorization_post_request() {
        return true;
    }
	
    public $post_validation_rules = array(
	    'id'            => 'required|integer',
		'idnumber'      => 'integer',
    );
    public $post_filter_rules = array(
		'id'            => 'trim',
		'idnumber'      => 'trim',
    );

    /**
     * it saves the data coming from the form in the getRequet
     */
    public function postRequest() {
        usecase( 'maintenance', 'saveasset' );
		$vc_dao  = dao_exp( 'maintenance', 'AssetDao' );

        $usecase = new SaveAsset( $this->parameters, $vc_dao, $_SESSION['user_id'] );
        $usecase->performAction();

        // redirecting to assets list
		$this->redirectToPage( 'maintenance', 'assets' );
    }
	
	/**
	 * this function is called in case the post call do not pass the validation rules contained in array
	 * $post_validation_rules
	 */
    public function show_post_error_page() {
		if ( is_numeric( $this->unvalidated_parameters['id'] ) ) {
			$this->setError( $this->readableErrors );
			
			block( 'template', 'wrappers/mainmenu' );
			block( 'maintenance', 'maintenacesubmenu' );
			block( 'maintenance', 'assetform' );

			$vc_dao  = dao_exp( 'maintenance', 'AssetDao' );
			$asset = $vc_dao->getEmpty();
		    $asset->mntas_id            = $this->unvalidated_parameters['id'];
			$asset->mntas_idnumber      = $this->unvalidated_parameters['idnumber'];
			$asset->mntas_registration  = $this->unvalidated_parameters['registration']; 
			$asset->mntas_serialnumber  = $this->unvalidated_parameters['serialnumber']; 
		    $asset->mntas_name          = $this->unvalidated_parameters['name'];
			$asset->mntas_description   = $this->unvalidated_parameters['description'];
			$asset->mntas_startinghours = $this->unvalidated_parameters['startinghours']; 
			$asset->mntas_workedhours   = $this->unvalidated_parameters['workedhours']; 
			$asset->mntas_status        = $this->unvalidated_parameters['status'];
			
			$this->menucontainer    = array( new MainMenu( $_SESSION['office_id'], 'maintenance' ),
			 							     new MaintenanceSubMenu( $_SESSION['office_id'] ) );
	        $this->centralcontainer = array( new AssetForm( $asset ) );
		} else {
			throw new GeneralException('General malfuction in post Ramp_Maintenance_Editasset!!!');
		}
    }

}
