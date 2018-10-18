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
            $url_parameters .= $par->name.'='.$entity->{$par->value}.'&';
        }
        $url_parameters = rtrim($url_parameters, '&');
        switch ( $action ) {
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
	        case 'officedocumentinbox':
	            return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_INBOX ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
	            break;
			case 'officedocumentaccept':
				return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_ACCEPT ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
				break;
		    case 'officedocumentoutbox':
		        return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_OUTBOX ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
		        break;
			case 'officedocumentoutboxuser':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_OUTBOX_USER ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
			case 'officedocumentdraft':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DRAFT ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
			case 'officedocumentdraftuser':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DRAFT_USER ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;				
		    case 'officedocumentdelete':
		        return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DELETE, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
		        break;
	        case 'officedocumentedit':
	            return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EDIT, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
	            break;
	        case 'officedocumentexport':
	            return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EXPORT, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
	            break;
		    case 'officedocumentnew':
		        return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
		        break;
			case 'officedocumentreject':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_REJECT, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
			case 'officedocumentnewlist':
			    return Button::get($router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW_LIST ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
			    break;
			case 'officedocumentsearch':
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
	        case 'officedocumentinbox':
	            return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_INBOX );
	            break;
		    case 'officedocumentaccept':
		        return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_ACCEPT, $url_parameters );
		        break;
			case 'officedocumentoutbox':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_OUTBOX, $url_parameters );
			    break;
			case 'officedocumentoutboxuser':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_OUTBOX_USER, $url_parameters );
			    break;
			case 'officedocumentdraft':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DRAFT, $url_parameters );
			    break;
			case 'officedocumentdraftuser':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DRAFT_USER, $url_parameters );
			    break;
			case 'officedocumentdelete':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_DELETE, $url_parameters );
			    break;
			case 'officedocumentedit':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EDIT, $url_parameters );
			    break;
			case 'officedocumentexport':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EXPORT, $url_parameters );
			    break;
			case 'officedocumentnew':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW, $url_parameters );
			    break;
			case 'officedocumentreject':
				return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_REJECT, $url_parameters );
				break;
			case 'officedocumentnewlist':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW_LIST );
			    break;
			case 'officedocumentsearch':
			    return $router->make_url( Router::ROUTE_OFFICE_DOCUMENT_SEARCH, $url_parameters );
			    break;
            
            default:
                return '#';
                break;
        }
    }    
}
