<?php

/**
 * Created by Fabio Mattei
 * 
 * Date: 25/12/17
 * Time: 17.53
 */

namespace Fabiom\UglyDuckling\Common\Redirectors;

/**
 * This class is a fake for testing porpuses
 * It does not do anything
 */
class FakeRedirector implements Redirector {
	
	private $url;

    public function setURL(string $url) {
        $this->url = $url;
    }

    public function redirect() {
        echo 'Redirecting to: ' . $this->url;
    }

}