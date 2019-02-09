<?php

/**
 * Created by fabio
 * Date: 09/02/19
 * Time: 14.16
 */

namespace Firststep\Common\Utils;

class HtmlTemplateLoader {

    private $path;

    public function setPath(string $path) {
        $this->path = $path;
    }

    /**
     * @param $search string or array to look for
     * @param $replace string or array to replace
     * @param $filename name of the template html file
     * @return string
     */
    public function loadTemplateAndReplace($search, $replace, $filename): string {
        return str_replace( $search, $replace, file_get_contents($this->path.$filename));
    }

}
