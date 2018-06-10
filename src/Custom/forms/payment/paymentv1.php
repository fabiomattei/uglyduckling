<?php

require_once( 'core/paperworks/basicpaper.php' );

/**
 * Created by Fabio Mattei <burattino@gmail.com>
 * Date: 30/03/2017
 * Time: 23:10
 */
class PaymentV1 extends BasicPaper {

    const SLUG        = 'paymentv1';
    const BASE_SLUG   = 'payment';
    const FAMILY_SLUG = 'expense';
    const HUMAN_NAME  = 'Payment V1';

    public $validation = array(
        'id'                => 'required|integer',
        'description'       => 'max_len,2500',
        'duedate'           => 'max_len,10',
    );

    public $filter = array(
        'id'                => 'trim',
        'description'       => 'trim',
        'duedate'           => 'trim',
	);

    public $statuses = array(
        'received'      => 'RECEIVED',
        'inprogress'    => 'IN PROGRESS',
        'closed'        => 'CLOSED/MONITORED',
    );

    function make_entity_for_saving( $array_containing_fields ) {
        $entity = array();

        $entity['description'] = ( isset($array_containing_fields['description']) ? $array_containing_fields['description'] : '' );
		$entity['duedate'] = '';
		if ( isset($array_containing_fields['duedate']) ) {
			$duedate = explode('/', $array_containing_fields['duedate']);
			$entity['duedate'] = $duedate[2].'-'.$duedate[1].'-'.$duedate[0];
		}
		
        return $entity;
    }

    function make_indexes_for_saving( $array_containing_fields ) {
        $entity = array();
        return $entity;
    }

    function make_title( $array_containing_fields ) {
        $out = '';
        $out .= ( isset($array_containing_fields['description'] ) ? $array_containing_fields['description'] : '' );
        return $out;
    }

    function get_form( $paper_data = null, $paper_id = 0, $parent_paper_id = 0 ) {
        utils( 'xmlserializer' );
        $entity = ( $paper_data == null ? null : simplexml_load_string( $paper_data ) );

        $id = $paper_id;
        $description = ( $entity == null ? '' : $entity->description );
        $duedate = ( $entity == null ? date( 'd/m/Y' ) : date( 'd/m/Y', strtotime($entity->duedate) ) );

        $out = '<br />
        Description:<br />
		<textarea name="description" cols="120" rows="3">'.$description.'</textarea><br /><br />
        Due date:<br />
		<input type="text" id="datepicker" class="form-control" name="duedate" value="'.$duedate.'"><br /><br />
        <input type="hidden" name="id" value="'.$id.'">';

        return $out;
    }
	
    function form_addToHead() {
        return '<link rel="stylesheet" href="'.BASEPATH.'assets/jquery-ui/jquery-ui.css">';
    }

    function form_addToFoot() {
        return '<script src="'.BASEPATH.'assets/jquery-ui/jquery-ui.min.js"></script>
 		   	<script>
  		  		$(function() {
    				$( "#datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
  				});
  			</script>';
    }

    function get_view( $entity = null ) {
        utils( 'xmlserializer' );

        $entity = simplexml_load_string($entity->fi_data);

        $description = ( $entity->description == null ? '' : $entity->description );
        $duedate = ( $entity->duedate == null ? '' : date( 'd/m/Y', strtotime( $entity->duedate )) );

        $out = '<br />
        Description: '.$description.'<br /><br />
        Due date: '.$duedate.'<br /><br />';

        return $out;
    }

    function get_short_view( $entity = null ) {
        utils( 'xmlserializer' );

        $entity = simplexml_load_string($entity->fi_data);

        $description = ( $entity->description == null ? '' : $entity->description );
        $duedate = ( $entity->duedate == null ? '' : date( 'd/m/Y', strtotime( $entity->duedate )) );

        $out = '<br />
        Description: '.$description.'<br /><br />
        Due date: '.$duedate.'<br /><br />';

        return $out;
    }

}
