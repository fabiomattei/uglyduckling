<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 05/02/2016
 * Time: 10:37
 */

namespace templates\blocks\containers;

use core\blocks\BaseBlock;

class ColContainer extends BaseBlock {

    function __construct($width, $blocks) {
        $this->width  = $width;
        $this->blocks = $blocks;
    }

    function show() {
        $out = '';

        if (isset($this->blocks)) {
            if (is_array($this->blocks)) {
                $counter = 0;
                foreach ($this->blocks as $bl) {
                    $toadd = ( $counter == 0 ? '' : 'style="margin-top: 20px;"');
                    $out .= '<div class="'.$this->width.'" '.$toadd.'>
                    <div class="panel panel-default">';
                    $out .= $bl->show();
                    $out .= '</div></div>';

                    $counter++;
                }
            } else {
                $out .= '<div class="'.$this->width.'">';
                $out .= $this->blocks->show();
                $out .= '</div>';
            }
        }

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
