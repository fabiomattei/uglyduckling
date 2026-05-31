<?php

namespace Fabiom\UglyDuckling\Framework\Components;

use Fabiom\UglyDuckling\Framework\Utils\PageStatus;
use PDO;
use PDOException;

abstract class BaseComponent {

    public PageStatus $pageStatus;

    protected array $get_validation_rules = [];
    protected array $get_filter_rules = [];
    protected array $post_validation_rules = [];
    protected array $post_filter_rules = [];

    protected array $getParameters = [];
    protected array $postParameters = [];

    public string $postSuccessUrl = '';
    public string $postSuccessMessage = '';

    abstract public function render(array $data): void;

    public function addToHead(): string {
        return '';
    }

    public function addToFoot(): string {
        return '';
    }

    protected function setSuccess(string $message): void {
        $_SESSION['msgsuccess'] = $message;
    }

    protected function setError(string $message): void {
        $_SESSION['msgerror'] = $message;
    }

    protected function check_authorization_resource_request(): bool {
        return true;
    }

    protected function get_request(): array {
        return [];
    }

    protected function post_request(): void {
    }

    public function renderAsPanel(): void {
        if (!$this->check_authorization_resource_request()) {
            return;
        }
        $this->getParameters = $this->validateGet();
        $this->render($this->get_request());
    }

    public function handlePost(): void {
        if ($this->validatePost()) {
            $this->post_request();
        }
    }

    protected function validateGet(): array {
        if (count($this->get_validation_rules) === 0) {
            return $_GET;
        }
        $gump = new \GUMP();
        $parms = $gump->sanitize($_GET);
        $gump->validation_rules($this->get_validation_rules);
        $gump->filter_rules($this->get_filter_rules);
        $result = $gump->run($parms);
        if ($result === false) {
            return [];
        }
        $allowedKeys = $this->get_validation_rules + $this->get_filter_rules;
        return array_intersect_key($result, $allowedKeys);
    }

    protected function validatePost(): bool {
        if (count($this->post_validation_rules) === 0) {
            $this->postParameters = $_POST;
            return true;
        }
        $gump = new \GUMP();
        $parms = $gump->sanitize($_POST);
        $gump->validation_rules($this->post_validation_rules);
        $gump->filter_rules($this->post_filter_rules);
        $result = $gump->run($parms);
        if ($result === false) {
            return false;
        }
        $allowedKeys = $this->post_validation_rules + $this->post_filter_rules;
        $this->postParameters = array_intersect_key($result, $allowedKeys);
        return true;
    }

    protected function executeSelectQuery(string $query, array $params): array {
        try {
            $namedParams = [];
            foreach ($params as $key => $value) {
                $namedParams[':' . $key] = $value;
            }
            $STH = $this->pageStatus->getDbconnection()->getDBH()->prepare($query);
            $STH->execute($namedParams);
            return $STH->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->pageStatus->logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('Component query failed');
        }
    }

    protected function executeWriteQuery(string $query, array $params): void {
        try {
            $namedParams = [];
            foreach ($params as $key => $value) {
                $namedParams[':' . $key] = $value;
            }
            $this->pageStatus->getDbconnection()->getDBH()->prepare($query)->execute($namedParams);
        } catch (PDOException $e) {
            $this->pageStatus->logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('Component query failed');
        }
    }

}
