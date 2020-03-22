<?php

namespace Fabiom\UglyDuckling\Common\Utils;

/**
 * User: Fabio Mattei
 * Date: 22/03/2020
 * Time: 08:32
 *
 * Helps to write the code with chainable methods in a functional way
 *
 * $computers_list = new Collection([
 *   'Apple MacBook Air',
 *   'Dell XPS 15',
 *   'Apple MacBook Pro',
 *   'Apple Mac Mini',
 *   'Lenovo Ideapad',
 *   'Apple Mac Pro'
 * ]);
 *
 * $applescomputers = $computers_list
 *      ->filter(function ($item) {
 *          return preg_match('/^.+\sApple$/', $item);
 *      })
 *      ->map(function ($item) {
 *          return str_replace('Apple ', '', $item);
 *      })
 *      ->sort(function ($a, $b) {
 *          return strcasecmp($a, $b);
 *      })
 *      ->reduce(function($carry, $item) {
 *          return $carry .', '. $item
 *      }, "Apple computers: ")
 *      ->execute();
 *
 *      echo($applescomputers); // Apple computers: MacBook Air, MacBook Pro, Mac Mini, Mac Pro
 *
 */
class Collection {
    
    private $array;    

    public function __construct($array) {
        $this->array = $array;
    }    

    public function map($callback) {
        $this->array = array_map($callback, $this->array);
        return $this;
    }

    public function reduce($callback, $initial) {
        $this->array = array_reduce($this->array, $callback, $initial);
        return $this;
    }

    public function sort($callback) {
        usort($this->array, $callback);
        return $this;
    }

    public function filter($callback) {
        $this->array = array_filter($this->array, $callback);
        return $this;
    }    

    public function execute() {
        return $this->array;
    }

}
