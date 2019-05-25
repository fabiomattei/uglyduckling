<?php 

namespace Firststep\Common\Json\Checkers\Transaction;

use Firststep\Common\Json\Checkers\BasicJsonChecker;
use Firststep\Common\Utils\StringUtils;

/**
 * Make all checks for form entity version 1
 */
class TransactionV1JsonChecker extends BasicJsonChecker {

	function isResourceBlockWellStructured() : bool {
        $out = true;
        $getParameters = $this->resource->get->request->parameters ?? array();
        $postParameters = $this->resource->post->request->postparameters ?? array();
        $getTransactions = $this->resource->get->transactions ?? array();
        $postTransactions = $this->resource->post->transactions ?? array();

        // for GET request it checks if all query sql parameters are passed between post parameters
        foreach ( $getTransactions as $transaction ) {
            foreach ($transaction->parameters as $sqlRequiredPar) {
                if (!array_filter($getParameters, function ($parToCheck) use ($sqlRequiredPar) {
                    return $parToCheck->name === $sqlRequiredPar->parameter;
                })) {
                    $this->errors[] = 'Error for form, SQL Transaction parameter ' . $sqlRequiredPar->parameter . ' it is not part of the get parameters array';
                    $out = false;
                }
            }
        }

        // for POST request it checks if all query sql parameters are passed between post parameters
        foreach ( $postTransactions as $transaction ) {
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
        return array();
    }

    public function isFieldInQuery( string $field = '', string $query = '' ): bool {
        return StringUtils::isStringBetweenCaseUnsensitive( $field, $query, 'SELECT', 'FROM' );
    }

}
