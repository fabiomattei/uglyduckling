<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:56
 */

namespace Firststep\Common\Builders;


use Firststep\Common\Blocks\CardBlock;

class PanelBuilder {

    static function getPanel($panel) {
        $panelBlock = new CardBlock;
        $panelBlock->setTitle($panel->title ?? '');
        $panelBlock->setWidth($panel->width ?? '3');
        return $panelBlock;
    }

}
