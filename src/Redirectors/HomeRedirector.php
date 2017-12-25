<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 25/12/17
 * Time: 16.59
 */

namespace Firststep\Redirectors;

class HomeRedirector implements Redirector {

    /**
     * HomeRedirector constructor.
     */
    public function __construct( string $basepath ) {
        $this->basepath = $basepath;
    }

    public function redirect() {
        header('Location: ' . $this->basepath . 'public/login.html');
        die();
    }

}
