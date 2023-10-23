<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 15/06/2016
 * Time: 17:26
 */

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
