<?php

namespace Fabiom\UglyDuckling\Common\Json\Variables;

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

    /**
     * @param string $input
     * @return string
     *
     * This method parse the string and make all the sobsitution.
     *
     * For every GET[VARIABLE_NAME] the method finds, the method is going to replace it with the content of the variale $this->getParameters['VARIABLE_NAME']
     * The same applies to POST and SESSION variables
     */
    public function parseString( string $input ): string {
        return preg_replace_callback_array(
            [
                '/POST\[([a-zA-Z0-9]+)\]/' => function ($matches) {
                    return $this->postParameters[$matches[1]];
                },
                '/GET\[([a-zA-Z0-9]+)\]/' => function ($matches) {
                    return $this->getParameters[$matches[1]];
                },
                '/SESSION\[([a-zA-Z0-9]+)\]/' => function ($matches) {
                    return $this->sessionParameters[$matches[1]];
                }
            ],
            $input);
    }

}
