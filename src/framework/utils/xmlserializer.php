<?php
/**
 * Created by Fabio Mattei <matteif@tcd.ie>
 * Date: 03/02/2016
 * Time: 10:51
 */

class XMLSerializer {

    /**
     * This static function takes a stdClass and creates a string with the object serialized in an XML
     * For each field of the object it creates an XML tag that contains the field content.
     *
     * The node_block parameter gives the name to the TAG that sourrond all the fields ( default is
     * case is nodes )
     * <nodes>
     * <field1>content1</field1>
     * <field2>content2</field2>
     * </nodes>
     *
     * The node_name parameter make a sobstitution for each field that has a numeric name with 
     * the node_name string as TAG.
     * EX.
     *
     * XMLSerializer::generateValidXmlFromObj($input, 'mynodes', 'tagname')
     * 
     * $input       = new stdClass;
     * $input->id   = 42;
     * $input->name = 'hello';
     *
     * <?xml version="1.0" encoding="UTF-8" ?>
     * <mynodes>
     * <id>42</id>
     * <tagname>twelve</tagname>
     * </mynodes>
     * 
     * @param  stdClass      obj
     * @param  string        node_block
     * @param  string        node_name
     * @return string        containing the XML
     */
    public static function generateValidXmlFromObj(stdClass $obj, $node_block='nodes', $node_name='node') {
        $arr = get_object_vars($obj);
        return self::generateValidXmlFromArray($arr, $node_block, $node_name);
    }

    /**
     * This static function takes an array and creates a string with the array serialized in an XML.
     * For each item of the array it creates an XML tag that contains the item content.
     *
     * The node_block parameter gives the name to the TAG that sourrond all the fields ( default is
     * case is nodes )
     * <nodes>
     * <field1>content1</field1>
     * <field2>content2</field2>
     * </nodes>
     *
     * The node_name parameter make a sobstitution for each field that has a numeric name with 
     * the node_name string as TAG.
     * EX.
     *
     * XMLSerializer::generateValidXmlFromArray($input, 'mynodes', 'tagname')
     * 
     * $input         = array();
     * $input['id']   = 42;
     * $input['12']   = 'twelve';
     *
     * <?xml version="1.0" encoding="UTF-8" ?>
     * <mynodes>
     * <id>42</id>
     * <tagname>twelve</tagname>
     * </mynodes>
     * 
     * @param  stdClass      obj
     * @param  string        node_block
     * @param  string        node_name
     * @return string        containing the XML
     */
    public static function generateValidXmlFromArray($array, $node_block='nodes', $node_name='node') {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>';

        $xml .= '<' . $node_block . '>';
        $xml .= self::generateXmlFromArray($array, $node_name);
        $xml .= '</' . $node_block . '>';

        return $xml;
    }

    /**
     * This intenal function does the actual work
     * 
     * @param  array       array to transform in XML
     * @param  string      node_name
     * @return string      containing the XML
     */
    private static function generateXmlFromArray($array, $node_name) {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key=>$value) {
                if (is_numeric($key)) {
                    $key = $node_name;
                }

                $xml .= '<' . $key . '>' . self::generateXmlFromArray($value, $node_name) . '</' . $key . '>';
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES);
        }

        return $xml;
    }

}
