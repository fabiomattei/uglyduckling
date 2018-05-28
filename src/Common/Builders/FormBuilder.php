<?php

/**
 * User: fabio
 * Date: 29/05/2017
 * Time: 20:02
 */

namespace Firststep\Common\Builders;

class FormBuilder {

    private $fields;
    private $form;
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
    public function setForm($form) {
        $this->form = $form;
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
        foreach ($this->form as $row => $fields) {
            $out .= '<div class="row">';
            foreach ($fields as $fieldname => $properties) {
                $out .= '<div class="'.$properties['width'].'">';
                $out .= '<label class="col-md-12" for="'.$fieldname.'">'.$properties['label'].'</label>';

                if ($properties['type'] == 'textarea') {
                    $out .= '<textarea class="form-control" rows="5" id="'.$fieldname.'" name="'.$fieldname.'">'.( $entity == null ? '' : ( isset($entity->$fieldname) ? htmlspecialchars($entity->$fieldname) : '' ) ).'</textarea>';
                }
                if ($properties['type'] == 'currency') {
                    $out .= '<input type="number" name="'.$fieldname.'" value="'.( $entity == null ? '' : ( isset($entity->$fieldname) ? htmlspecialchars($entity->$fieldname) : '' ) ).'" min="0" step="0.01" >';
                }
                if ($properties['type'] == 'date') {
                    $out .= '<input type="text" class="form-control datepicker" name="'.$fieldname.'" value="'.( $entity == null ? '' : ( isset($entity->$fieldname) ? htmlspecialchars( date( 'd/m/Y', strtotime($entity->$fieldname) ) ) : '' ) ).'">';
                }

                $out .= '</div>';
            }
            $out .= '</div><!-- row '.$row.' -->';
        }
        return $out;
    }

    public function create_addToHead() {
        $adddate = false;

        foreach ($this->form as $row => $fields) {
            foreach ($fields as $fieldname => $properties) {

                if ($properties['type'] == 'date') {
                    $adddate = true;
                }

            }
        }

        $out = '';
        if ($adddate) {
            $out .= '<link rel="stylesheet" href="'.BASEPATH.'assets/lib/jquery-ui/jquery-ui.css">';
        }

        return $out;
    }

    public function create_addToFoot() {
        $adddate = false;

        foreach ($this->form as $row => $fields) {
            foreach ($fields as $fieldname => $properties) {

                if ($properties['type'] == 'date') {
                    $adddate = true;
                }

            }
        }

        $out = '';
        if ($adddate) {
            $out .= '<script src="'.BASEPATH.'assets/lib/jquery-ui/jquery-ui.min.js"></script>
 		   	            <script>
  		  		            $(function() {
    				            $( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
  				            });
  			            </script>';
        }

        return $out;
    }

}
