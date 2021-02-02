<?php

namespace Fabiom\UglyDuckling\Common\UseCases;

use Fabiom\UglyDuckling\Common\Status\PageStatus;

class BaseUseCase {

    public /* PageStatus */ $pageStatus;
    public $useCaseJsonStructure;

    /**
     * BaseUseCase constructor.
     * @param $pageStatus
     */
    function __construct( $useCaseJsonStructure, PageStatus $pageStatus ) {
        $this->useCaseJsonStructure = $useCaseJsonStructure;
        $this->pageStatus = $pageStatus;
    }

    /**
     * Return true if data given as input were validated
     *
     * @return bool
     */
    function dataValidated() {
        return true;
    }

    /**
     * Return true in case the UseCase was successfull
     *
     * @return bool
     */
    function endedSuccessfully() {
        return true;
    }

    /**
     * This method contains the usecase business logic
     * Implement this method in order to implement this use case
     */
    function performAction() {
    }

    /**
     * @return array of strings containing all error messages
     */
    function getErrors() {
        return array();
    }

}
