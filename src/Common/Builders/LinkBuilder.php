<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Router\Router;
use Firststep\Common\Blocks\Button;

class LinkBuilder {

    static function get( $router, $lable, $action, $resource, $parameters, $entity ) {
        $url_parameters = 'res='.$resource.'&';
        foreach ($parameters as $par) {
            $url_parameters .= $par->name.'='.$entity->{$par->sqlfield}.'&';
        }
        $url_parameters = rtrim($url_parameters, '&');
        switch ( $action ) {
            case 'entitychart':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_CHART, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitytable':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_TABLE, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL); 
                break;
            case 'entityform':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityinfo':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_INFO, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitysearch':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_SEARCH, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityexport':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_EXPORT, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitylogic':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
	        case 'documentinbox':
	            return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_INBOX ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
	            break;
            case 'documentacceptedbox':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_ACCEPTEDBOX ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'documentrejectedbox':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_REJECTEDBOX ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
			case 'documentaccept':
				return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_ACCEPT ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
				break;
		    case 'documentoutbox':
		        return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_OUTBOX ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
		        break;
			case 'documentoutboxuser':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_OUTBOX_USER ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
			case 'documentdraft':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DRAFT ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
			case 'documentdraftuser':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DRAFT_USER ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;				
		    case 'documentdelete':
		        return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DELETE, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
		        break;
	        case 'documentedit':
	            return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EDIT, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
	            break;
	        case 'documentexport':
	            return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EXPORT, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
	            break;
		    case 'documentnew':
		        return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
		        break;
			case 'documentreject':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_REJECT, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
			case 'documentnewlist':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW_LIST ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
			case 'documentsearch':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_SEARCH, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
            
            default:
                return '#';
                break;
        }
    } 

    static function getURL( $router, $action, $resource ) {
        $url_parameters = 'res='.$resource;
        switch ( $action ) {
            case 'entitychart':
                return $router->make_url( Router::ROUTE_OFFICE_ENTITY_CHART, $url_parameters );
                break;
            case 'entitytable':
                return $router->make_url( Router::ROUTE_OFFICE_ENTITY_TABLE, $url_parameters ); 
                break;
            case 'entityform':
                return $router->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, $url_parameters );
                break;
            case 'entityinfo':
                return $router->make_url( Router::ROUTE_OFFICE_ENTITY_INFO, $url_parameters );
                break;
            case 'entitysearch':
                return $router->make_url( Router::ROUTE_OFFICE_ENTITY_SEARCH, $url_parameters );
                break;
            case 'entityexport':
                return $router->make_url( Router::ROUTE_OFFICE_ENTITY_EXPORT, $url_parameters );
                break;
            case 'entitylogic':
                return $router->make_url( Router::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters );
                break;
	        case 'documentinbox':
	            return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_INBOX );
	            break;
		    case 'documentaccept':
		        return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_ACCEPT, $url_parameters );
		        break;
			case 'documentoutbox':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_OUTBOX, $url_parameters );
			    break;
            case 'documentacceptedbox':
                return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_ACCEPTEDBOX, $url_parameters );
                break;
            case 'documentrejectedbox':
                return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_REJECTEDBOX, $url_parameters );
                break;
			case 'documentoutboxuser':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_OUTBOX_USER, $url_parameters );
			    break;
			case 'documentdraft':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DRAFT, $url_parameters );
			    break;
			case 'documentdraftuser':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DRAFT_USER, $url_parameters );
			    break;
			case 'documentdelete':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DELETE, $url_parameters );
			    break;
			case 'documentedit':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EDIT, $url_parameters );
			    break;
			case 'documentexport':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EXPORT, $url_parameters );
			    break;
			case 'documentnew':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW, $url_parameters );
			    break;
			case 'documentreject':
				return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_REJECT, $url_parameters );
				break;
			case 'documentnewlist':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW_LIST );
			    break;
			case 'documentsearch':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_SEARCH, $url_parameters );
			    break;
            
            default:
                return '#';
                break;
        }
    }    
}
