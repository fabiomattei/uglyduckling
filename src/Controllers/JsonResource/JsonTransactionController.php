<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;
use Fabiom\UglyDuckling\Common\Status\Logics;

/**
 * User: Fabio
 * Date: 29/09/2018
 * Time: 05:57
 */
class JsonTransactionController extends JsonResourceBasicController {
	
	public function getRequest() {

        Logics::performTransactions( $this->pageStatus, $this->applicationBuilder, $this->resource );

        // performing usecases
        if (isset($this->resource->get->usecases) and is_array($this->resource->get->usecases)) {
            foreach ($this->resource->get->usecases as $jsonusecase) {
                $useCase = $this->pageStatus->getUseCasesIndex()->getUseCase($jsonusecase, $this->pageStatus);
                $useCase->performAction();
            }
        }

        // if resource->get->sessionupdates is set I need to update the session
        if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

        // redirect
        if (isset($this->resource->get->redirect)) {
            $this->jsonRedirector($this->resource->get->redirect);
        } else {
            $this->redirectToPreviousPage();
        }
    }

}
