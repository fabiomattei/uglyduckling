<?php

namespace Fabiom\UglyDuckling\Framework\Components;

use Fabiom\UglyDuckling\Framework\Controllers\CommonController;
use Fabiom\UglyDuckling\Framework\Loggers\LocalFileLogger;

class ComponentControllerFactory {

    /**
     * @param mixed $entry a component class name, ['tabs'=>…] structure, or panel-node array
     * @param CommonController $fallbackController returned (and logged) when $entry names a missing class
     */
    public static function create(mixed $entry, LocalFileLogger $logger, CommonController $fallbackController): CommonController {
        if (is_array($entry) && isset($entry['tabs'])) {
            return new class($entry['tabs']) extends BaseTabsComponent {
                public function __construct(array $componentTabs) {
                    parent::__construct();
                    $this->tabs = $componentTabs;
                }
            };
        }

        if (is_array($entry)) {
            return self::wrapAsGrid($entry);
        }

        if (!class_exists($entry)) {
            $logger->write('Component class not found: ' . $entry);
            return $fallbackController;
        }

        if (is_subclass_of($entry, BasePageComponent::class)) {
            return new $entry;
        }

        return self::wrapAsGrid([['cssclass' => 'col-md-12', 'component' => $entry]]);
    }

    private static function wrapAsGrid(array $panels): BaseGridComponent {
        return new class($panels) extends BaseGridComponent {
            public function __construct(array $componentPanels) {
                parent::__construct();
                $this->panels = $componentPanels;
            }
        };
    }

}
