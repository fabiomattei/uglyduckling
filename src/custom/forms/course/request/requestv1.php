<?php

require_once( 'core/paperworks/basicpaper.php' );

/**
 * Created by Fabio Mattei <burattino@gmail.com>
 * Date: 31/03/2017
 * Time: 18:29
 */
class RequestV1 extends BasicPaper {

    const SLUG        = 'requestv1';
    const BASE_SLUG   = 'request';
    const FAMILY_SLUG = 'request';
    const HUMAN_NAME  = 'Request V1';

    public $validation = array(
        'id'                => 'required|integer',
        'description'       => 'max_len,2500',
        'amount'            => 'required|numeric',
        'duedate'           => 'max_len,10',
    );

    public $filter = array(
        'id'                => 'trim',
        'description'       => 'trim',
		'amount'            => 'trim',
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
		$entity['amount'] = ( isset($array_containing_fields['amount']) ? $array_containing_fields['amount'] : 0 );
		$entity['duedate'] = '';
		if ( isset($array_containing_fields['duedate']) ) {
			$duedate = explode('/', $array_containing_fields['duedate']);
			$entity['duedate'] = $duedate[2].'-'.$duedate[1].'-'.$duedate[0];
		}
		
        return $entity;
    }

    function make_indexes_for_saving( $array_containing_fields ) {
        $entity = array();
		
        // fi_dind1 :: amount
        $entity['fi_dind1'] = ( isset($array_containing_fields['amount']) ? $array_containing_fields['amount'] : 0.00 );
		
        return $entity;
    }

    function make_title( $array_containing_fields ) {
        $out = '';
        $out .= ( isset($array_containing_fields['description']) ? $array_containing_fields['description'] : 1 );
        return $out;
    }

    function get_form( $paper_data = null, $paper_id = 0, $parent_paper_id = 0 ) {
        utils( 'xmlserializer' );
        $entity = ( $paper_data == null ? null : simplexml_load_string( $paper_data ) );

        $id = $paper_id;
        $description = ( $entity == null ? '' : $entity->description );
		$amount = ( $entity == null ? 0.00 : $entity->amount );
        $duedate = ( $entity == null ? date( 'd/m/Y' ) : date( 'd/m/Y', strtotime($entity->duedate) ) );

        $out = '<br />
        Description:<br />
		<textarea name="description" cols="120" rows="3">'.$description.'</textarea><br /><br />
        Amount (&euro;): <br />
		<input type="number" name="amount" value="'.$amount.'" min="0" step="0.01" ><br /><br />
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
		$amount = ( $entity->amount == null ? 0.00 : $entity->amount );
        $duedate = ( $entity->duedate == null ? '' : date( 'd/m/Y', strtotime( $entity->duedate )) );

        $out = '<br />
        Description: '.$description.'<br /><br />
		Amount (&euro;): '.$amount.'<br /><br />
        Due date: '.$duedate.'<br /><br />';

        return $out;
    }

    function get_short_view( $entity = null ) {
        utils( 'xmlserializer' );

        $entity = simplexml_load_string($entity->fi_data);

        $description = ( $entity->description == null ? '' : $entity->description );
        $amount = ( $entity->amount == null ? 0.00 : $entity->amount );
        $duedate = ( $entity->duedate == null ? '' : date( 'd/m/Y', strtotime( $entity->duedate )) );

        $out = '<br />
        Description: '.$description.'<br /><br />
        Amount (&euro;): '.$amount.'<br /><br />
        Due date: '.$duedate.'<br /><br />';

        return $out;
    }

}
