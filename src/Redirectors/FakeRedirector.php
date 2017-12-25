<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 25/12/17
 * Time: 17.53
 */

namespace Firststep\Redirectors;

class FakeRedirector implements Redirector {

    public function redirect() {
        echo 'Redirecting';
    }

}