<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

use Fabiom\UglyDuckling\Framework\Utils\PageStatus;

class BaseHTMLStaticBlock extends BaseHTMLBlock {

    public PageStatus $pageStatus;

    public function __construct( PageStatus $pageStatus) {
        parent::__construct();
        $this->pageStatus = $pageStatus;
    }

    /**
     * Example
     *
     * [ 'username'    => 'required|alpha_numeric|max_len,100|min_len,6',
     *   'password'    => 'required|max_len,100|min_len,6',
     *   'email'       => 'required|valid_email',
     *   'gender'      => 'required|exact_len,1|contains,m;f',
     *   'credit_card' => 'required|valid_cc' ]
     *
     * @return array
     */
    function getParametersValidationRules() {
        return [];
    }

    /**
     * Example
     *
     * [ 'username' => 'trim|sanitize_string',
     *   'password' => 'trim',
     *   'email'    => 'trim|sanitize_email',
     *   'gender'   => 'trim',
     *   'bio'      => 'noise_words' ]
     *
     * @return array
     */
    function getParametersFilterRules() {
        return [];
    }

}
