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
// 'Templates/HTML/Form/body.html'
    public function loadTemplate(string $filename) {
        return file_get_contents($this->path.$filename);
    }

}
