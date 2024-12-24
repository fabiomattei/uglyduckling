<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

class BaseHtmlDoc extends BaseHTMLBlock {

    protected $html = '';

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function h1( string $text, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<h1 '.$this->addAttributes($attributes).'>'.$text.'</h1>';
        } else {
            $this->html .= '<h1>'.$text.'</h1>';
        }
    }

    function h2( string $text, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<h2 '.$this->addAttributes($attributes).'>'.$text.'</h2>';
        } else {
            $this->html .= '<h2>'.$text.'</h2>';
        }
    }

    function h3( string $text, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<h3 '.$this->addAttributes($attributes).'>'.$text.'</h3>';
        } else {
            $this->html .= '<h3>'.$text.'</h3>';
        }
    }

    function h4( string $text, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<h4 '.$this->addAttributes($attributes).'>'.$text.'</h4>';
        } else {
            $this->html .= '<h4>'.$text.'</h4>';
        }
    }

    function h5( string $text, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<h5 '.$this->addAttributes($attributes).'>'.$text.'</h5>';
        } else {
            $this->html .= '<h5>'.$text.'</h5>';
        }
    }

    function h6( string $text, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<h6 '.$this->addAttributes($attributes).'>'.$text.'</h6>';
        } else {
            $this->html .= '<h6>'.$text.'</h6>';
        }
    }

    function paragraph( string $text, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<p '.$this->addAttributes($attributes).'>'.$text.'</p>';
        } else {
            $this->html .= '<p>'.$text.'</p>';
        }
    }

    function img( string $src, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<img src="'.$src.'" '.$this->addAttributes($attributes).' />';
        } else {
            $this->html .= '<img src="'.$src.'" />';
        }
    }

    function openTable( array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<table '.$this->addAttributes($attributes).'>';
        } else {
            $this->html .= '<table>';
        }
    }

    function closeTable( array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '</table '.$this->addAttributes($attributes).'>';
        } else {
            $this->html .= '</table>';
        }
    }

    function openRow( array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<tr '.$this->addAttributes($attributes).'>';
        } else {
            $this->html .= '<tr>';
        }
    }

    function closeRow( array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '</tr '.$this->addAttributes($attributes).'>';
        } else {
            $this->html .= '</tr>';
        }
    }

    function th(string $text, array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<th '.$this->addAttributes($attributes).'>'.$text.'</th>';
        } else {
            $this->html .= '<th>'.$text.'</th>';
        }
    }

    function td(string $text,  array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<td '.$this->addAttributes($attributes).'>'.$text.'</td>';
        } else {
            $this->html .= '<td>'.$text.'</td>';
        }
    }

    function openOl( array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<ol '.$this->addAttributes($attributes).'>';
        } else {
            $this->html .= '<ol>';
        }
    }

    function closeOl( array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '</ol '.$this->addAttributes($attributes).'>';
        } else {
            $this->html .= '</ol>';
        }
    }

    function openUl( array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '<ul '.$this->addAttributes($attributes).'>';
        } else {
            $this->html .= '<ul>';
        }
    }

    function closeUl( array $attributes = [] ) {
        if (count($attributes) > 0) {
            $this->html .= '</ul '.$this->addAttributes($attributes).'>';
        } else {
            $this->html .= '</ul>';
        }
    }

    function li( $text, array $attributes = []  ) {
        if (count($attributes) > 0) {
            $this->html .= '<li '.$this->addAttributes($attributes).'>'.$text.'</li>';
        } else {
            $this->html .= '<li>'.$text.'</li>';
        }
    }

    function label( $text, array $attributes = []  ) {
        if (count($attributes) > 0) {
            $this->html .= '<label '.$this->addAttributes($attributes).'>'.$text.'</label>';
        } else {
            $this->html .= '<label>'.$text.'</label>';
        }
    }
    function input( $text, array $attributes = []  ) {
        if (count($attributes) > 0) {
            $this->html .= '<input '.$this->addAttributes($attributes).'>'.$text.'</input>';
        } else {
            $this->html .= '<input>'.$text.'</input>';
        }
    }

    function textarea( $text, array $attributes = []  ) {
        if (count($attributes) > 0) {
            $this->html .= '<textarea '.$this->addAttributes($attributes).'>'.$text.'</textarea>';
        } else {
            $this->html .= '<textarea>'.$text.'</textarea>';
        }
    }

    function select( $text, array $attributes = []  ) {
        if (count($attributes) > 0) {
            $this->html .= '<select '.$this->addAttributes($attributes).'>'.$text.'</select>';
        } else {
            $this->html .= '<select>'.$text.'</select>';
        }
    }

    function show(): string {
        return $this->html;
    }

    /**
     * This function takes an array of attributes and return a string of formatted HTML
     *
     * Example:
     * $attributes = [ 'id' => 'myid', 'class' => 'myclass' ]
     * return id="myid" class="myclass"
     * @param $attributes
     * @return string
     */
    private function addAttributes( $attributes ): string {
        return implode(' ', array_map(
            function ($v, $k) {
                return $k.'="'.$v.'"';
            },
            $attributes,
            array_keys($attributes)
        ));
    }

}