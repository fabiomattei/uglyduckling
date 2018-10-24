<?php

require_once 'framework/libs/gump/gump.class.php';

class AdmDeleteUser {

	function __construct($formdata, $user_dao, $usertype) { // $formdata = $_POST
		$this->user_dao = $user_dao;
		$this->usertype  = $usertype;
		$this->dataValidated     = false;
		$this->readableErrors    = '';

		$this->gump = new GUMP();

		$this->formdata = $this->gump->sanitize($formdata);

		$this->gump->validation_rules(array(
			'id'   => 'numeric'
		));

		$this->gump->filter_rules(array(
			'id'   => 'trim',
		));

		$this->validated_data = $this->gump->run($this->formdata);
	}
	
	function performAction() { 
		if($this->validated_data === false) {

			$this->dataValidated = false;
			$this->readableErrors = $this->gump->get_readable_errors(true);

		} else {	
			$this->dataValidated = true;
			
			// check if status updates belongs to user's activity
			$user = $this->user_dao->getById( $this->validated_data['id'] );
			if( isset( $user->usr_id ) AND $user->usr_id != 0 ) {
			
				// deleting flight
				$this->user_dao->delete( $this->validated_data['id'] );
			
			} else {
				// not giving feedback
				throw new GeneralException( 'General malfuction!!!' );
			}
			
		}
	}
	
	public function isDataValidated() {
		return $this->dataValidated;
	}
	
	public function getReadableErrors() {
		return $this->readableErrors;
	}
}
