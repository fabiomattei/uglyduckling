<?php

/**
 * User: Fabio Mattei
 * Date: 30/05/17
 * Time: 18.19
 */

namespace core\html;

class InfoBuilder {

    private $fields;
    private $info;
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
    public function setInfo($info) {
        $this->info = $info;
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
        foreach ($this->info as $row => $fields) {
            $out .= '<div class="row">';
            foreach ($fields as $fieldname => $properties) {
                $out .= '<div class="'.$properties['width'].'">';
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

                $out .= '</div>';
            }
            $out .= '</div><!-- row '.$row.' -->';
        }
        return $out;
    }

}
