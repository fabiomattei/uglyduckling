<?php

namespace core\businesslogic\user\usecases;

class UserCanLogIn {

	function __construct( $parameters, $userDao ) { // $formdata = $_POST
		$this->userDao    = $userDao;
		$this->parameters = $parameters;
	}
	
	function performAction() {
		$this->userCanLogIn = $this->userDao->checkEmailAndPassword(
									  $this->parameters['email'],
									  $this->parameters['password'] );
	}
	
	function getUserCanLogIn() {
		return $this->userCanLogIn;
	}
	
}
