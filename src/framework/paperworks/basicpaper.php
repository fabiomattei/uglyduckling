<?php

/**
 * Created by Fabio Mattei <matteif@tcd.ie>
 * Date: 20/05/2016
 * Time: 12:32
 */
class BasicPaper {

    public $class_name              = 'BasicPaper';
    public $slug                    = 'basicpaper';
    public $base_slug               = 'basicpaper';
    public $family_slug             = 'basicpaper';
    public $human_name              = 'Basic Paper';
    public $destination_office_slug = 'safety';
    public $default_flow_slug       = 'basicv1';
    public $tablename               = 'pwpaper';
    public $can_read                = array( '1' );
    public $can_edit                = array( '1' );
    public $can_receive             = array( '1' ); // destination offices

    public $fields = array();

    public $validation = array(
        'id' => 'required|integer',
    );

    public $filter = array(
        'id'  => 'trim',
    );

    public $related_documents = array();

    function get_fields() {
        return $this->fields;
    }

    function get_validation_rules() {
        return $this->validation;
    }

    function get_related_documents() {
        return $this->related_documents;
    }

    function make_entity_for_saving( $array_containing_fields ) {
        return array();
    }

    function make_action_after_saving( $paper_id ) {
        return 0;
    }

    function make_indexes_for_saving( $array_containing_fields ) {
        return array();
    }

    function make_title( $array_containing_fields ) {
        return 'No title';
    }

    function get_form( $paper_data = null, $paper_id = 0, $parent_paper_id = 0 ) {
        $out = 'No Form defined';
        return $out;
    }

    function get_planning_form( $paper_data = null, $paper_id = 0 ) {
        return $this->get_form();
    }
	
	function form_load_entites( $paper_data, $connection = '' ) {
		
	}
	
    function form_addToHead() {
        return '';
    }

    function form_addToFoot() {
        return '';
    }

    function get_view( $entity = null ) {
        $out = 'No view defined';
        return $out;
    }

    function get_short_view( $entity = null ) {
        $out = 'No view defined';
        return $out;
    }

    function get_table_column_names() {
        return '<th>id</th><th>Title</th>';
    }

    function get_table_column_row( $entity ) {
        return '<th>'.$entity->pwp_id.'</th><th>'.$entity->pwp_title.'</th>';
    }
	
	function check_field_content( $field='', $default_value='' ) {
		return ( isset($field) ? $field : $default_value );
	}

}
