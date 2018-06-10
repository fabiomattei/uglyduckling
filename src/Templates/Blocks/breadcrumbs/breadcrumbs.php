<?php
/**
 * Created by Fabio Mattei <burattino@gmail.com>
 * Date: 19/11/2015
 * Time: 22:21
 */

namespace templates\blocks\breadcrumbs;

use core\blocks\BaseBlock;

class BreadCrumbs extends BaseBlock {

    function __construct( $items, $laststring ) {
        $this->items      = $items;
        $this->laststring = $laststring;
    }

    function show() {
        $out = '<section id="breadcrumbs">
				<div class="container">
					<ul>';
        foreach ( $this->items as $link ) {
            $out .='<li>'.$link.'</li>';
        }
        $out .='<li><span>'.$this->laststring.'</span></li>';
        $out .='</ul>
				</div>
			</section>';
        return $out;
    }

}
