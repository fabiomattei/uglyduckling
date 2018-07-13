<?php

/**
 * User: fabio
 * Date: 29/05/2017
 * Time: 20:02
 */

namespace Firststep\Common\Builders;

class FormBuilder {

    private $form;
    private $entity;

    /**
     * @param mixed $form
     */
    public function setForm($form) {
        $this->form = $form;
    }

    /**
     * @param mixed $entity
	 * the $entity variable contains all values for the form
     */
    public function setEntity($entity) {
        $this->entity = $entity;
    }

    public function createBodyStructure() {
        $out = '';
        foreach ($this->form->rows as $row) {
            $out .= '<div class="row">';
            foreach ($row->fields as $field) {
				$fieldname = $field->value;
				$value = ($this->entity == null ? '' : ( isset($this->entity->$fieldname) ? htmlspecialchars($this->entity->$fieldname) : '' ) );
                $out .= '<div class="'.$field->width.'">';
                $out .= '<label class="col-md-12" for="'.$field->name.'">'.$field->label.'</label>';

                if ($field->type == 'textarea') {
                    $out .= '<textarea class="form-control" rows="5" id="'.$field->name.'" name="'.$field->name.'">'.$value.'</textarea>';
                }
                if ($field->type == 'currency') {
                    $out .= '<input type="number" name="'.$field->name.'" value="'.$value.'" min="0" step="0.01" >';
                }
                if ($field->type == 'date') {
                    $out .= '<input type="text" class="form-control datepicker" name="'.$field->name.'" value="'.date( 'd/m/Y', strtotime($value) ).'">';
                }

                $out .= '</div>';
            }
            $out .= '</div><!-- row '.$row->row.' -->';
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
            $out .= '<link rel="stylesheet" href="assets/lib/jquery-ui/jquery-ui.css">';
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
            $out .= '<script src="assets/lib/jquery-ui/jquery-ui.min.js"></script>
 		   	            <script>
  		  		            $(function() {
    				            $( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
  				            });
  			            </script>';
        }

        return $out;
    }

}
