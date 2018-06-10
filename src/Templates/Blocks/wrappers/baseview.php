<?php

namespace templates\blocks\wrappers;

use core\blocks\BaseBlock;

class BaseView extends BaseBlock {

	function __construct( $item, $title ) {
		$this->item  = $item;
		$this->title = $title;
	}

    function show() {
		$out = '<!--[if !IE]>start section<![endif]-->	
				<div class="section table_section">
					<!--[if !IE]>start title wrapper<![endif]-->
					<div class="title_wrapper">
						<h2>' . $this->title . '</h2>
						<span class="title_wrapper_left"></span>
						<span class="title_wrapper_right"></span>
					</div>
					<!--[if !IE]>end title wrapper<![endif]-->
					<!--[if !IE]>start section content<![endif]-->
					<div class="section_content">
					
						<!--[if !IE]>start section content top<![endif]-->
						<div class="sct">
							<div class="sct_left">
								<div class="sct_right">
									<div class="sct_left">
										<div class="sct_right">
                                            <form action="" method="POST">
                                                ' . $this->main_form( $this->item ) . '
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--[if !IE]>end section content top<![endif]-->
						<!--[if !IE]>start section content bottom<![endif]-->
						<span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
						<!--[if !IE]>end section content bottom<![endif]-->
						
					</div>
					<!--[if !IE]>end section content<![endif]-->
				</div>
				<!--[if !IE]>end section<![endif]-->';
		return $out;
    }
	
}
