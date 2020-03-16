<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;

class BaseHTMLMenu extends BaseHTMLBlock {

    private /* HtmlTemplateLoader */ $htmlTemplateLoader;

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
        $this->buttonToggler = $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'Menu/buttontoggeler.html');
    }

    function addForm() {
        $this->rightBody .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'Menu/form.html');
    }

    function addNavItem( string $label, string $url, bool $active, bool $current ) {
        $this->body .= '<li class="nav-item '.( $active ? 'active' : '' ).'">
            <a class="nav-link" href="'.$url.'">'.$label.' '.( $current ? '<span class="sr-only">(current)</span>' : '' ).'</a>
          </li>';
    }

    function addNavItemWithDropdown( string $label, string $url, bool $active, bool $current, array $submenuItems ) {
        $submenu = '';
        foreach ($submenuItems as $item) {
            $submenu .= '<a class="dropdown-item" href="'.$item->url.'">'.$item->label.'</a>';
        }
        $this->body .= '<li class="nav-item dropdown'.( $active ? 'active' : '' ).'">
            <a class="nav-link dropdown-toggle" href="'.$url.'" id="dropdown'.$this->dropdownCounter.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$label.' '.( $current ? '<span class="sr-only">(current)</span>' : '' ).'</a>
            <div class="dropdown-menu" aria-labelledby="dropdown'.$this->dropdownCounter.'">'.$submenu.'</div>
          </li>';
        $this->dropdownCounter++;
    }

    function show(): string {
        return $this->brand.
            $this->buttonToggler.
        '<div class="collapse navbar-collapse" id="navbarsExampleDefault">'.
        '<ul class="navbar-nav mr-auto">'.$this->body.'</ul>'.
        $this->rightBody.
        '</div>';
    }

}
