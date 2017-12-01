<?php

namespace Firststep\Blocks;

use Firststep\Blocks\BaseBlock;

class BaseForm extends BaseBlock {

    private $title;
    private $subtitle;

    function __construct() {
        $this->body = '';
    }

    function show() {
        $out = '<h3>' . $this->title . '</h3>';
        if ( $this->subtitle != '' ) {
            $out .= '<p>' . $this->subtitle . '</p>';
        }
        $out .= '<form action="" method="POST" class="form-horizontal">';
        $out .= $this->body;
        $out .= '</form>';
        return $out;
    }

    function addTextField( $name, $label, $placeholder, $value ) {
        $this->body .= '<label for="'.$name.'">'.$label.'</label><input type="text" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars( $value ).'" placeholder="'.$placeholder.'">';
    }

    function addTextAreaField( $name, $label, $value ) {
        $this->body .= '<label for="'.$name.'">'.$label.'</label><textarea id="'.$name.'" name="'.$name.'">'.htmlspecialchars( $value ).'</textarea>';
    }

    function addDropdownField( $name, $label, $options, $value ) {
        $this->body .= '<label for="'.$name.'">'.$label.'</label><select id="'.$name.'" name="'.$name.'">';
        foreach ($options as $key => $val) {
            $this->body .= '<option value="'.$key.'" '.( $key==$value ? 'selected="selected"' : '' ).'>'.htmlspecialchars( $val ).'</option>';
        }
        $this->body .= '</select>';
    }

    function addFileUploadField( $name, $label ) {
        $this->body .= '<label for="'.$name.'">'.$label.'</label><input type="file" id="'.$name.'" name="'.$name.'">';
    }

    function addHelpingText( $title, $text ) {
        $this->body .= '<h5>'.$title.'</h5><p>'.$text.'</p>';
    }

    function addHiddenField( $name, $value ) {
        $this->body .= '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars( $value ).'">';
    }

    function addSubmitButton( $name='save', $value='Save' ) {
        $this->body .= '<input type="submit" name="'.$name.'" value="'.htmlspecialchars( $value ).'"/>';
    }

}
