<?php

namespace Fabiom\UglyDuckling\BusinessLogic\User\UseCases;

class UserCanLogIn {

    public $parameters;
    public $userDao;
    public $userCanLogIn;
	
	public function setParameters( $parameters ) {
		$this->parameters = $parameters;
	}
	
	public function setUserDao( $userDao ) {
		$this->userDao = $userDao;
	}
	
	public function performAction() {
		$this->userCanLogIn = $this->userDao->checkEmailAndPassword(
									  $this->parameters['email'],
									  $this->parameters['password'] );
	}
	
	public function getUserCanLogIn() {
		return ( (isset($this->parameters['email']) AND isset($this->parameters['password']) ) ? $this->userCanLogIn : false );
	}
	
}
