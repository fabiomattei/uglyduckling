<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Common\Status\Logics;

class JsonResourcePartialBasicController extends ControllerNoCSRFTokenRenew {

    const CONTROLLER_NAME = 'partial';

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->applicationBuilder->getJsonloader()->loadIndex();

        // GETTING json resource name from parameter
        $jsonResourceName = filter_input(INPUT_POST | INPUT_GET, 'res', FILTER_SANITIZE_STRING);

        // loading json resource
        if ( ! $jsonResourceName ) {
            echo 'missing resource name';
            $jsonResource = new \stdClass;
        } else {
            if ( strlen( $jsonResourceName ) > 0 ) {
                $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource( $jsonResourceName );
            } else {
                $jsonResource = new \stdClass;
            }
        }

        // if json resource was correctly loaded
        if ( is_object( $jsonResource ) ) {
            $this->templateFile = 'empty';

            // if json resource has parameters
            if(!isset($jsonResource->get->request) OR !isset($jsonResource->get->request->parameters)) {
                if ( isset($jsonResource->get->sessionupdates) ) $this->pageStatus->updateSession( $jsonResource->get->sessionupdates );

                $myBlocks = $this->applicationBuilder->getHTMLBlock( $jsonResource );
                echo $myBlocks->show();
            } else {
                $secondGump = new \Gump;

                $parametersGetter = BasicParameterGetter::parameterGetterFactory( $jsonResource, $this->applicationBuilder );
                $validation_rules = $parametersGetter->getValidationRoules();
                $filter_rules = $parametersGetter->getFiltersRoules();

                if ( count( $validation_rules ) == 0 ) {
                    // nothing to do
                } else {
                    $parms = $secondGump->sanitize( $_GET );
                    $secondGump->validation_rules( $validation_rules );
                    $secondGump->filter_rules( $filter_rules );
                    $cleanGETParameters = $secondGump->run( $parms );
                    $this->pageStatus->setGetParameters( $cleanGETParameters );
                    $this->unvalidated_parameters = $parms;
                    if ( $this->internalGetParameters === false ) {
                        $this->readableErrors = $this->secondGump->get_readable_errors(true);
                    } else {
                        if ( isset($jsonResource->get->sessionupdates) ) $this->pageStatus->updateSession( $jsonResource->get->sessionupdates );

                        $myBlocks = $this->applicationBuilder->getHTMLBlock( $jsonResource );
                        echo $myBlocks->show();
                    }
                }
            }
        } else {
            echo 'resource '.$jsonResourceName.' undefined';
        }
    }

    /**
     * This method implements POST Request logic for all possible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function postRequest() {
        $this->templateFile = "empty";

        $this->applicationBuilder->getJsonloader()->loadIndex();

        // GETTING json resource name from parameter
        $jsonResourceName = filter_input(INPUT_POST | INPUT_GET, 'res', FILTER_SANITIZE_STRING);
        if ( ! $jsonResourceName ) {
            if ( isset( $_POST['res'] ) ) {
                $jsonResourceName = filter_var($_POST['res'], FILTER_SANITIZE_STRING);
            }
        }

        // loading json resource
        if ( ! $jsonResourceName ) {
            echo 'missing resource name';
            $jsonResource = new \stdClass;
        } else {
            if ( strlen( $jsonResourceName ) > 0 ) {
                $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource( $jsonResourceName );
            } else {
                $jsonResource = new \stdClass;
            }
        }

        // checking parameters
        $secondGump = new \Gump;
        if( isset($jsonResource->post->request) AND isset($jsonResource->post->request->postparameters)) {
            $parametersGetter = BasicParameterGetter::parameterGetterFactory( $jsonResource, $this->applicationBuilder );
            $validation_rules = $parametersGetter->getPostValidationRoules();
            $filter_rules = $parametersGetter->getPostFiltersRoules();

            $parms = $secondGump->sanitize( array_merge($_GET, $_POST) );
            $secondGump->validation_rules( $validation_rules );
            $secondGump->filter_rules( $filter_rules );
            $cleanPostParameters = $secondGump->run( $parms );
            $this->pageStatus->setPostParameters( $cleanPostParameters );
            $this->unvalidated_parameters = $parms;
        }
        if ($secondGump->errors()) {
            $this->pageStatus->addErrors( $secondGump->get_readable_errors() );
        } else {
            Logics::performTransactions( $this->pageStatus, $this->applicationBuilder, $jsonResource );

            Logics::performUseCases( $this->pageStatus, $this->applicationBuilder, $jsonResource );

            // if resource->get->sessionupdates is set I need to update the session
            if ( isset($this->resource->post->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->post->sessionupdates );


        }
        echo Logics::performAjaxCallPost( $this->pageStatus, $this->applicationBuilder, $jsonResource );
    }
}
