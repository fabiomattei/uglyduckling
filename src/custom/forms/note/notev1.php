<?php

namespace custom\forms\note;

use core\paperworks\BasicPaper;

/**
 * Created by Fabio Mattei <burattino@gmail.com>
 * Date: 31/03/2017
 * Time: 18:28
 */
class NoteV1 extends BasicPaper {

    public $slug        = 'notev1';
    public $base_slug   = 'note';
    public $family_slug = 'note';
    public $human_name  = 'Note V1';

    public $validation = array(
        'id'                => 'required|integer',
        'description'       => 'max_len,2500',
    );

    public $filter = array(
        'id'                => 'trim',
        'description'       => 'trim',
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
		
        return $entity;
    }

    function make_indexes_for_saving( $array_containing_fields ) {
        return array();
    }

    function make_title( $array_containing_fields ) {
        $out = '';
        $out .= ( isset($array_containing_fields['description']) ? $array_containing_fields['description'] : '' );
        return $out;
    }

    function get_form( $paper_data = null, $paper_id = 0, $parent_paper_id = 0 ) {
        utils( 'xmlserializer' );
        $entity = ( $paper_data == null ? null : simplexml_load_string( $paper_data ) );

        $id = $paper_id;
        $description = ( $entity == null ? '' : $entity->description );

        $out = '<br />
        Description:<br />
		<textarea name="description" cols="120" rows="3">'.$description.'</textarea><br /><br />
        <input type="hidden" name="id" value="'.$id.'">';

        return $out;
    }

    function get_view( $entity = null ) {
        utils( 'xmlserializer' );

        $entity = simplexml_load_string($entity->fi_data);

        $description = ( $entity->description == null ? '' : $entity->description );

        $out = '<br />Description: '.$description.'<br /><br />';

        return $out;
    }

    function get_short_view( $entity = null ) {
        utils( 'xmlserializer' );

        $entity = simplexml_load_string($entity->fi_data);

        $description = ( $entity->description == null ? '' : $entity->description );

        $out = '<br />Description: '.$description.'<br /><br />';

        return $out;
    }

}
