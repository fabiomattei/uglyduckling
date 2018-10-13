<?php

namespace Firststep\Common\Json;

use Firststep\Common\Builders\FormBuilder;
use Firststep\Common\Builders\FormBuilderDocumentV1;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonBlockFormParser {
	
	public function parse( $resource, $entity, $action ) {
		if ( $resource->metadata->type === 'form' ) {
			$formBuilder = new FormBuilder;
			$formBuilder->setFormStructure( $resource->form );
			$formBuilder->setEntity( $entity );
			$formBuilder->setAction( $action );
			return $formBuilder->createForm();
		}
		if ( $resource->metadata->type === 'document' ) {
			$formBuilder = new FormBuilderDocumentV1;
			$formBuilder->setFormStructure( $resource );
			$formBuilder->setEntity($entity);
			$formBuilder->setAction( $action );
			return $formBuilder->createForm();
		}
	}
	
}
