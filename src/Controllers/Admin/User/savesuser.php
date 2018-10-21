<?php

require_once 'framework/libs/gump/gump.class.php';

class AdmSavesUser {

	function __construct($formdata, $paxdao, $usertype) { // $formdata = $_POST $_SESSION['administrator']
		$this->paxdao = $paxdao;
		$this->usertype = $usertype;
		$this->dataValidated = false;
		$this->readableErrors = '';

		$this->gump = new GUMP();

		$this->formdata = $this->gump->sanitize($formdata);

		$this->gump->validation_rules(array(
		    'userid'   => 'required|integer',
			'name'	   => 'max_len,200',
			'surname'  => 'max_len,200',
			'username' => 'max_len,200',
			'password' => 'max_len,200',
			'mansion'  => 'max_len,200',
			'rights'   => 'max_len,200',
		));

		$this->gump->filter_rules(array(
		    'userid'   => 'trim',
			'name'	   => 'trim',
			'surname'  => 'trim',
			'username' => 'trim',
			'password' => 'trim',
			'mansion'  => 'trim',
			'rights'   => 'trim',
		));

		$this->validated_data = $this->gump->run($this->formdata);
	}
	
	function performAction() { 
		if($this->validated_data === false) {

			$this->dataValidated = false;
			$this->readableErrors = $this->gump->get_readable_errors(true);

		} else {
			$this->dataValidated = true;
			
			$dutymanager = ( $this->validated_data['rights'] == 'Duty Manager' ? 1 : 0 );
			$actingdutymanager = ( $this->validated_data['rights'] == 'Acting Duty Manager' ? 1 : 0 );
			$ramp = ( $this->validated_data['rights'] == 'Ramp' ? 1 : 0 );
		    
			$fields = array(
				'usr_name'              => $this->validated_data['name'],
				'usr_surname'           => $this->validated_data['surname'],
				'usr_username'          => $this->validated_data['username'],
				'usr_password'          => $this->validated_data['password'],
				'usr_mansion'           => $this->validated_data['mansion'],
				'usr_dutyManager'       => $dutymanager,
				'usr_actingDutyManager' => $actingdutymanager,
				'usr_rampAgent'         => $ramp,
				'usr_manager'           => 0,
				'usr_safety'            => 0,
				'usr_password_updated'  => date( 'Y-m-d' ),
			);
			
			if ( $this->validated_data['userid'] == 0 ) {
				// I need to create a new record in the table
				$this->entity_id = $this->paxdao->insert( $fields );
			} else {
				// I need to update the record in the table
				$this->paxdao->update( $this->validated_data['userid'] , $fields );
			}
		}
	}
	
	public function get_entity_id() {
		return ( isset($this->entity_id) ? $this->entity_id : 0 );
	}
	
	public function isDataValidated() {
		return $this->dataValidated;
	}
	
	public function getReadableErrors() {
		return $this->readableErrors;
	}
}
