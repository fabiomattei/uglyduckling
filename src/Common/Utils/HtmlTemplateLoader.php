<?php

/**
 * Created by fabio
 * Date: 09/02/19
 * Time: 14.16
 */

namespace Fabiom\UglyDuckling\Common\Utils;

class HtmlTemplateLoader {

    private $path;

    public function setPath(string $path) {
        $this->path = $path;
    }

    /**
     * Load the template file replacing the strings
     *
     * @param $search string or array to look for
     * @param $replace string or array to replace
     * @param $filename name of the template html file
     * @return string
     */
    public function loadTemplateAndReplace($search, $replace, $filename): string {
        return str_replace( $search, $replace, file_get_contents($this->path.$filename));
    }

    /**
     * Load the template file
     *
     * @param $search string or array to look for
     * @param $replace string or array to replace
     * @param $filename name of the template html file
     * @return string
     */
    public function loadTemplate($filename): string {
        return file_get_contents($this->path.$filename);
    }

}
