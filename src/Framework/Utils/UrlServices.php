<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

class UrlServices {

    public static function extractActionName( string $requestURI, string $pathToApp = '/' ) {
        $request2 = str_replace( ['.html', '.pdf', '.svg', '.json'], '',$requestURI );
        $request  = preg_replace( '/\?.*/', '', $request2 );

        if ( $request == '' ) throw new \Exception('General malfuction!!!');

        # removing the first '/' from the path
        $action = substr( $request, strlen($pathToApp) ); // explode( '/', $request );

        if (!UrlServices::validate_string( $action )) {
            throw new \Exception('Illegal access to calculateSplittedURL!!! Unvalidated action: "' . $action . '" over request: ' . $requestURI);
        }

        return $action;
    }

    public static function getRequestURI(): string {
        return ServerWrapper::getRequestURI();
    }

    public static function validate_string( $string_var ) {
        if ( strlen( $string_var ) == 0 ) return true;
        if ( strlen( $string_var ) > 200 ) return false;
        if ( ctype_alnum( str_replace( ['-', '_'], '', $string_var) ) ) return true;
        return false;
    }


    /**
     * @param mixed $json_action
     * @param PageStatus $pageStatus
     * @return mixed
     *
     * Example of a json action:
     *
     * {
     *   "type": "link",
     *   "label": "Info",
     *   "resource": "myinfopanel",
     *   "tooltip": "My tool tip text",
     *   "onclick": "My on click text",
     *   "buttoncolor": "green",
     *   "outline": false,
     *   "parameters":[
     *     {"name": "id", "sqlfield": "id"},
     *     {"name": "secondid", "constantparameter": "3"},
     *     {"name": "thirdid", "getparameter": "mygetparameter"}
     *   ]
     * }
     *
     * I case I want to link a controller
     * {
     *   "type": "link",
     *   "label": "Info",
     *   "controller": "myinfopanel",
     *   "tooltip": "My tool tip text",
     *   "onclick": "My on click text",
     *   "buttoncolor": "green",
     *   "outline": false,
     *   "parameters":[
     *     {"name": "id", "sqlfield": "id"},
     *     {"name": "secondid", "constantparameter": "3"},
     *     {"name": "thirdid", "getparameter": "mygetparameter"}
     *   ]
     * }
     *
     * In case I want to link a URL
     * {
     *   "type": "link",
     *   "label": "Info",
     *   "url": "www.google.com",
     *   "tooltip": "My tool tip text",
     *   "onclick": "My on click text",
     *   "buttoncolor": "green",
     *   "outline": false
     * }
     *
     * Check out: http://www.uddocs.com/docs/actions
     */
    public static function make_resource_url( $json_action, PageStatus $pageStatus ) {
        if ( isset( $json_action->url ) ) {
            return $json_action->url;
        }

        if ( isset( $json_action->resource ) AND isset( $json_action->controller ) AND $json_action->controller == 'partial') {
            $url_parameters = 'udpartial=true&';
            if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
                foreach ($json_action->parameters as $par) {
                    $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
                }
                $url_parameters = rtrim( $url_parameters, '&' );
            }
            return UrlServices::makeRelativeUrl( $json_action->resource, $url_parameters );
        }

        if ( isset( $json_action->resource ) AND isset( $json_action->controller ) ) {
            $url_parameters = 'res=' . $json_action->resource . '&';
            if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
                foreach ($json_action->parameters as $par) {
                    $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
                }
                $url_parameters = rtrim( $url_parameters, '&' );
            }
            return UrlServices::makeRelativeUrl( $json_action->controller, $url_parameters );
        }

        if ( isset( $json_action->controller ) ) {
            $url_parameters = '';
            if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
                foreach ($json_action->parameters as $par) {
                    $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
                }
                $url_parameters = rtrim($url_parameters, '&');
            }
            return UrlServices::makeRelativeUrl( $json_action->controller, $url_parameters );
        }

        if ( isset( $json_action->resource ) ) {
            $url_parameters = '';
            if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
                foreach ($json_action->parameters as $par) {
                    $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
                }
                $url_parameters = rtrim($url_parameters, '&');
            }
            return UrlServices::makeRelativeUrl( $json_action->resource, $url_parameters );
        }

        // going to default controller
        if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
            $url_parameters = '';
            foreach ($json_action->parameters as $par) {
                $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
            }
            $url_parameters = rtrim($url_parameters, '&');
        }
        return UrlServices::makeRelativeUrl( $json_action->resource, $url_parameters );

        // TODO Activate this in future
        //throw new \Exception('[UD Error] No action or controller or URL defined');
    }

    /**
     * It creates a URL appending the content of variable $_SESSION['office'] to BASEPATH
     * The URL created is relative and not absolute
     *
     * Result is: BASEPATH . $_SESSION['office'] . $final_part
     *
     * @param        string     Action
     * @param        string     Parameters: string containing all parameters separated by '/'
     * @param        string     Extension:  .html by default
     *
     * @return       string     The url well formed
     */
    public static function makeRelativeUrl( $controllerSlug = '', $parameters = '', $extension = '.html' ) {
        if ( $controllerSlug == '' ) {
            return '#';
        } else {
            return $controllerSlug.$extension.( $parameters == '' ? '' : '?'.$parameters );
        }
    }

    public static function urlencode($parameter)
    {
        return urlencode($parameter);
    }

}
