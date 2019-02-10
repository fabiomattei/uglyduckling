<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;
use Firststep\Common\Blocks\ColWidth;

class BaseInfo extends BaseBlock {

    private $title;
    private $subtitle;
    private $body;
    private $htmlTemplateLoader;

    function __construct() {
        $this->body = '';
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }
	
	function setTitle( string $title ) {
		$this->title = $title;
	}
	
	function setSubTitle( string $subTitle ) {
		$this->subTitle = $subTitle;
	}

    function show(): string {
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${title}', '${subtitle}', '${body}'),
            array($this->title, $this->subtitle, $this->body),
            'Info/body.html');
    }

    function addTextField( string $label, string $value, string $width ) {
        $this->body .= '<div class="'.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><h5>'.$label.'</h5><p>'.$value.'</p></div>';
    }

    function addTextAreaField( string $label, string $value, string $width ) {
        $this->body .= '<div class="'.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><h5>'.$label.'</h5><p>'.$value.'</p></div>';
    }

    function addDropdownField( string $name, string $label, array $options, string $value, string $width ) {
        $this->body .= '<div class="'.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><h5>'.$label.'</h5><p>';
        foreach ($options as $key => $val) {
            $this->body .= ( $key==$value ? $val : '' );
        }
        $this->body .= '</p></div>';
    }
	
	function addCurrencyField( string $label, string $value, string $width ) {
		$this->body .= '<div class="'.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><h5>'.$label.'</h5><p>'.$value.'</p></div>';
	}
	
	function addDateField( string $label, string $value, string $width ) {
		$this->body .= '<div class="'.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><h5>'.$label.'</h5><p>'.date( 'd/m/Y', strtotime($value) ).'</p></div>';
	}

    function addFileUploadField( string $name, string $label, string $width ) {
        $this->body .= '<div class="'.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><input type="file" id="'.$name.'" name="'.$name.'"></div>';
    }

    function addHelpingText( string $title, string $text, string $width ) {
        $this->body .= '<div class="'.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><h5>'.$title.'</h5><p>'.$text.'</p></div>';
    }
	
    function addParagraph( string $text, string $width ) {
        $this->body .= '<div class="'.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><p>'.$text.'</p></div>';
    }
	
	function addRow() {
		$this->body .= '<div class="row">';
	}
	
	function closeRow( string $comment = '' ) {
		$this->body .= '</div>  <!-- '.$comment.' -->';
	}

}
