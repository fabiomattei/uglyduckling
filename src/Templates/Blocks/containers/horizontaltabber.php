<?php

/**
 * Created by Fabio Mattei <burattino@gmail.com>
 * Date: 05/02/2016
 * Time: 10:41
 */

namespace templates\blocks\containers;

use core\blocks\BaseBlock;

class HorizontalTabber extends BaseBlock {

    function __construct($titles, $blocks, $tabid = 'tbh1' ) {
        $this->titles = $titles;
        $this->blocks = $blocks;
        $this->tab_ids = array( $tabid.'_a', $tabid.'_b', $tabid.'_c', $tabid.'_d', $tabid.'_e' );
    }

    function show() {
        $out = '<div id="tabs">
	                <ul>';
        $counter = 0;
        foreach ($this->titles as $tit) {
            $active = '';
            if ( $counter == 0 ) {
                $active = 'class="active"';
            }
            $out .= '<li '.$active.'><a href="#'.$this->tab_ids[$counter].'">'.$tit.'</a></li>';
            $counter++;
        }

        $out .= '</ul>';

        $counter = 0;
        if (isset($this->blocks)) {
            if (is_array($this->blocks)) {
                foreach ($this->blocks as $bl) {
                    $active = ( $counter == 0 ? 'active' : '');
                    $out .= '<div id="'.$this->tab_ids[$counter].'" class="'.$active.'">';
                    $out .= $bl->show();
                    $out .= '</div>';

                    $counter++;
                }
            } else {
                $out .= $this->blocks->show();
            }
        }

        $out .= '</div>';
        return $out;
    }

    function addToHead() {
        $out = '<link href="'.BASEPATH.'assets/lib/jquery-ui/jquery-ui.css" rel="stylesheet">';
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
        $out = '<script src="'.BASEPATH.'assets/lib/jquery-ui/jquery-ui.js"></script>
			<script>
				$( "#tabs" ).tabs();
		    </script>';
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
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">First</a></li>
		<li><a href="#tabs-2">Second</a></li>
		<li><a href="#tabs-3">Third</a></li>
	</ul>
	<div id="tabs-1">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
	<div id="tabs-2">Phasellus mattis tincidunt nibh. Cras orci urna, blandit id, pretium vel, aliquet ornare, felis. Maecenas scelerisque sem non nisl. Fusce sed lorem in enim dictum bibendum.</div>
	<div id="tabs-3">Nam dui erat, auctor a, dignissim quis, sollicitudin eu, felis. Pellentesque nisi urna, interdum eget, sagittis et, consequat vestibulum, lacus. Mauris porttitor ullamcorper augue.</div>
</div>

*/
