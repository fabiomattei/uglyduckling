<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

class BaseHTMLInfo extends BaseHTMLBlock {

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

    function addTextField( string $label, string $value, string $width, string $cssClass = '' ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${label}', '${value}', '${ColWidth}', '${cssClass}'),
            array($label, htmlspecialchars( $value ), ColWidth::getWidth(ColWidth::MEDIUM, $width), $cssClass),
            'Info/textfield.html');
    }

    function addTextAreaField( string $label, string $value, string $width, string $cssClass = '' ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${label}', '${value}', '${ColWidth}', '${cssClass}'),
            array($label, htmlspecialchars( $value ), ColWidth::getWidth(ColWidth::MEDIUM, $width), $cssClass),
            'Info/textfield.html');
    }

    function addDropdownField( string $label, array $options, string $value, string $width ) {
        $selectedvalue = '';
        foreach ($options as $key => $val) {
            $selectedvalue .= ( $key == $value ? $val : '' );
        }
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${label}', '${value}', '${ColWidth}'),
            array($label, htmlspecialchars( $selectedvalue ), ColWidth::getWidth(ColWidth::MEDIUM, $width)),
            'Info/textfield.html');
    }
	
	function addCurrencyField( string $label, string $value, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${label}', '${value}', '${ColWidth}'),
            array($label, htmlspecialchars( $value ), ColWidth::getWidth(ColWidth::MEDIUM, $width)),
            'Info/textfield.html');
	}
	
	function addDateField( string $label, string $value, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${label}', '${value}', '${ColWidth}'),
            array($label, date( 'd/m/Y', strtotime(htmlspecialchars( $value ))), ColWidth::getWidth(ColWidth::MEDIUM, $width)),
            'Info/textfield.html');
	}

    function addFileUploadField( string $name, string $label, string $width ) {
        $this->body .= 'addFileUploadField TO BE implemented';
    }

    function addHelpingText( string $title, string $text, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${label}', '${value}', '${ColWidth}'),
            array($title, htmlspecialchars( $text ), ColWidth::getWidth(ColWidth::MEDIUM, $width)),
            'Info/textfield.html');
    }
	
    function addParagraph( string $text, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${text}', '${ColWidth}'),
            array(htmlspecialchars( $text ), ColWidth::getWidth(ColWidth::MEDIUM, $width)),
            'Info/paragraph.html');
    }

    function addUnfilteredParagraph( string $text, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${text}', '${ColWidth}'),
            array($text, ColWidth::getWidth(ColWidth::MEDIUM, $width)),
            'Info/paragraph.html');
    }

    function addRow() {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'Form/addrow.html');
    }

    function closeRow( string $comment = '' ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${comment}'),
            array($comment),
            'Form/closerow.html');
    }

}
