<?php

namespace Fabiom\UglyDuckling\Common\Json;
use stdClass;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonLoader {
	
	private $indexpath;
	private $resourcesIndex = array();
    private $resourceCache = array();

    /**
     * Set the path of the file containing the main index of the json structure
     *
     * @param string $indexpath
     */
	public function setIndexPath( string $indexpath ) {
		$this->indexpath = $indexpath;
	}

    /**
     * Load the main index file from a given path and it creates
     * an internal structure, the software can search from
     * in order to find a json resource
     *
     * @param string $indexPath
     * @throws \Exception
     */
	public function loadIndex( $indexPath = '' ) {
		if ($indexPath == '') {
			$indexPath = $this->indexpath;
		}
		if (file_exists ( $indexPath )) {
			$handle = fopen($indexPath, 'r');
			$data = fread($handle,filesize($indexPath));
			$loadedfile = $this->json_decode_with_error_control($data);
			foreach ($loadedfile->scripts as $key) {
				if($key->type === 'index') {
					$this->loadIndex( $key->path );
				} else {
					$this->resourcesIndex[$key->name] = new stdClass;
					$this->resourcesIndex[$key->name]->path = $key->path;
					$this->resourcesIndex[$key->name]->type = $key->type;
					$this->resourcesIndex[$key->name]->name = $key->name;
				}
			}
		}
		// var_dump( $this->resourcesIndex );	
	}

    /**
     * Load a resource from file specified with array index
     *
     * @param string $key
     * @return mixed, a php structure that mirrors the json structure
     * @throws \Exception
     */
	public function loadResource( string $key ) {
        if ( array_key_exists( $key, $this->resourceCache ) ) {
            return $this->resourceCache[$key];
        }
		if ( array_key_exists( $key, $this->resourcesIndex ) ) {
			if ( file_exists( $this->resourcesIndex[$key]->path ) ) {
				$handle = fopen($this->resourcesIndex[$key]->path, 'r');
                $resourceOut = $this->json_decode_with_error_control(fread($handle,filesize($this->resourcesIndex[$key]->path)));
                $this->resourceCache[$key] = $resourceOut;
				return $resourceOut;
			} else {
				throw new \Exception('[JsonLoader] :: File associated to resource does not exists!!!');
			}
		} else {
			throw new \Exception('[JsonLoader] :: Resource '.$key.' undefined in array index!!!');
		}
	}

    /**
     * Given the key of a specific resource id checks if the resource is defined and it returns the action to link it
     *
     * @param string $resource_key
     * @return string
     * @throws \Exception
     */
	public function getActionRelatedToResource( string $resource_key ): string {
        if ( array_key_exists( $resource_key, $this->resourcesIndex ) ) {
            switch ( $this->resourcesIndex[$resource_key]->type ) {
                case 'index': return '';
                case 'dashboard': return 'entitydashboard';
                case 'table': return 'entitytable';
                case 'form': return 'entityform';
                case 'info': return 'entityinfo';
                case 'search': return 'entitysearch';
                case 'export': return 'entityexport';
                case 'chart': return 'entitychart';
                case 'transaction': return 'entitytransaction';
            }
        } else {
            throw new \Exception('[JsonLoader] :: It is not possible to associate an action to Resource: '.$resource_key);
        }
    }

    /**
     * Return the index of all resources loaded from the main index
     */
    public function getResourcesIndex() {
        return $this->resourcesIndex;
    }

    /**
     * Return resources of a given type
     *
     * Type can be: chart, dashboard, document, entity, export, index, info, form, group, logic, table
     *
     * @param string $type
     */
	public function getResourcesByType( string $type ) {
	    $out = array();
        foreach ( $this->resourcesIndex as $res ) {
            if ( $res->type === $type ) {
                $out[] = $res;
            }
        }
        return $out;
    }

    /**
     * Decode json string with error control
     *
     * based on json_decode, it builds a php structure based on the json structure.
     * throws exceptions
     *
     * @param $data string that contains the json structure
     *
     * @return mixed, a php structure that mirrors the json structure
     *
     * @throws \InvalidArgumentException after the error check
     * JSON_ERROR_DEPTH
     * JSON_ERROR_STATE_MISMATCH
     * JSON_ERROR_CTRL_CHAR
     * JSON_ERROR_SYNTAX
     * JSON_ERROR_UTF8
     *
     */
	public function json_decode_with_error_control( string $data ) {
		$loadeddata = json_decode($data);
		switch (json_last_error()) {
        	case JSON_ERROR_NONE:
        	    // throw new \Exception(' - No errors');
        	break;
        	case JSON_ERROR_DEPTH:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Maximum stack depth exceeded ::'. json_last_error_msg());
        	break;
        	case JSON_ERROR_STATE_MISMATCH:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Underflow or the modes mismatch ::'. json_last_error_msg());
        	break;
        	case JSON_ERROR_CTRL_CHAR:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Unexpected control character found ::'. json_last_error_msg());
        	break;
        	case JSON_ERROR_SYNTAX:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Syntax error, malformed JSON ::'. json_last_error_msg());
        	break;
        	case JSON_ERROR_UTF8:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Malformed UTF-8 characters, possibly incorrectly encoded ::'. json_last_error_msg());
        	break;
        	default:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Unknown error ::'. json_last_error_msg());
        	break;
    	}
		return $loadeddata;
	}
	
}
