<?php

namespace Firststep\Blocks;

use core\blocks\BaseBlock;

class BasicForm extends BaseBlock {

    private $title;
    private $subtitle;

    function __construct( $item ) {
        $this->item = $item;
    }

    function show() {
        $out = '<!-- .row -->
			<br /><br />
			      <div class="row">
			        <div class="col-sm-12">
			          <div class="white-box">
			            <h3 class="box-title m-b-0">' . $this->title . '</h3>';
        if ( $this->subtitle != '' ) {
            $out .= '<p class="text-muted m-b-30 font-13">' . $this->subtitle . '</p>';
        }
        $out .= '<form action="" method="POST" class="form-horizontal">';
        $out .= $this->main_form( $this->item );
        $out .= '</form>
			          </div>
			        </div>
			      </div>
			      <!-- /.row -->';
        return $out;
    }

    function add_text_field( $name, $label, $placeholder, $value ) {
        return '<div class="form-group">
          <label class="col-md-12" for="'.$name.'">'.$label.'</label>
          <div class="col-md-12">
            <input type="text" id="'.$name.'" name="'.$name.'" class="form-control" value="'.htmlspecialchars( $value ).'" placeholder="'.$placeholder.'">
          </div>
        </div>';
    }

    function add_textarea_field( $name, $label, $value ) {
        return '<div class="form-group">
              <label class="col-md-12" for="'.$name.'">'.$label.'</label>
              <div class="col-md-12">
                <textarea class="form-control" rows="5" id="'.$name.'" name="'.$name.'">'.htmlspecialchars( $value ).'</textarea>
              </div>
            </div>';
    }

    function add_dropdown_field( $name, $label, $options, $value ) {
        $out = '<div class="form-group">
              <label class="col-sm-12" for="'.$name.'">'.$label.'</label>
              <div class="col-sm-12">
                <select class="form-control" id="'.$name.'" name="'.$name.'">';
        foreach ($options as $key => $val) {
            $out .= '<option value="'.$key.'" '.( $key==$value ? 'selected="selected"' : '' ).'>'.htmlspecialchars( $val ).'</option>';
        }
        $out .= '</select>
              </div>
            </div>';
        return $out;
    }

    function add_fileupload_field( $name, $label ) {
        return '<div class="form-group">
              <label class="col-sm-12" for="'.$name.'">'.$label.'</label>
              <div class="col-sm-12">
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                  <div class="form-control" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                  <span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                  <input type="file" id="'.$name.'" name="'.$name.'">
                  </span> <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> </div>
              </div>
            </div>';
    }

    function add_helping_text( $title, $text ) {
        return '<div class="form-group">
                  <label class="col-sm-12">'.$title.'</label>
                  <div class="col-sm-12">
                    <span class="help-block">'.$text.'</span> 
				  </div>
                </div>';
    }

    function add_hidden_field( $name, $value ) {
        return '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars( $value ).'">';
    }

    function add_submit_button( $name='save', $value='Save' ) {
        return '<input type="submit" name="'.$name.'" value="'.htmlspecialchars( $value ).'"/>';
    }

}
