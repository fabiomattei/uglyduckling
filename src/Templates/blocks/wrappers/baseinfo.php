<?php

namespace templates\blocks\wrappers;

use core\blocks\BaseBlock;

class BaseInfo extends BaseBlock {

	function __construct( $item, $title, $subtitle = '' ) {
		$this->item     = $item;
		$this->title    = $title;
		$this->subtitle = $subtitle;
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
		$out .= '<div class="form-horizontal">';
		$out .= $this->main_info( $this->item );
		$out .= '</div>';
		$out .= '	  </div>
			        </div>
			      </div>
			      <!-- /.row -->';
		return $out;
    }
	
	function add_text_info( $label, $value ) {
        return '<div class="form-group">
                  <label class="col-sm-12">'.$label.'</label>
                  <div class="col-sm-12">
                    <span class="help-block">'.htmlspecialchars( $value ).'</span> 
				  </div>
                </div>';
	}
	
	function add_textarea_info( $label, $value ) {
        return '<div class="form-group">
                  <label class="col-sm-12">'.$label.'</label>
                  <div class="col-sm-12">
                    <span class="help-block">'.htmlspecialchars( $value ).'</span> 
				  </div>
                </div>';
	}
	
	function add_dropdown_info( $label, $options, $value ) {
        $out = '<div class="form-group">
              <label class="col-sm-12">'.$label.'</label>
              <div class="col-sm-12">';
		foreach ($options as $key => $val) {
			$out .= ( $key==$value ? htmlspecialchars( $val ) : '' );
		}
        $out .= '</div>
            </div>';
		return $out;
	}
	
	function add_helping_text( $title, $text ) {
		return '<div class="form-group">
                  <label class="col-sm-12">'.$title.'</label>
                  <div class="col-sm-12">
                    <span class="help-block">'.$text.'</span> 
				  </div>
                </div>';
	}
	
}
