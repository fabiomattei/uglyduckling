<?php

namespace Firststep\Common\Json\Variables;

class StringParser {

    protected $getParameters;
    protected $postParameters;
    protected $sessionParameters;

    /**
     * @param mixed $parameters
     */
    public function setGetParameters( $getParameters ) {
        $this->getParameters = $getParameters;
    }

    /**
     * @param mixed $postparameters
     */
    public function setPostparameters( $postParameters ) {
        $this->postParameters = $postParameters;
    }

    /**
     * @param mixed $sessionparameters
     */
    public function setSessionparameters( $sessionParameters ) {
        $this->sessionParameters = $sessionParameters;
    }

    public function parseString( string $input ): string {
        return $input;
    }

}
