<?php

/**
 * Created by fabio
 * Date: 07/01/2018
 * Time: 18:26
 */

namespace Firststep\Redirectors;

class URLRedirector implements Redirector {

    public function setURL(string $url) {
        $this->url = $url;
    }

    public function redirect() {
        header('Location: ' . $this->url );
        die();
    }

}