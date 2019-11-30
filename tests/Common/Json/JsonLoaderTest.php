<?php

class EJsonLoader extends \Fabiom\UglyDuckling\Common\Json\JsonLoader {

    public function setResourcesIndex(array $resourcesIndex) {
        $this->resourcesIndex = $resourcesIndex;
    }
}


/**
 *  Testing the JsonParametersParser class
 *
 *  @author Fabio Mattei
 */
class JsonLoaderTest extends PHPUnit\Framework\TestCase {

    private $json = '[{ "path":"./Json/groups/author.json", "type":"group", "name":"author" },
    { "path":"./Json/groups/administrationgroup.json", "type":"group", "name":"administrationgroup" },
    { "path":"./Json/articles/articlestable.json", "type":"table", "name":"articlestable" },
    { "path":"./Json/reportstable.json", "type":"table", "name":"reportstable" },
    { "path":"./Json/articles/newarticleform.json", "type":"form", "name":"newarticleform" },
    { "path":"./Json/articles/editarticleform.json", "type":"form", "name":"editarticleform" },
    { "path":"./Json/articles/deletearticletransaction.json", "type":"transaction", "name":"deletearticletransaction" },
    { "path":"./Json/extentions/index.json", "type":"index" }]';


    public function testCanExtractFromList(){
        $jsonLoader = new EJsonLoader;
        $jsonLoader->setResourcesIndex(json_decode($this->json));
        $this->assertSame(2, count($jsonLoader->getResourcesByType('table')));
    }

}
