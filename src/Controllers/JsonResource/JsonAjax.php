<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;

/**
 * User: Fabio Mattei
 * Date: 24/01/2020
 * Time: 07:48
 *
 * Controller created in order to handle jinplace requests for ajax features in the application
 */
class JsonAjax extends JsonResourceBasicController {

    public $fields;
    public $join;

    const CONTROLLER_NAME = 'jsonajax';

    public /* array */ $post_validation_rules = array(
        'riskcenterid' => 'numeric',
        'unitid'       => 'numeric',
        'parentid'     => 'numeric'
    );
    public /* array */ $post_filter_rules = array(
        'riskcenterid' => 'trim',
        'unitid'       => 'trim',
        'parentid'     => 'trim'
    );

    public function postRequest() {
        $this->templateFile = 'empty';

        // checking post parameters in post request
        $parms = $this->gump->sanitize( array_merge($this->getParameters, $this->postParameters) );
        $this->gump->validation_rules( $this->post_validation_rules );
        $this->gump->filter_rules( $this->post_filter_rules );
        $this->filteredParameters = $this->gump->run( $parms );
        $this->unvalidated_parameters = $parms;
        if ( $this->postParameters === false ) {
            $this->readableErrors = $this->gump->get_readable_errors(true);
            $out = false;
        } else {

            $dbh = $this->pageStatus->getDbconnection()->getDBH();
            if ( $this->filteredParameters['parentid'] == 0 ) {
                $statement = $dbh->prepare("SELECT " . $fields .  " FROM asset A ".$join." WHERE A.ta_parentid IS NULL AND A.ta_riskcenterid = :riskcenterid AND A.ta_unitid = :unitid;");
                $statement->execute(
                    [ ':riskcenterid' => $this->filteredParameters['riskcenterid'],
                        ':unitid'=> $this->filteredParameters['unitid'] ]
                );
            } else {
                $statement = $dbh->prepare("SELECT " . $fields . " FROM asset A ".$join." WHERE A.ta_parentid = :parentid AND A.ta_riskcenterid = :riskcenterid AND A.ta_unitid = :unitid;");
                $statement->execute(
                    [ ':riskcenterid' => $this->filteredParameters['riskcenterid'],
                        ':parentid'=> $this->filteredParameters['parentid'],
                        ':unitid'=> $this->filteredParameters['unitid'] ]
                );
            }
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

            // $statement->debugDumpParams();

            echo json_encode($results);
        }
    }
}
