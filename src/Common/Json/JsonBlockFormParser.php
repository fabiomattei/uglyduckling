<?php

namespace Firststep\Common\Json;

use Firststep\Common\Json\JsonTemplates\FormBuilderDocumentV1;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonBlockFormParser {
	
	public function parse( $resource, $entity, $action ) {
		if ( $resource->metadata->type === 'document' ) {
			$formBuilder = new FormBuilderDocumentV1;
			$formBuilder->setFormStructure( $resource );
			$formBuilder->setEntity( $entity );
			$formBuilder->setAction( $action );
			return $formBuilder->createForm();
		}
	}
	
}
