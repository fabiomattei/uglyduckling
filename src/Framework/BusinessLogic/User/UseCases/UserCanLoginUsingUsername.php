<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\User\UseCases;

class UserCanLoginUsingUsername {

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
        $this->userCanLogIn = $this->userDao->checkUserNameAndPassword(
            $this->parameters['username'],
            $this->parameters['password'] );
    }

    public function getUserCanLogIn() {
        return ( (isset($this->parameters['username']) AND isset($this->parameters['password']) ) ? $this->userCanLogIn : false );
    }

}
