<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:34
 */

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

/**
 * This class is a container
 *
 * As we define in a dashboard rows containing one or more HTMLBlock
 * we can use this class in order to contain these
 */
class RowHTMLBlock extends BaseHTMLBlock {

    private $blocks;
    private $htmlTemplateLoader;

    /**
     * RowHTMLBlock constructor.
     * @param $blocks
     */
    public function __construct() {
        parent::__construct();
        $this->blocks = array();
        $this->rowblockid = '';
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function addBlock($block) {
        $this->blocks[] = $block;
    }

    function setRowBlockId( string $rowblockid ) {
        $this->rowblockid = $rowblockid;
    }

    function show(): string {
        $htmlbody = '';
        foreach ($this->blocks as $bl) {
            $htmlbody .= $bl->show();
        }
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array( '${htmlbody}', '${rowblockid}' ),
            array( $htmlbody, $this->rowblockid ),
            'RowBlock/body.html');
    }

    function addToHead(): string {
        $globalAddToHead = '';
        foreach ($this->blocks as $bl) {
            $globalAddToHead .= $bl->addToHead();
        }
        return $globalAddToHead;
    }

    function addToFoot(): string {
        $globalAddToFoot = '';
        foreach ($this->blocks as $bl) {
            $globalAddToFoot .= $bl->addToFoot();
        }
        return $globalAddToFoot;
    }

}
