<?php 

namespace Firststep\Common\Json\Checkers\Form;

use Firststep\Common\Json\Checkers\BasicJsonChecker;
use Firststep\Common\Utils\StringUtils;

/**
 * Make all checks for form entity version 1
 */
class FormV1JsonChecker extends BasicJsonChecker {

	function isResourceBlockWellStructured() : bool {
        $out = true;
        $getParameters = $this->resource->get->request->parameters ?? array();
        $postParameters = $this->resource->post->request->postparameters ?? array();
        $querysql = $this->resource->get->query->sql ?? '';
        $querySqlParameters = $this->resource->get->query->parameters ?? array();
        $formfields = $this->resource->get->form->fields ?? array();
        $form_transactions = $this->resource->post->transactions ?? array();

        // check if all form fields are cotained in the query selected fields
        foreach ( $formfields as $field ) {
            if ( isset( $field->sqlfield ) AND isset( $querysql ) ) {
                if ( !$this->isFieldInQuery( $field->sqlfield, $querysql ) ) {
                    $this->errors[] = 'Error for form field ' . $field->name . ' its sqlfield ' . $field->sqlfield . ' is not in query ' . $querysql;
                    $out = false;
                }
            }
        }

        // check if all query sql parameters are passed in the get parameters array
        foreach ($querySqlParameters as $sqlRequiredPar) {
            echo $sqlRequiredPar->getparameter;
            if (!array_filter($getParameters, function ($parToCheck) use ($sqlRequiredPar) {
                echo "$parToCheck->name === $sqlRequiredPar->getparameter<br>";
                return $parToCheck->name === $sqlRequiredPar->getparameter;
            })) {
                $this->errors[] = 'Error for form, SQL parameter ' . $sqlRequiredPar->getparameter . ' it is not part of the get parameters array';
                $out = false;
            }
        }

        // for POST request check if all post parameters are contained in the HTML form
        foreach ($postParameters as $post_required_par) {
            if (!array_filter($formfields, function ($parToCheck) use ($post_required_par) {
                return $parToCheck->name === $post_required_par->name;
            })) {
                $this->errors[] = 'Error for form field POST parameter ' . $post_required_par->name . ' it is not part of form fields array';
                $out = false;
            }
        }

        // for POST request it checks if all query sql parameters are passed between post parameters
        foreach ( $form_transactions as $transaction ) {
            foreach ($transaction->parameters as $sqlRequiredPar) {
                if (!array_filter($postParameters, function ($parToCheck) use ($sqlRequiredPar) {
                    return $parToCheck->name === $sqlRequiredPar->postparameter;
                })) {
                    $this->errors[] = 'Error for form, SQL Transaction parameter ' . $sqlRequiredPar->postparameter . ' it is not part of the get parameters array';
                    $out = false;
                }
            }
        }
        
	    return $out;
    }

    /**
     * Return an array containing all actions defined in this resource
     *
     * @return array
     */
    public function getActionsDefinedInResource(): array {
	    $actions = $this->resource->get->form->topactions ?? array();
		if ( isset( $this->resource->post->redirect ) ) $actions[] = $this->resource->post->redirect;
	    // $actions = array_merge( $actions, $this->resource->post->redirect ?? array() );
        return $actions;
    }

    public function isFieldInQuery( string $field = '', string $query = '' ): bool {
        return StringUtils::isStringBetweenCaseUnsensitive( $field, $query, 'SELECT', 'FROM' );
    }

}
