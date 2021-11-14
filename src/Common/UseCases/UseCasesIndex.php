<?php

namespace Fabiom\UglyDuckling\Common\UseCases;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;

class UseCasesIndex {

    /**
     * Overwrite this method in order to extend the usecase index for each application
     *
     * @param string $action
     */
    function isUseCaseSupported( string $useCaseName ) {
        return in_array( $useCaseName, array( 'baseusecase' ) );
    }

    /**
     * Overwrite this method in order to extend the usecase index for each application
     *
     * return a class extending BaseUseCase
     */
    function getUseCase( $useCaseJsonStructure, PageStatus $pageStatus, ApplicationBuilder $applicationBuilder ) {
        return new BaseUseCase( $useCaseJsonStructure, $pageStatus, $applicationBuilder );
    }

}
