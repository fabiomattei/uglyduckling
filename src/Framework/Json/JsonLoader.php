<?php

namespace Fabiom\UglyDuckling\Framework\Json;

class JsonLoader {

    /**
     * Load a resource from file specified with array index
     *
     * @param string $resourceName
     * @return mixed, a php structure that mirrors the json structure
     * @throws \Exception
     */
    static public function loadResource( array $index_resources, string $resourceName ) {
        if ( array_key_exists( $resourceName, $index_resources ) ) {
            if (self::isMobile()) {
                if ( file_exists( self::addMobileToResourceName($index_resources[$resourceName]) ) ) {
                    // try to load mobile resource if exists
                    $handle = fopen( self::addMobileToResourceName($index_resources[$resourceName]), 'r');
                    return JsonLoader::json_decode_with_error_control(fread($handle, filesize($index_resources[$resourceName])), $index_resources[$resourceName] );
                } elseif ( file_exists( $index_resources[$resourceName] ) ) {
                    // if mobile resource does not exist load normal resource
                    $handle = fopen($index_resources[$resourceName], 'r');
                    return JsonLoader::json_decode_with_error_control(fread($handle, filesize($index_resources[$resourceName])), $index_resources[$resourceName] );
                } else {
                    throw new \Exception('[JsonLoader] :: Path associated to resource does not exists!!! Path required: ' . $index_resources[$resourceName]->path);
                }
            } else {
                if ( file_exists( $index_resources[$resourceName] ) ) {
                    // load resource normally
                    $handle = fopen($index_resources[$resourceName], 'r');
                    return JsonLoader::json_decode_with_error_control(fread($handle, filesize($index_resources[$resourceName])), $index_resources[$resourceName] );
                } else {
                    throw new \Exception('[JsonLoader] :: Path associated to resource does not exists!!! Path required: ' . $index_resources[$resourceName]->path);
                }
            }
        } else {
            throw new \Exception('[JsonLoader] :: Resource '.$resourceName.' undefined in array index!!!');
        }
    }

    /**
     * Return true if the access is done by a mobile browser
     *
     * @return bool
     */
    static public function isMobile(): bool {
        return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)));
    }

    /**
     * A mobile resource name is a resource having a normal name with the
     * addition of "-mobile" to the end.
     * Ex:
     * if a reusource is named: my-users-list.json
     * then the mobile name is going to be: my-users-list-mobile.json
     *
     * @param $resource
     * @return string
     */
    static public function addMobileToResourceName( $resource ): string {
        return substr($resource, 0, -4).'mobile.json';
    }

    /**
     * Check if a resource exists in the index and if the associated file
     * esists in the filesystem.
     *
     * @param array $index_resources
     * @param string $resourceName
     * @return bool
     */
    static public function isJsonResourceIndexedAndFileExists( array $index_resources, string $resourceName ) {
        if ( array_key_exists( $resourceName, $index_resources ) ) {
            return file_exists( $index_resources[$resourceName] );
        } else {
            return false;
        }
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
    static public function json_decode_with_error_control( string $jsondata, string $fileNameAndPath ) {
        $loadeddata = json_decode( $jsondata );
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                // throw new \Exception(' - No errors');
                break;
            case JSON_ERROR_DEPTH:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Maximum stack depth exceeded ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            case JSON_ERROR_STATE_MISMATCH:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Underflow or the modes mismatch ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            case JSON_ERROR_CTRL_CHAR:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Unexpected control character found ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            case JSON_ERROR_SYNTAX:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Syntax error, malformed JSON ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            case JSON_ERROR_UTF8:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Malformed UTF-8 characters, possibly incorrectly encoded ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            default:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Unknown error ::'. $fileNameAndPath .' '. json_last_error_msg());
                break;
        }
        return $loadeddata;
    }

}