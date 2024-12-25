<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Framework\Utils\HtmlTemplateLoader;

class BaseHTMLMenu extends BaseHTMLBlock {

    public $brand;
    public $buttonToggler;
    public $body;
    public $rightBody;
    public $dropdownCounter;

    function __construct() {
        parent::__construct();
        $this->brand = '';
        $this->buttonToggler = '';
        $this->body = '';
        $this->rightBody = '';
        $this->dropdownCounter = 1;
    }

    function addBrand( string $brand, string $url ) {
        $this->brand = HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
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
            $this->rightBody .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
                array('${active}', '${current}', '${url}', '${label}'),
                array($activestr, $currentstr, $url, $label),
                'Menu/navitem.html');
        } else {
            $this->body .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
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
                $submenu .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
                    array('${active}', '${current}', '${url}', '${label}'),
                    array($activestr, $currentstr, $item->url, $item->label),
                    'Menu/submenuitem.html');
            } elseif ( isset( $resourceName ) AND $item->resource == $resourceName) {
                $submenu .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
                    array('${active}', '${current}', '${url}', '${label}'),
                    array($activestr, $currentstr, $item->url, $item->label),
                    'Menu/submenuitem.html');
            } else {
                $submenu .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
                    array('${active}', '${current}', '${url}', '${label}'),
                    array('', '', $item->url, $item->label),
                    'Menu/submenuitem.html');
            }
        }

        $activestr = $active ? $this->htmlTemplateLoader->loadTemplate('Menu/active.html') : '';
        $currentstr = $current ? $this->htmlTemplateLoader->loadTemplate('Menu/current.html') : '';

        if ( $right ) {
            $this->rightBody .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
                array('${active}', '${current}', '${url}', '${label}', '${dropdownCounter}', '${submenu}'),
                array($activestr, $currentstr, $url, $label, $this->dropdownCounter, $submenu),
                'Menu/menuitem.html');
        } else {
            $this->body .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
                array('${active}', '${current}', '${url}', '${label}', '${dropdownCounter}', '${submenu}'),
                array($activestr, $currentstr, $url, $label, $this->dropdownCounter, $submenu),
                'Menu/menuitem.html');
        }

        $this->dropdownCounter++;
    }

    function show(): string {
        if ( isset($this->rightBody) AND $this->rightBody != '' ) {
            $rightSubmenu = $this->rightBody;
            $rightSubmenu = HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
                array('${rightbody}'),
                array($rightSubmenu),
                'Menu/rightbody.html');
        } else {
            $rightSubmenu = '';
        }

        return HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array('${brand}', '${buttonToggler}', '${body}', '${rightbody}'),
            array($this->brand, $this->buttonToggler, $this->body, $rightSubmenu),
            'Menu/body.html');
    }

}
