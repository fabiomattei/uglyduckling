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

        Logics::performUseCases( $this->pageStatus, $this->applicationBuilder, $this->resource );

        // if resource->get->sessionupdates is set I need to update the session
        if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

        if ( isset($jsonResource->get->ajax) ) {
            echo Logics::performAjaxCallGet( $this->pageStatus, $this->applicationBuilder, $this->resource );
        }

        // redirect
        if (isset($this->resource->get->redirect)) {
            $this->jsonRedirector($this->resource->get->redirect);
        } else {
            $this->redirectToPreviousPage();
        }
    }

}
