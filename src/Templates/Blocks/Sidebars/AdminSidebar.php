<?php

namespace Fabiom\UglyDuckling\Templates\Blocks\Sidebars;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\Router\Router;

class AdminSidebar extends BaseHTMLBlock {
	
	function __construct( string $appname, string $active = 'home', $router ) {
		$this->appname = $appname;
		$this->active = $active;
		$this->router = $router;
	}

	// icons list here: https://feathericons.com/

    function show(): string {
		$out = '<div class="sidebar-sticky">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_DASHBOARD ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_DASHBOARD ).'">
                      <span data-feather="home"></span>
                      Dashboard <span class="sr-only">(current)</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_METRICS_DASHBOARD ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_METRICS_DASHBOARD ).'">
                      <span data-feather="home"></span>
                      Metrics <span class="sr-only">(current)</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_ENTITY_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_ENTITY_LIST ).'">
                      <span data-feather="file"></span>
                      Entities
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_DOCUMENT_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_DOCUMENT_LIST ).'">
                      <span data-feather="file"></span>
                      Documents
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_TABLE_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_TABLE_LIST ).'">
                      <span data-feather="layers"></span>
                      Tables
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_EXPORT_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_EXPORT_LIST ).'">
                      <span data-feather="bar-chart-2"></span>
                      Exports
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_SEARCH_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_SEARCH_LIST).'">
                      <span data-feather="search"></span>
                      Searches
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" '.( $this->active === Router::ROUTE_ADMIN_FORM_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_FORM_LIST ).'">
                      <span data-feather="edit"></span>
                      Forms
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" '.( $this->active === Router::ROUTE_ADMIN_TRANSACTION_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_TRANSACTION_LIST ).'">
                      <span data-feather="cpu"></span>
                      Logics
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="target"></span>
                      Dashboards
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" '.( $this->active === Router::ROUTE_ADMIN_INFO_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_INFO_LIST ).'">
                      <span data-feather="eye"></span>
                      Infos
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" '.( $this->active === Router::ROUTE_ADMIN_GROUP_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_GROUP_LIST ).'">
                      <span data-feather="users"></span>
                      Groups
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_USER_LIST ? 'active' : '' ).'" href="'.$this->router->makeRelativeUrl( Router::ROUTE_ADMIN_USER_LIST ).'">
                      <span data-feather="user"></span>
                      Users
                    </a>
                  </li>
                </ul>
                </div>';
        return $out; 
    }
	
}
