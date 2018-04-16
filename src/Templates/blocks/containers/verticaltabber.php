<?php

/**
 * Created by Fabio Mattei <burattino@gmail.com>
 * Date: 05/02/2016
 * Time: 10:41
 */

namespace templates\blocks\containers;

use core\blocks\BaseBlock;

class VerticalTabber extends BaseBlock {

    function __construct($titles, $blocks, $tabid = 'tb1' ) {
        $this->titles = $titles;
        $this->blocks = $blocks;

        $this->tab_ids = array( $tabid.'_a', $tabid.'_b', $tabid.'_c', $tabid.'_d', $tabid.'_e' );
    }

    function show() {
        $out = '<div class="tabbable tabs-left tabbable-bordered">
	                <ul class="nav nav-tabs">';

        $counter = 0;
        foreach ($this->titles as $tit) {
            $active = '';
            if ( $counter == 0 ) {
                $active = 'class="active"';
            }
            $out .= '<li '.$active.'><a data-toggle="tab" href="#'.$this->tab_ids[$counter].'">'.$tit.'</a></li>';
            $counter++;
        }

        $out .= '    </ul>
	             <div class="tab-content">';

        $counter = 0;
        if (isset($this->blocks)) {
            if (is_array($this->blocks)) {
                foreach ($this->blocks as $bl) {
                    $active = ( $counter == 0 ? 'active' : '');
                    $out .= '<div id="'.$this->tab_ids[$counter].'" class="tab-pane '.$active.'">';
                    $out .= $bl->show();
                    $out .= '</div>';

                    $counter++;
                }
            } else {
                $out .= $this->blocks->show();
            }
        }

        $out .= '</div></div>';
        return $out;
    }

    function addToHead() {
        $out = '';
        if (isset($this->blocks)) {
            if (is_array($this->blocks)) {
                foreach ($this->blocks as $bl) {
                    $out .= $bl->addToHead();
                }
            } else {
                $out .= $this->blocks->addToHead();
            }
        }
        return $out;
    }

    function addToFoot() {
        $out = '';
        if (isset($this->blocks)) {
            if (is_array($this->blocks)) {
                foreach ($this->blocks as $bl) {
                    $out .= $bl->addToFoot();
                }
            } else {
                $out .= $this->blocks->addToFoot();
            }
        }
        return $out;
    }

}

/*
<div class="tabbable tabs-left tabbable-bordered">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tb3_a">Section 1</a></li>
		<li><a data-toggle="tab" href="#tb3_b">Section 2</a></li>
		<li><a data-toggle="tab" href="#tb3_c">Section 3</a></li>
	</ul>
	<div class="tab-content">
		<div id="tb3_a" class="tab-pane active">
			<p>Section 1</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi elit dui, porta ac scelerisque placerat, rhoncus vitae sem. Nulla eget libero enim, facilisis accumsan eros.</p>
		</div>
		<div id="tb3_b" class="tab-pane">
			<p>Section 2</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi elit dui, porta ac scelerisque placerat, rhoncus vitae sem. Nulla eget libero enim, facilisis accumsan eros.</p>
		</div>
		<div id="tb3_c" class="tab-pane">
			<p>Section 3</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi elit dui, porta ac scelerisque placerat, rhoncus vitae sem. Nulla eget libero enim, facilisis accumsan eros.</p>
		</div>
	</div>
</div>
*/
