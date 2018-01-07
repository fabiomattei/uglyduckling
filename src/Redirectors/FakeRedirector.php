<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 25/12/17
 * Time: 17.53
 */

namespace Firststep\Redirectors;

class FakeRedirector implements Redirector {

    public function setURL(string $url) {
        // nothing to do here
    }

    public function redirect() {
        echo 'Redirecting';
    }

}