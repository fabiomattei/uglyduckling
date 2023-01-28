<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;

class BaseHTMLMenu extends BaseHTMLBlock {

    private HtmlTemplateLoader $htmlTemplateLoader;

    function __construct() {
        parent::__construct();
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
        $this->rightBody .= $this->htmlTemplateLoader->loadTemplate('Menu/rightbody.html');
    }

    function addNavItem( string $label, string $url, bool $active, bool $current, bool $right = false ) {
        $activestr = $active ? $this->htmlTemplateLoader->loadTemplate('Menu/active.html') : '';
        $currentstr = $current ? $this->htmlTemplateLoader->loadTemplate('Menu/current.html') : '';

        if ( $right ) {
            $this->rightBody .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                array('${active}', '${current}', '${url}', '${label}'),
                array($activestr, $currentstr, $url, $label),
                'Menu/navitem.html');
        } else {
            $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                array('${active}', '${current}', '${url}', '${label}'),
                array($activestr, $currentstr, $url, $label),
                'Menu/navitem.html');
        }
    }

    function addNavItemWithDropdown( string $label, string $url, bool $active, bool $current, array $submenuItems, string $controllerName = '', string $resourceName = '', bool $right = false ) {
        $submenu = '';
        $activestr = $active ? $this->htmlTemplateLoader->loadTemplate('Menu/submenuitemactive.html') : '';
        $currentstr = $current ? $this->htmlTemplateLoader->loadTemplate('Menu/submenuitemcurrent.html') : '';
        foreach ($submenuItems as $item) {
            if ( isset( $controllerName ) AND $item->controller == $controllerName ) {
                $submenu .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                    array('${active}', '${current}', '${url}', '${label}'),
                    array($activestr, $currentstr, $item->url, $item->label),
                    'Menu/submenuitem.html');
            } elseif ( isset( $resourceName ) AND $item->resource == $resourceName) {
                $submenu .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                    array('${active}', '${current}', '${url}', '${label}'),
                    array($activestr, $currentstr, $item->url, $item->label),
                    'Menu/submenuitem.html');
            } else {
                $submenu .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                    array('${active}', '${current}', '${url}', '${label}'),
                    array('', '', $item->url, $item->label),
                    'Menu/submenuitem.html');
            }
        }

        $activestr = $active ? $this->htmlTemplateLoader->loadTemplate('Menu/active.html') : '';
        $currentstr = $current ? $this->htmlTemplateLoader->loadTemplate('Menu/current.html') : '';

        if ( $right ) {
            $this->rightBody .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                array('${active}', '${current}', '${url}', '${label}', '${dropdownCounter}', '${submenu}'),
                array($activestr, $currentstr, $url, $label, $this->dropdownCounter, $submenu),
                'Menu/menuitem.html');
        } else {
            $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                array('${active}', '${current}', '${url}', '${label}', '${dropdownCounter}', '${submenu}'),
                array($activestr, $currentstr, $url, $label, $this->dropdownCounter, $submenu),
                'Menu/menuitem.html');
        }

        $this->dropdownCounter++;
    }

    function show(): string {
        if ( $this->rightBody != '' ) {
            $rightSubmenu = $this->rightBody;
            $rightSubmenu = $this->htmlTemplateLoader->loadTemplateAndReplace(
                array('${rightbody}'),
                array($rightSubmenu),
                'Menu/rightbody.html');
        } else {
            $rightSubmenu = '';
        }

        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${brand}', '${buttonToggler}', '${body}', '${rightbody}'),
            array($this->brand, $this->buttonToggler, $this->body, $rightSubmenu),
            'Menu/body.html');
    }

}
