<?php

namespace Firststep\Templates\Blocks\Sidebars;

use Firststep\Common\Blocks\BaseBlock;
use Firststep\Common\Router\Router;

class AdminSidebar extends BaseBlock {
	
	function __construct( string $appname, string $active = 'home', $router ) {
		$this->appname = $appname;
		$this->active = $active;
		$this->router = $router;
	}
	
    function show(): string {
		$out = '<div class="sidebar-sticky">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_DASHBOARD ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_DASHBOARD ).'">
                      <span data-feather="home"></span>
                      Dashboard <span class="sr-only">(current)</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_ENTITY_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_ENTITY_LIST ).'">
                      <span data-feather="file"></span>
                      Entities
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_DOCUMENT_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_DOCUMENT_LIST ).'">
                      <span data-feather="file"></span>
                      Documents
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_TABLE_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_TABLE_LIST ).'">
                      <span data-feather="layers"></span>
                      Tables
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_REPORT_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_REPORT_LIST ).'">
                      <span data-feather="bar-chart-2"></span>
                      Reports
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="shopping-cart"></span>
                      Products
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="users"></span>
                      Users
                    </a>
                  </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                  <span>Saved reports</span>
                  <a class="d-flex align-items-center text-muted" href="#">
                    <span data-feather="plus-circle"></span>
                  </a>
                </h6>
                <ul class="nav flex-column mb-2">
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="file-text"></span>
                      Current month
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="file-text"></span>
                      Last quarter
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="file-text"></span>
                      Social engagement
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="file-text"></span>
                      Year-end sale
                    </a>
                  </li>
                </ul>
              </div>';
        return $out; 
    }
	
}
