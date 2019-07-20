<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class BaseHTMLMenu extends BaseHTMLBlock {

    function __construct() {
        $this->brand = '';
        $this->buttonToggler = '';
        $this->body = '';
        $this->rightBody = '';
        $this->dropdownCounter = 1;
    }

    function addBrand( string $brand, string $url ) {
        $this->brand = '<a class="navbar-brand" href="' . $url . '">' . $title . '</a>';    
    }

    function addButtonToggler() {
        $this->buttonToggler = '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>';    
    }

    function addForm() {
        $this->rightBody .= '<form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>';    
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
