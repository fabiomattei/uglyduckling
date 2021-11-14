<?php

namespace Fabiom\UglyDuckling\Common\UseCases;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;

class BaseUseCase {

    public PageStatus $pageStatus;
    public ApplicationBuilder $applicationBuilder;
    public $useCaseJsonStructure;
    public $errors = array();
    public $useCaseParameters = array();

    /**
     * BaseUseCase constructor.
     * @param $pageStatus
     */
    function __construct( $useCaseJsonStructure, PageStatus $pageStatus, ApplicationBuilder $applicationBuilder ) {
        $this->useCaseJsonStructure = $useCaseJsonStructure;
        $this->pageStatus = $pageStatus;
        $this->applicationBuilder = $applicationBuilder;
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
     * Load all parameters sent with the json structure
     */
    function loadParameters() {
        $this->useCaseParameters = array();
        if (isset($this->useCaseJsonStructure->parameters) AND is_array( $this->useCaseJsonStructure->parameters)) {
            foreach ($this->useCaseJsonStructure->parameters as $parameter) {
                $this->useCaseParameters[$parameter->name] = $this->pageStatus->getValue( $parameter );
            }
        }
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
    function getErrors(): array {
        return $this->errors;
    }

}
