<?php

/**
 * Created by Fabio Mattei
 * Date: 10/11/2018
 * Time: 07:17
 */

namespace Firststep\Common\Json\TemplateBuilder;

class NotificationBuilder {

    private $queryExecuter;
    private $queryBuilder;
    private $resource;
    private $router;
    private $dbconnection;
    private $parameters;
    private $action;


    /**
     * @param mixed $queryStructure
     */
    public function setQueryStructure($queryStructure) {
        $this->queryStructure = $queryStructure;
    }

    /**
     * @param mixed $parameters
     * the $parameters variable contains all values for the query
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    public function createNotifications() {
    }

}

