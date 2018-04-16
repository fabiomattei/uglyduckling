<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class BaseForm extends BaseBlock {

    private $title;
    private $subtitle;

    function __construct() {
        $this->body = '';
    }

    function show(): string {
        $out = '<h3>' . $this->title . '</h3>';
        if ( $this->subtitle != '' ) {
            $out .= '<p>' . $this->subtitle . '</p>';
        }
        $out .= '<form action="" method="POST" class="form-horizontal">';
        $out .= $this->body;
        $out .= '</form>';
        return $out;
    }

    function addTextField( string $name, string $label, string $placeholder, string $value ) {
        $this->body .= '<label for="'.$name.'">'.$label.'</label><input type="text" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars( $value ).'" placeholder="'.$placeholder.'">';
    }

    function addTextAreaField( string $name, string $label, string $value ) {
        $this->body .= '<label for="'.$name.'">'.$label.'</label><textarea id="'.$name.'" name="'.$name.'">'.htmlspecialchars( $value ).'</textarea>';
    }

    function addDropdownField( string $name, string $label, array $options, string $value ) {
        $this->body .= '<label for="'.$name.'">'.$label.'</label><select id="'.$name.'" name="'.$name.'">';
        foreach ($options as $key => $val) {
            $this->body .= '<option value="'.$key.'" '.( $key==$value ? 'selected="selected"' : '' ).'>'.htmlspecialchars( $val ).'</option>';
        }
        $this->body .= '</select>';
    }

    function addFileUploadField( string $name, string $label ) {
        $this->body .= '<label for="'.$name.'">'.$label.'</label><input type="file" id="'.$name.'" name="'.$name.'">';
    }

    function addHelpingText( string $title, string $text ) {
        $this->body .= '<h5>'.$title.'</h5><p>'.$text.'</p>';
    }

    function addHiddenField( string $name, string $value ) {
        $this->body .= '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars( $value ).'">';
    }

    function addSubmitButton( string $name = 'save', string $value = 'Save' ) {
        $this->body .= '<input type="submit" name="'.$name.'" value="'.htmlspecialchars( $value ).'"/>';
    }

}
