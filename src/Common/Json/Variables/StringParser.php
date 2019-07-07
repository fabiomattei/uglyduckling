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

    /*
            Pattern p = Pattern.compile("GET\\[([a-zA-Z0-9]+)\\]");
            Matcher m = p.matcher(this.pattern);

            while (m.find()) {
                String group = m.group(1);
                out = out.replace("GET[" + group + "]", getWashingMachine.getCleanValue(group));
            }*/



    public function parseString( string $input ): string {
        $pattern = "/POST\[([a-zA-Z0-9]+)\]/";
        $replacement = "1";

        $matches = preg_replace($pattern, $replacement, $input);

        print_r($matches);

        return $matches;
    }

}
