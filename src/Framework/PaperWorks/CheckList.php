<?php

namespace Fabiom\UglyDuckling\Framework\PaperWorks;

class CheckList {

    public $class_name      = 'None';
    public $slug            = 'none';
    public $human_name      = 'None';
    public $question_number = 0;

    public $questions = array();

    function get_question_text( $question_id ) {
        return $this->questions[$question_id];
    }

}
