<?php

namespace Firststep\Common\Json;

use Firststep\Common\Json\JsonBlockFormParser;
use Firststep\Common\Blocks\EmptyBlock;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonBlockParser {
	
	public static function parseResourceForBlock( $resource, $entity ) {
		if ($resource->metadata->type == 'form') {
			return JsonBlockFormParser::parse($resource->form, $entity);
		}
		if ($resource->metadata->type == 'form') {
			return JsonBlockInfoParser::parse($resource->info, $entity);
		}
		return new EmptyBlock;
	}
	
}
