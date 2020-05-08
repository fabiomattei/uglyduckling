<?php

/**
 * Created Fabio Mattei
 * Date: 2020-03-30
 * Time: 19:21
 */

namespace Fabiom\UglyDuckling\Common\Tags\DefaultTags;

use Fabiom\UglyDuckling\Common\Tags\BaseHTMLTag;

/**
 * A Json small block is a JSON resource (object or array or composite)
 * that we need to convert in HTML
 */
class HTMLButtonTag extends BaseHTMLTag {

    const BLOCK_TYPE = 'button';

    /**
     * Takes a JSON resource (object or array or composite) and convert it in HTML
     */
    function getHTML( $resource, $parameters ): string {
        return '';
    }

}
