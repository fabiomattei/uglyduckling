<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;

class BaseHTMLMenu extends BaseHTMLBlock {

    private HtmlTemplateLoader $htmlTemplateLoader;

    function __construct() {
        $this->brand = '';
        $this->buttonToggler = '';
        $this->body = '';
        $this->rightBody = '';
        $this->dropdownCounter = 1;
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function addBrand( string $brand, string $url ) {
        $this->brand = $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${url}', '${title}'),
            array($url, $brand),
            'Menu/brand.html');
    }

    function addButtonToggler() {
        $this->buttonToggler = $this->htmlTemplateLoader->loadTemplate('Menu/buttontoggeler.html');
    }

    function addForm() {
        $this->rightBody .= $this->htmlTemplateLoader->loadTemplate('Menu/form.html');
    }

    function addNavItem( string $label, string $url, bool $active, bool $current ) {
        $activestr = $active ? $this->htmlTemplateLoader->loadTemplate('Menu/active.html') : '';
        $currentstr = $current ? $this->htmlTemplateLoader->loadTemplate('Menu/current.html') : '';

        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${active}', '${current}', '${url}', '${label}'),
            array($activestr, $currentstr, $url, $label),
            'Menu/navitem.html');
    }

    function addNavItemWithDropdown( string $label, string $url, bool $active, bool $current, array $submenuItems, string $controllerName = '', string $resourceName = '' ) {
        $submenu = '';
        foreach ($submenuItems as $item) {
            if ( $controllerName == $item->controller || $resourceName == $item->resource ) {
                $activestr = $active ? $this->htmlTemplateLoader->loadTemplate('Menu/submenuitemactive.html') : '';
                $currentstr = $current ? $this->htmlTemplateLoader->loadTemplate('Menu/submenuitemcurrent.html') : '';
            }

            $submenu .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                array('${active}', '${current}', '${url}', '${label}'),
                array($activestr, $currentstr, $item->url, $item->label),
                'Menu/submenuitem.html');
        }

        $activestr = $active ? $this->htmlTemplateLoader->loadTemplate('Menu/active.html') : '';
        $currentstr = $current ? $this->htmlTemplateLoader->loadTemplate('Menu/current.html') : '';

        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${active}', '${current}', '${url}', '${label}', '${dropdownCounter}', '${submenu}'),
            array($activestr, $currentstr, $url, $label, $this->dropdownCounter, $submenu),
            'Menu/menuitem.html');
        $this->dropdownCounter++;
    }

    function show(): string {
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${brand}', '${buttonToggler}', '${body}', '${rightBody}'),
            array($this->brand, $this->buttonToggler, $this->body, $this->rightBody),
            'Menu/body.html');
    }

}
