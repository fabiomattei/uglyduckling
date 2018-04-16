<?php
	
function make_link_new( $chapter_slug, $action ) {
	return '<a href="'.make_url( 'riskregister', 'risknew' ).'" class="btn btn-success pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">New</a>';
}

function make_link_edit( $chapter_slug, $action, $item_id ) {
	return make_link( '<i class="fa fa-pencil text-inverse m-r-10"></i>', $chapter_slug, $action, array(
		'parameters' => htmlspecialchars( $item_id ),
		'title' => 'Edit',
	) );
}

function make_link_delete( $chapter_slug, $action, $item_id, $error_message ) {
	return make_link( '<i class="fa fa-close text-danger"></i>',
		Riskregister::$chapter_slug, 
		'riskdelete', 
		array( 
			'parameters' => htmlspecialchars( $item_id ),
			'onclick' => $error_message,
			'title' => 'Delete',
		) );
}

function make_link_pdf( $chapter_slug, $action, $item_id ) {
	return make_link( '<i class="fa fa-file-pdf-o text-inverse m-r-10"></i>', $chapter_slug, $action, array(
		'parameters' => htmlspecialchars( $item_id ),
		'title' => 'Pdf',
	) );
}

function make_link_excel( $chapter_slug, $action, $item_id ) {
	return make_link( '<i class="fa fa-file-excel-o text-inverse m-r-10"></i>', $chapter_slug, $action, array(
		'parameters' => htmlspecialchars( $item_id ),
		'title' => 'Excel',
	) );
}

function make_link_info( $chapter_slug, $action, $item_id ) {
	return make_link( '<i class="fa fa-newspaper-o text-inverse m-r-10"></i>', $chapter_slug, $action, array(
		'parameters' => htmlspecialchars( $item_id ),
		'title' => 'Info',
	) );
}

function make_link_view( $chapter_slug, $action, $item_id ) {
	return make_link( '<i class="fa fa-sitemap text-inverse m-r-10"></i>', $chapter_slug, $action, array(
		'parameters' => htmlspecialchars( $item_id ),
		'title' => 'View',
	) );
}

function make_link_log( $chapter_slug, $action, $item_id ) {
	return make_link( '<i class="fa fa-archive text-inverse m-r-10"></i>', $chapter_slug, $action, array(
		'parameters' => htmlspecialchars( $item_id ),
		'title' => 'Log',
	) );
}
