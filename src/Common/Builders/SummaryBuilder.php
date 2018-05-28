<?php

/**
 * User: fabio
 * Date: 30/05/17
 * Time: 18.41
 */

namespace core\html;

class SummaryBuilder {

    private $fields;
    private $summary;
    private $xmlstring;

    /**
     * @param mixed $fields
     */
    public function setFields($fields) {
        $this->fields = $fields;
    }

    /**
     * @param mixed $form
     */
    public function setSummary($summary) {
        $this->summary = $summary;
    }

    /**
     * @param mixed $xmlstring
     */
    public function setXmlstring($xmlstring) {
        $this->xmlstring = $xmlstring;
    }

    public function createBodyStructure() {
        $entity = ( $this->xmlstring == null ? null : simplexml_load_string( $this->xmlstring ) );
        $out = '';
        foreach ($this->summary as $row => $fields) {
            $out .= '<div class="row">';
            foreach ($fields as $fieldname => $properties) {
                $out .= '<label class="col-md-12" for="'.$fieldname.'">'.$properties['label'].'</label>';

                if ($properties['type'] == 'textarea') {
                    $out .= ( $entity == null ? '' : ( isset($entity->$fieldname) ? htmlspecialchars($entity->$fieldname) : '' ) );
                }
                if ($properties['type'] == 'currency') {
                    $out .= ( $entity == null ? '' : ( isset($entity->$fieldname) ? htmlspecialchars($entity->$fieldname) : '' ) );
                }
                if ($properties['type'] == 'date') {
                    $out .= ( $entity == null ? '' : ( isset($entity->$fieldname) ? htmlspecialchars( date( 'd/m/Y', strtotime($entity->$fieldname) ) ) : '' ) );
                }
            }
            $out .= '</div><!-- row '.$row.' -->';
        }
        return $out;
    }

}
